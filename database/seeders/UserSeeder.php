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
        User::create(['name' => 'amineelhailaa',
            'email' => 'amineelhailaa@gmail.com',
            'email_verified_at' => now(),
            'password' => 'asdfasdfasdf',
            'remember_token' => 'asdfasdfasdfsadfsadf']);
    }
}
