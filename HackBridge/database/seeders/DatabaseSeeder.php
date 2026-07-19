<?php
// FILE: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;
use App\Models\Hackathon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Seed Skills ──
        $skills = [
            ['name' => 'Python',        'category' => 'Programming'],
            ['name' => 'JavaScript',    'category' => 'Programming'],
            ['name' => 'PHP / Laravel', 'category' => 'Programming'],
            ['name' => 'React',         'category' => 'Programming'],
            ['name' => 'Flutter',       'category' => 'Programming'],
            ['name' => 'C / C++',       'category' => 'Programming'],
            ['name' => 'Java',          'category' => 'Programming'],
            ['name' => 'Machine Learning','category'=> 'Programming'],
            ['name' => 'Deep Learning', 'category' => 'Programming'],
            ['name' => 'Arduino',       'category' => 'Hardware'],
            ['name' => 'ESP32 / IoT',   'category' => 'Hardware'],
            ['name' => 'PCB Design',    'category' => 'Hardware'],
            ['name' => 'Raspberry Pi',  'category' => 'Hardware'],
            ['name' => 'UI/UX Design',  'category' => 'Design'],
            ['name' => 'Figma',         'category' => 'Design'],
            ['name' => 'Data Analysis', 'category' => 'Research'],
            ['name' => 'Project Management','category'=>'Management'],
            ['name' => 'Git / GitHub',  'category' => 'Programming'],
            ['name' => 'MySQL',         'category' => 'Programming'],
            ['name' => 'TensorFlow',    'category' => 'Programming'],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(['name' => $skill['name']], $skill);
        }

        // ── Seed Hackathons ──
        $hackathons = [
            // Intra-University
            [
                'title'               => 'KUET CSE Fest 2025',
                'organizer'           => 'Dept of CSE, KUET',
                'description'         => 'Annual tech fest of CSE department, KUET.',
                'category'            => 'AI / Web',
                'type'                => 'Team',
                'mode'                => 'Offline',
                'deadline'            => '2025-07-18 23:59:59',
                'prize'               => '৳50,000',
                'banner_emoji'        => '🖥️',
                'banner_color'        => 'linear-gradient(135deg,#1e3a5f,#0f2542)',
                'is_intra_university' => true,
                'is_featured'         => true,
            ],
            [
                'title'               => 'EEE Innovation Cup',
                'organizer'           => 'Dept of EEE, KUET',
                'description'         => 'Hardware and innovation competition.',
                'category'            => 'Hardware',
                'type'                => 'Team',
                'mode'                => 'Offline',
                'deadline'            => '2025-08-02 23:59:59',
                'prize'               => '৳30,000',
                'banner_emoji'        => '⚡',
                'banner_color'        => 'linear-gradient(135deg,#2d1b5e,#1a0f3d)',
                'is_intra_university' => true,
                'is_featured'         => false,
            ],
            [
                'title'               => 'K-MiNDS Datathon',
                'organizer'           => 'K-MiNDS Club, KUET',
                'description'         => 'Data science and ML competition.',
                'category'            => 'Data Science',
                'type'                => 'Both',
                'mode'                => 'Online',
                'deadline'            => '2025-08-15 23:59:59',
                'prize'               => '৳20,000',
                'banner_emoji'        => '🤖',
                'banner_color'        => 'linear-gradient(135deg,#0f3d2d,#062018)',
                'is_intra_university' => true,
                'is_featured'         => false,
            ],
            // Inter-University / International
            [
                'title'               => 'NASA Space Apps 2025',
                'organizer'           => 'NASA · International',
                'description'         => 'Global hackathon challenging teams to solve space-related problems.',
                'category'            => 'Open Theme',
                'type'                => 'Team',
                'mode'                => 'Hybrid',
                'deadline'            => '2025-10-04 23:59:59',
                'prize'               => 'Global Recognition',
                'banner_emoji'        => '🚀',
                'banner_color'        => 'linear-gradient(135deg,#1a3a6b,#0c2044)',
                'is_intra_university' => false,
                'is_featured'         => true,
            ],
            [
                'title'               => 'ICPC Asia Dhaka Regional',
                'organizer'           => 'ICPC · National',
                'description'         => 'Prestigious programming contest for university students.',
                'category'            => 'CP / Algorithms',
                'type'                => 'Team',
                'mode'                => 'Offline',
                'deadline'            => '2025-09-20 23:59:59',
                'prize'               => 'World Finals Qualification',
                'banner_emoji'        => '🧠',
                'banner_color'        => 'linear-gradient(135deg,#3d2000,#241300)',
                'is_intra_university' => false,
                'is_featured'         => true,
            ],
            [
                'title'               => 'Google Solution Challenge',
                'organizer'           => 'Google · International',
                'description'         => 'Build solutions for UN sustainable development goals.',
                'category'            => 'SDG / App Dev',
                'type'                => 'Team',
                'mode'                => 'Online',
                'deadline'            => '2026-03-01 23:59:59',
                'prize'               => '$3,000 + Mentorship',
                'banner_emoji'        => '🌍',
                'banner_color'        => 'linear-gradient(135deg,#0d3d1a,#061f0c)',
                'is_intra_university' => false,
                'is_featured'         => false,
            ],
        ];

        foreach ($hackathons as $h) {
            Hackathon::firstOrCreate(['title' => $h['title']], $h);
        }

        // ── Demo User ──
        User::firstOrCreate(['email' => 'demo@kuet.ac.bd'], [
            'name'         => 'Zaina Rahman',
            'email'        => 'demo@kuet.ac.bd',
            'password'     => Hash::make('password'),
            'department'   => 'CSE',
            'university'   => 'KUET',
            'year'         => 3,
            'bio'          => '3rd year CSE student at KUET. Passionate about ML and IoT.',
            'availability' => 'open',
        ]);
    }
}