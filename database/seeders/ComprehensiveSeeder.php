<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Agent;
use App\Models\Builder;
use App\Models\Property;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Opportunity;
use App\Models\OpportunityStage;
use App\Models\SiteVisit;
use App\Models\Task;
use App\Models\Negotiation;
use App\Models\Commission;
use App\Models\Booking;

class ComprehensiveSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREATE USERS (Employees)
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ansrealty.com',
            'password' => Hash::make('password'),
            'user_type' => 'Admin',
            'employee_code' => 'EMP-00001',
            'mobile' => '9876543210',
            'joining_date' => now()->subYears(2),
            'status' => 'Active',
        ]);

        $manager = User::create([
            'name' => 'Rajesh Kumar (Manager)',
            'email' => 'rajesh@ansrealty.com',
            'password' => Hash::make('password'),
            'user_type' => 'Manager',
            'employee_code' => 'EMP-00002',
            'mobile' => '9876543211',
            'joining_date' => now()->subYear(),
            'status' => 'Active',
            'target_monthly' => 5000000,
            'reports_to' => $admin->id,
        ]);

        $employees = [
            [
                'name' => 'Priya Sharma (Sales Executive)',
                'email' => 'priya@ansrealty.com',
                'mobile' => '9876543212',
                'target' => 2000000,
            ],
            [
                'name' => 'Amit Patel (Sales Executive)',
                'email' => 'amit@ansrealty.com',
                'mobile' => '9876543213',
                'target' => 1800000,
            ],
            [
                'name' => 'Sneha Reddy (Sales Executive)',
                'email' => 'sneha@ansrealty.com',
                'mobile' => '9876543214',
                'target' => 2200000,
            ],
            [
                'name' => 'Vikram Singh (Telecaller)',
                'email' => 'vikram@ansrealty.com',
                'mobile' => '9876543215',
                'target' => 0,
            ],
        ];

        $employeeModels = [];
        foreach ($employees as $index => $emp) {
            $employeeModels[] = User::create([
                'name' => $emp['name'],
                'email' => $emp['email'],
                'password' => Hash::make('password'),
                'user_type' => strpos($emp['name'], 'Telecaller') ? 'Telecaller' : 'Employee',
                'employee_code' => 'EMP-0000' . ($index + 3),
                'mobile' => $emp['mobile'],
                'joining_date' => now()->subMonths(rand(3, 18)),
                'status' => 'Active',
                'target_monthly' => $emp['target'],
                'reports_to' => $manager->id,
            ]);
        }

        // 2. CREATE EXTERNAL AGENTS
        $agents = [
            [
                'name' => 'Suresh Properties',
                'company' => 'Suresh Real Estate Consultants',
                'mobile' => '9123456780',
                'email' => 'suresh@agents.com',
                'pan' => 'ABCPS1234A',
                'commission' => 2.5,
                'employee' => $employeeModels[0],
            ],
            [
                'name' => 'Meera Builders Associate',
                'company' => 'Meera Realty Group',
                'mobile' => '9123456781',
                'email' => 'meera@agents.com',
                'pan' => 'ABCPM1234B',
                'commission' => 2.0,
                'employee' => $employeeModels[0],
            ],
            [
                'name' => 'Ramesh Kumar',
                'company' => null,
                'mobile' => '9123456782',
                'email' => 'ramesh@agents.com',
                'pan' => 'ABCPR1234C',
                'commission' => 1.5,
                'employee' => $employeeModels[1],
            ],
            [
                'name' => 'Kavita Homes',
                'company' => 'Kavita Property Solutions',
                'mobile' => '9123456783',
                'email' => 'kavita@agents.com',
                'pan' => 'ABCPK1234D',
                'commission' => 2.0,
                'employee' => $employeeModels[1],
            ],
            [
                'name' => 'Anil Property Consultants',
                'company' => 'Anil & Associates',
                'mobile' => '9123456784',
                'email' => 'anil@agents.com',
                'pan' => 'ABCPA1234E',
                'commission' => 1.8,
                'employee' => $employeeModels[2],
            ],
        ];

        $agentModels = [];
        foreach ($agents as $agent) {
            $agentModels[] = Agent::create([
                'agent_type' => 'External',
                'name' => $agent['name'],
                'company_name' => $agent['company'],
                'email' => $agent['email'],
                'mobile' => $agent['mobile'],
                'pan_number' => $agent['pan'],
                'commission_percentage' => $agent['commission'],
                'commission_type' => 'Percentage',
                'assigned_employee_id' => $agent['employee']->id,
                'status' => 'Active',
                'joining_date' => now()->subMonths(rand(6, 24)),
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'bank_name' => 'HDFC Bank',
                'ifsc_code' => 'HDFC0001234',
            ]);
        }

        // 3. CREATE BUILDERS
        $builders = [
            ['name' => 'Lodha Group', 'rera' => 'P51800000001'],
            ['name' => 'Godrej Properties', 'rera' => 'P51800000002'],
            ['name' => 'Hiranandani Group', 'rera' => 'P51800000003'],
            ['name' => 'Oberoi Realty', 'rera' => 'P51800000004'],
            ['name' => 'Kalpataru Group', 'rera' => 'P51800000005'],
        ];

        $builderModels = [];
        foreach ($builders as $builder) {
            $builderModels[] = Builder::create([
                'name' => $builder['name'],
                'company_name' => $builder['name'] . ' Ltd.',
                'email' => strtolower(str_replace(' ', '', $builder['name'])) . '@builders.com',
                'phone' => '022' . rand(20000000, 29999999),
                'rera_number' => $builder['rera'],
                'website' => 'www.' . strtolower(str_replace(' ', '', $builder['name'])) . '.com',
            ]);
        }

        // 4. CREATE PROPERTIES
        $locations = ['Bandra West', 'Andheri East', 'Powai', 'Malad West', 'Thane West', 'Navi Mumbai'];
        $propertyTypes = ['Flat', 'Villa', 'Penthouse', 'Commercial'];

        $properties = [];
        for ($i = 0; $i < 20; $i++) {
            $type = $propertyTypes[array_rand($propertyTypes)];
            $location = $locations[array_rand($locations)];
            $builder = $builderModels[array_rand($builderModels)];
            $bedrooms = in_array($type, ['Flat', 'Penthouse']) ? rand(2, 4) : rand(3, 5);
            $carpetArea = $type === 'Villa' ? rand(2000, 4000) : rand(600, 1500);
            $priceMin = $carpetArea * rand(15000, 25000);

            $properties[] = Property::create([
                'name' => $builder->name . ' ' . $location . ' ' . $bedrooms . 'BHK',
                'builder_id' => $builder->id,
                'project_name' => $builder->name . ' ' . ['Paradise', 'Heights', 'Residency', 'Towers', 'Park'][rand(0, 4)],
                'location' => $location,
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '4000' . rand(50, 99),
                'rera_number' => 'P518000' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'property_type' => $type,
                'listing_type' => 'Sale',
                'carpet_area' => $carpetArea,
                'built_up_area' => $carpetArea * 1.2,
                'area_unit' => 'sqft',
                'bedrooms' => $bedrooms,
                'bathrooms' => $bedrooms,
                'balconies' => rand(1, 3),
                'parking' => rand(1, 2),
                'floor_number' => rand(5, 25),
                'total_floors' => rand(30, 45),
                'price_min' => $priceMin,
                'price_max' => $priceMin * 1.1,
                'price_unit' => 'INR',
                'amenities' => ['Swimming Pool', 'Gym', 'Club House', 'Garden', 'Security', 'Power Backup'],
                'possession_date' => now()->addMonths(rand(6, 24)),
                'possession_status' => ['Under Construction', 'Ready to Move'][rand(0, 1)],
                'availability_status' => 'Available',
                'is_featured' => rand(0, 1) ? true : false,
                'is_hot' => rand(0, 1) ? true : false,
                'is_active' => true,
                'description' => "Luxurious $bedrooms BHK $type in prime $location location with modern amenities and excellent connectivity.",
                'views_count' => rand(50, 500),
            ]);
        }

        // 5. CREATE LEADS
        $leadNames = [
            ['name' => 'Rahul Verma', 'mobile' => '9988776655', 'email' => 'rahul.v@email.com'],
            ['name' => 'Anjali Mehta', 'mobile' => '9988776656', 'email' => 'anjali.m@email.com'],
            ['name' => 'Sanjay Gupta', 'mobile' => '9988776657', 'email' => 'sanjay.g@email.com'],
            ['name' => 'Pooja Nair', 'mobile' => '9988776658', 'email' => 'pooja.n@email.com'],
            ['name' => 'Karan Malhotra', 'mobile' => '9988776659', 'email' => 'karan.m@email.com'],
            ['name' => 'Divya Shah', 'mobile' => '9988776660', 'email' => 'divya.s@email.com'],
            ['name' => 'Nikhil Joshi', 'mobile' => '9988776661', 'email' => 'nikhil.j@email.com'],
            ['name' => 'Isha Kapoor', 'mobile' => '9988776662', 'email' => 'isha.k@email.com'],
            ['name' => 'Arjun Rao', 'mobile' => '9988776663', 'email' => 'arjun.r@email.com'],
            ['name' => 'Nisha Desai', 'mobile' => '9988776664', 'email' => 'nisha.d@email.com'],
        ];

        $websiteSource = LeadSource::where('slug', 'website')->first();
        $referralSource = LeadSource::where('slug', 'referral')->first();
        $newStatus = LeadStatus::where('slug', 'new')->first();
        $contactedStatus = LeadStatus::where('slug', 'contacted')->first();

        $leadModels = [];
        foreach ($leadNames as $index => $leadData) {
            $employee = $employeeModels[array_rand($employeeModels)];
            $agent = rand(0, 1) ? $agentModels[array_rand($agentModels)] : null;

            $leadModels[] = Lead::create([
                'full_name' => $leadData['name'],
                'mobile' => $leadData['mobile'],
                'email' => $leadData['email'],
                'budget_min' => rand(3000000, 5000000),
                'budget_max' => rand(6000000, 10000000),
                'preferred_locations' => [$locations[array_rand($locations)]],
                'property_types' => [['Flat', 'Villa'][rand(0, 1)]],
                'purchase_intent' => ['Buy', 'Rent', 'Invest'][rand(0, 2)],
                'lead_source_id' => $agent ? $referralSource->id : $websiteSource->id,
                'lead_status_id' => $index < 5 ? $contactedStatus->id : $newStatus->id,
                'assigned_to' => $employee->id,
                'agent_id' => $agent?->id,
                'priority' => ['Hot', 'Warm', 'Cold'][rand(0, 2)],
                'notes' => 'Looking for property in ' . $locations[array_rand($locations)],
                'first_contact_at' => now()->subDays(rand(1, 30)),
                'last_contact_at' => now()->subDays(rand(0, 7)),
            ]);
        }

        // 6. CREATE OPPORTUNITIES
        $stages = OpportunityStage::all();
        $opportunityModels = [];

        for ($i = 0; $i < 8; $i++) {
            $lead = $leadModels[$i];
            $property = $properties[array_rand($properties)];
            $stage = $stages[rand(0, min(5, $stages->count() - 1))];

            $opportunityModels[] = Opportunity::create([
                'lead_id' => $lead->id,
                'assigned_to' => $lead->assigned_to,
                'agent_id' => $lead->agent_id,
                'opportunity_stage_id' => $stage->id,
                'title' => $lead->full_name . ' - ' . $property->name,
                'description' => 'Opportunity for ' . $property->property_type . ' in ' . $property->location,
                'expected_value' => $property->price_min,
                'probability' => $stage->probability ?? 50,
                'expected_close_date' => now()->addMonths(rand(1, 3)),
                'close_status' => 'Open',
            ]);

            // Attach property
            $opportunityModels[$i]->properties()->attach($property->id, [
                'is_shortlisted' => true,
                'notes' => 'Customer showed interest',
            ]);
        }

        // 7. CREATE BOOKINGS (3 bookings - different stages)
        $bookingData = [
            [
                'opportunity' => $opportunityModels[0],
                'stage' => 'Token Confirmed',
                'property_value' => 8500000,
                'token_amount' => 100000,
                'token_date' => now()->subDays(5),
            ],
            [
                'opportunity' => $opportunityModels[1],
                'stage' => 'Agreement Signed',
                'property_value' => 12000000,
                'token_amount' => 200000,
                'token_date' => now()->subDays(45),
                'booking_amount' => 1200000,
                'booking_date' => now()->subDays(40),
                'agreement_value' => 12000000,
                'agreement_date' => now()->subDays(10),
                'agreement_number' => 'AGR-2026-001',
            ],
            [
                'opportunity' => $opportunityModels[2],
                'stage' => 'Registration Done',
                'property_value' => 9500000,
                'token_amount' => 150000,
                'token_date' => now()->subDays(90),
                'booking_amount' => 950000,
                'booking_date' => now()->subDays(85),
                'agreement_value' => 9500000,
                'agreement_date' => now()->subDays(60),
                'agreement_number' => 'AGR-2026-002',
                'registration_date' => now()->subDays(5),
                'registration_number' => 'REG-2026-001',
            ],
        ];

        foreach ($bookingData as $data) {
            $opp = $data['opportunity'];
            Booking::create([
                'opportunity_id' => $opp->id,
                'property_id' => $opp->properties->first()->id,
                'customer_lead_id' => $opp->lead_id,
                'agent_id' => $opp->agent_id,
                'employee_id' => $opp->assigned_to,
                'booking_stage' => $data['stage'],
                'property_value' => $data['property_value'],
                'token_amount' => $data['token_amount'] ?? null,
                'token_date' => $data['token_date'] ?? null,
                'booking_amount' => $data['booking_amount'] ?? null,
                'booking_date' => $data['booking_date'] ?? null,
                'agreement_value' => $data['agreement_value'] ?? null,
                'agreement_date' => $data['agreement_date'] ?? null,
                'agreement_number' => $data['agreement_number'] ?? null,
                'registration_date' => $data['registration_date'] ?? null,
                'registration_number' => $data['registration_number'] ?? null,
                'commission_status' => $data['stage'] === 'Registration Done' ? 'Approved' : 'Pending',
            ]);
        }

        echo "\n✅ Seed data created successfully!\n";
        echo "📊 Summary:\n";
        echo "   - Users: " . User::count() . "\n";
        echo "   - External Agents: " . Agent::where('agent_type', 'External')->count() . "\n";
        echo "   - Builders: " . Builder::count() . "\n";
        echo "   - Properties: " . Property::count() . "\n";
        echo "   - Leads: " . Lead::count() . "\n";
        echo "   - Opportunities: " . Opportunity::count() . "\n";
        echo "   - Bookings: " . Booking::count() . "\n";
        echo "\n🔑 Login Credentials:\n";
        echo "   Admin: admin@ansrealty.com / password\n";
        echo "   Manager: rajesh@ansrealty.com / password\n";
        echo "   Employee: priya@ansrealty.com / password\n";
    }
}
