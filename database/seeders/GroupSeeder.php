<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        if ($users->count() < 2) {
            return;
        }

        for ($i = 0; $i < 5; $i++) {
            $group = Group::create([
                'name' => fake()->company().' Trip',
                'description' => fake()->sentence(),
                'status' => 'active',
                'settle' => 1,
            ]);

            // Add an owner
            $owner = $users->random();
            Membership::create([
                'group_id' => $group->id,
                'user_id' => $owner->id,
                'role' => 'owner',
                'status' => 'active',
            ]);

            // Add random members
            $members = $users->where('id', '!=', $owner->id)->random(rand(2, 4));
            foreach ($members as $member) {
                Membership::create([
                    'group_id' => $group->id,
                    'user_id' => $member->id,
                    'role' => 'member',
                    'status' => 'active',
                ]);
            }
        }
    }
}
