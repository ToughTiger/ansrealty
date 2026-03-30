<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CommunicationTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunicationTemplatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_communication::template');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CommunicationTemplate $communicationTemplate): bool
    {
        return $user->can('view_communication::template');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_communication::template');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CommunicationTemplate $communicationTemplate): bool
    {
        return $user->can('update_communication::template');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CommunicationTemplate $communicationTemplate): bool
    {
        return $user->can('delete_communication::template');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_communication::template');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, CommunicationTemplate $communicationTemplate): bool
    {
        return $user->can('force_delete_communication::template');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_communication::template');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, CommunicationTemplate $communicationTemplate): bool
    {
        return $user->can('restore_communication::template');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_communication::template');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, CommunicationTemplate $communicationTemplate): bool
    {
        return $user->can('replicate_communication::template');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_communication::template');
    }
}
