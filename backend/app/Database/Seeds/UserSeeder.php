<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'      => 'admin',
                'email'         => 'admin@bsu.edu.ph',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name'     => 'System Administrator',
                'role'          => 'Admin',
                'department'    => 'IT Department',
                'bio'           => 'Maintains the Root Crops Research Portal.',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'researcher',
                'email'         => 'researcher@bsu.edu.ph',
                'password_hash' => password_hash('researcher123', PASSWORD_DEFAULT),
                'full_name'     => 'Dr. Juan Dela Cruz',
                'role'          => 'Researcher',
                'department'    => 'Root Crops Institute',
                'bio'           => 'Specialist in sweet potato genetic diversity.',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'student',
                'email'         => 'student@bsu.edu.ph',
                'password_hash' => password_hash('student123', PASSWORD_DEFAULT),
                'full_name'     => 'Maria Clara',
                'role'          => 'User',
                'department'    => 'College of Agriculture',
                'bio'           => 'Undergraduate student researching taro pests.',
                'created_at'    => date('Y-m-d H:i:s'),
            ]
        ];

        // Using Query Builder
        $this->db->table('users')->insertBatch($data);
    }
}