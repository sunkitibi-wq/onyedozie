<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AgentLocation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GeographicSeeder::class,
            RoleSeeder::class,
        ]);

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@onyendozi.org',
            'phone' => '08030000000',
            'status' => 'active',
            'phone_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

        $candidate = User::create([
            'name' => 'Hon. Dozie Nwankwo',
            'email' => 'candidate@onyendozi.org',
            'phone' => '08031111111',
            'status' => 'active',
            'phone_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
        $candidate->assignRole('Candidate Dashboard');

        // Volunteer 1 (Online)
        $volunteer1 = User::create([
            'name' => 'John Volunteer',
            'phone' => '08032222222',
            'status' => 'active',
            'phone_verified_at' => now(),
            'password' => bcrypt('password'),
            'lga_id' => 1,
            'ward_id' => 1,
            'polling_unit_id' => 1,
            'occupation' => 'Trader',
        ]);
        $volunteer1->assignRole('Volunteer');
        AgentLocation::create([
            'user_id' => $volunteer1->id,
            'lat' => 6.2230,
            'lng' => 7.0700,
            'accuracy' => 12.50,
            'battery_level' => 85,
            'recorded_at' => now(),
        ]);

        // LGA Coordinator (Online - 5 mins ago)
        $lgaCoord = User::create([
            'name' => 'Chidi Coordinator',
            'phone' => '08033333333',
            'status' => 'active',
            'phone_verified_at' => now(),
            'password' => bcrypt('password'),
            'lga_id' => 2,
            'occupation' => 'Civil Servant',
        ]);
        $lgaCoord->assignRole('LGA Coordinator');
        AgentLocation::create([
            'user_id' => $lgaCoord->id,
            'lat' => 6.1330,
            'lng' => 7.1230,
            'accuracy' => 15.00,
            'battery_level' => 74,
            'recorded_at' => now()->subMinutes(5),
        ]);

        // Volunteer 2 (Offline - 3 hours ago)
        $volunteer2 = User::create([
            'name' => 'Amara Volunteer',
            'phone' => '08034444444',
            'status' => 'active',
            'phone_verified_at' => now(),
            'password' => bcrypt('password'),
            'lga_id' => 1,
            'ward_id' => 1,
            'occupation' => 'Student',
        ]);
        $volunteer2->assignRole('Volunteer');
        AgentLocation::create([
            'user_id' => $volunteer2->id,
            'lat' => 6.2550,
            'lng' => 7.0420,
            'accuracy' => 25.00,
            'battery_level' => 42,
            'recorded_at' => now()->subHours(3),
        ]);

        // Ward Coordinator (Online)
        $wardCoord = User::create([
            'name' => 'Bello Officer',
            'phone' => '08035555555',
            'status' => 'active',
            'phone_verified_at' => now(),
            'password' => bcrypt('password'),
            'lga_id' => 3,
            'occupation' => 'Engineer',
        ]);
        $wardCoord->assignRole('Ward Coordinator');
        AgentLocation::create([
            'user_id' => $wardCoord->id,
            'lat' => 6.0120,
            'lng' => 7.0220,
            'accuracy' => 8.00,
            'battery_level' => 98,
            'recorded_at' => now()->subMinutes(2),
        ]);

        // Seed Tasks
        $task1 = \App\Models\Task::create([
            'title' => 'Distribute Campaign Flyers',
            'description' => 'Distribute Hon. Dozie Nwankwo\'s flyers around the main market square.',
            'deadline' => now()->addDays(2),
            'status' => 'pending',
            'assigned_to_role' => 'Volunteer',
            'lga_id' => 1,
            'created_by' => $superAdmin->id,
        ]);

        $task2 = \App\Models\Task::create([
            'title' => 'Verify Polling Unit 001 Boundaries',
            'description' => 'Physically walk the boundary of Polling Unit 001 and ensure the landmark signs are visible.',
            'deadline' => now()->addDay(),
            'status' => 'pending',
            'assigned_user_id' => $volunteer1->id,
            'created_by' => $superAdmin->id,
        ]);

        $task3 = \App\Models\Task::create([
            'title' => 'Coordinate LGA Meeting Prep',
            'description' => 'Arrange the chairs and sound system for the LGA coordinator meeting.',
            'deadline' => now()->subDay(),
            'status' => 'verified',
            'assigned_to_role' => 'LGA Coordinator',
            'lga_id' => 2,
            'created_by' => $superAdmin->id,
            'verified_by' => $superAdmin->id,
        ]);

        \App\Models\TaskCompletion::create([
            'task_id' => $task3->id,
            'user_id' => $lgaCoord->id,
            'notes' => 'Everything is prepared and set up at the venue.',
            'completed_at' => now()->subHours(12),
            'verified_at' => now()->subHours(10),
        ]);

        // Seed Door Knocks
        \App\Models\DoorKnock::create([
            'user_id' => $volunteer1->id,
            'lat' => 6.2235,
            'lng' => 7.0705,
            'address_description' => 'House 4, Market Road, Awka',
            'voter_name' => 'Emeka Okafor',
            'outcome' => 'supporter',
            'notes' => 'Very enthusiastic about our plans. Promised to bring family.',
            'visited_at' => now()->subHours(2),
        ]);

        \App\Models\DoorKnock::create([
            'user_id' => $volunteer1->id,
            'lat' => 6.2240,
            'lng' => 7.0710,
            'address_description' => 'Flat B, 12 Zik Avenue, Awka',
            'voter_name' => 'Ngozi Okoye',
            'outcome' => 'undecided',
            'notes' => 'Undecided. Concerned about local road conditions.',
            'visited_at' => now()->subHours(3),
        ]);

        \App\Models\DoorKnock::create([
            'user_id' => $volunteer2->id,
            'lat' => 6.2560,
            'lng' => 7.0430,
            'address_description' => 'Block 3, University Gate, Awka',
            'voter_name' => 'Chioma Nze',
            'outcome' => 'hostile',
            'notes' => 'Supports opposition. Refused flyers.',
            'visited_at' => now()->subHours(1),
        ]);

        \App\Models\DoorKnock::create([
            'user_id' => $volunteer2->id,
            'lat' => 6.2545,
            'lng' => 7.0415,
            'address_description' => 'Green Bungalow opposite Chisco park',
            'voter_name' => 'Kabiru Ibrahim',
            'outcome' => 'not_home',
            'notes' => 'No one answered. Will retry tomorrow.',
            'visited_at' => now()->subMinutes(30),
        ]);

        // Seed News
        \App\Models\News::create([
            'title' => 'Hon. Dozie Nwankwo Leads Massive Campaign Rally in Awka North',
            'body' => 'Thousands of supporters turned out today to welcome Hon. Dozie Nwankwo. He outlined key initiatives for infrastructure, education, and youth empowerment in the district.',
            'category' => 'Rally',
            'is_breaking' => true,
            'published_at' => now(),
        ]);

        \App\Models\News::create([
            'title' => 'Ward Coordinators Strategy Session Scheduled',
            'body' => 'All Ward Coordinators are requested to attend the upcoming strategy session this Friday. We will align on door-knocking operations and campaign material distribution.',
            'category' => 'Strategy',
            'is_breaking' => false,
            'published_at' => now()->subHours(6),
        ]);

        // Seed Events
        \App\Models\Event::create([
            'title' => 'Town Hall Meeting - Awka South',
            'description' => 'Join Hon. Dozie Nwankwo and community leaders to discuss key developmental programs for the Anambra Central Senatorial District. General mobilization session starts at 10:00 AM sharp.',
            'date' => now()->addDay()->setHour(10)->setMinute(0)->setSecond(0),
            'venue' => 'Civic Center, Main Hall',
            'lga_id' => 1,
            'ward_id' => 1,
            'created_by' => $superAdmin->id,
        ]);

        \App\Models\Event::create([
            'title' => 'Anambra Central Youth Summit',
            'description' => 'A gathering of young minds to discuss political participation, job creation, and digital skill acquisition programs under the campaign manifesto.',
            'date' => now()->addDays(3)->setHour(14)->setMinute(0)->setSecond(0),
            'venue' => 'Grand Arena, Awka',
            'lga_id' => 1,
            'ward_id' => 2,
            'created_by' => $superAdmin->id,
        ]);

        // Seed Media Library
        \App\Models\MediaLibrary::create([
            'name' => 'Official Campaign Anthem',
            'category' => 'jingles',
            'path' => '/audio/anthem.mp3',
            'mime_type' => 'audio/mpeg',
            'size' => 2450000,
            'uploaded_by' => $superAdmin->id,
        ]);

        \App\Models\MediaLibrary::create([
            'name' => 'Youth Outreach Flyer',
            'category' => 'flyers',
            'path' => '/flyers/youth_outreach.png',
            'mime_type' => 'image/png',
            'size' => 102400,
            'uploaded_by' => $superAdmin->id,
        ]);

        \App\Models\MediaLibrary::create([
            'name' => 'Manifesto Highlight Flyer',
            'category' => 'flyers',
            'path' => '/flyers/manifesto.png',
            'mime_type' => 'image/png',
            'size' => 152400,
            'uploaded_by' => $superAdmin->id,
        ]);

        \App\Models\MediaLibrary::create([
            'name' => 'Campaign Manifesto PDF',
            'category' => 'documents',
            'path' => '/docs/manifesto.pdf',
            'mime_type' => 'application/pdf',
            'size' => 4500000,
            'uploaded_by' => $superAdmin->id,
        ]);
    }
}
