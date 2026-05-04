<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make the specific user an admin
        $user = User::where('email', 'amineelhailaa@gmail.com')->first();
        if ($user) {
            Admin::firstOrCreate(['user_id' => $user->id]);
        }

        // Randomly pick a few other users to be admins
        $users = User::where('email', '!=', 'amineelhailaa@gmail.com')->inRandomOrder()->limit(2)->get();
        foreach ($users as $u) {
            Admin::firstOrCreate(['user_id' => $u->id]);
        }
    }
}
