<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\AssignmentRule;
use App\Models\AssignmentCounter;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadAssignmentService
{
    /**
     * Auto-assign a lead based on active assignment rules
     */
    public function autoAssign(Lead $lead): ?User
    {
        // Get active rules ordered by priority
        $rules = AssignmentRule::where('is_active', true)
            ->orderBy('priority_order', 'asc')
            ->get();

        foreach ($rules as $rule) {
            // Check if this rule applies to the lead
            if ($this->ruleApplies($rule, $lead)) {
                $assignedUser = $this->executeRule($rule, $lead);
                
                if ($assignedUser) {
                    Log::info("Lead #{$lead->id} auto-assigned to User #{$assignedUser->id} via rule: {$rule->name}");
                    return $assignedUser;
                }
            }
        }

        return null;
    }

    /**
     * Check if a rule applies to the given lead
     */
    protected function ruleApplies(AssignmentRule $rule, Lead $lead): bool
    {
        // If no conditions, rule applies to all
        if (!$rule->conditions) {
            return true;
        }

        $conditions = $rule->conditions;

        // Check source-based conditions
        if (!empty($conditions['sources']) && $lead->lead_source_id) {
            if (!in_array($lead->lead_source_id, $conditions['sources'])) {
                return false;
            }
        }

        // Check location-based conditions
        if (!empty($conditions['locations']) && $lead->preferred_locations) {
            $leadLocations = is_array($lead->preferred_locations) 
                ? $lead->preferred_locations 
                : json_decode($lead->preferred_locations, true);
            
            if (!array_intersect($conditions['locations'], $leadLocations ?? [])) {
                return false;
            }
        }

        // Check priority-based conditions
        if (!empty($conditions['priorities']) && $lead->priority) {
            if (!in_array($lead->priority, $conditions['priorities'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Execute the assignment rule
     */
    protected function executeRule(AssignmentRule $rule, Lead $lead): ?User
    {
        if (!$rule->assigned_users || count($rule->assigned_users) === 0) {
            return null;
        }

        switch ($rule->type) {
            case 'round_robin':
                return $this->roundRobinAssignment($rule);
            
            case 'load_balance':
                return $this->loadBalanceAssignment($rule);
            
            case 'location':
            case 'source':
            case 'priority':
                // These use the same logic - just different conditions
                return $this->roundRobinAssignment($rule);
            
            default:
                return $this->roundRobinAssignment($rule);
        }
    }

    /**
     * Round-robin assignment
     */
    protected function roundRobinAssignment(AssignmentRule $rule): ?User
    {
        $counter = $rule->counter ?? AssignmentCounter::create([
            'rule_id' => $rule->id,
            'assignment_count' => 0,
        ]);

        $users = User::whereIn('id', $rule->assigned_users)
            ->orderBy('id')
            ->get();

        if ($users->isEmpty()) {
            return null;
        }

        // Find next user in rotation
        $lastAssignedIndex = -1;
        if ($counter->last_assigned_user_id) {
            $lastAssignedIndex = $users->search(function ($user) use ($counter) {
                return $user->id === $counter->last_assigned_user_id;
            });
        }

        $nextIndex = ($lastAssignedIndex + 1) % $users->count();
        $nextUser = $users[$nextIndex];

        // Update counter
        $counter->update([
            'last_assigned_user_id' => $nextUser->id,
            'assignment_count' => $counter->assignment_count + 1,
            'last_assigned_at' => now(),
        ]);

        return $nextUser;
    }

    /**
     * Load balance assignment (assign to user with least leads)
     */
    protected function loadBalanceAssignment(AssignmentRule $rule): ?User
    {
        $users = User::whereIn('id', $rule->assigned_users)
            ->withCount(['assignedLeads' => function ($query) {
                $query->whereNull('converted_at'); // Only count unconverted leads
            }])
            ->orderBy('assigned_leads_count', 'asc')
            ->first();

        return $users;
    }
}
