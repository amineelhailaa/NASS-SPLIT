<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::factory(20)->create();
        User::firstOrCreate(
            ['email' => 'amineelhailaa@gmail.com'],
            [
                'name' => 'amineelhailaa',
                'email_verified_at' => now(),
                'password' => 'asdfasdfasdf',
                'remember_token' => 'asdfasdfasdfsadfsadf'
            ]
        );
    }
}
