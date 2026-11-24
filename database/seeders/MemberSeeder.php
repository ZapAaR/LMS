<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            Member::firstOrCreate(
                [
                    'member_code' => 'AGT-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                ],
                [
                    'name' => fake()->name(),
                    'email' => 'member' . $i . '@gmail.com',
                    'phone' => '08' . fake()->numerify('##########'),
                    'address' => fake()->address(),
                    'status' => fake()->randomElement([
                        'active',
                        'inactive',
                    ]),
                ]
            );
        }
    }
}
