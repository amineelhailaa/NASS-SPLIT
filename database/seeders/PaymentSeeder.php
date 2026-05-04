<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = Group::with('members')->get();

        foreach ($groups as $group) {
            $members = $group->members;
            if ($members->count() < 2) {
                continue;
            }

            // Create 1-3 payments per group
            for ($i = 0; $i < rand(1, 3); $i++) {
                $creditor = $members->random();
                $debtor = $members->where('id', '!=', $creditor->id)->random();

                Payment::create([
                    'creditor_id' => $creditor->id,
                    'debtor_id' => $debtor->id,
                    'amount' => fake()->randomFloat(2, 5, 100),
                    'status' => fake()->randomElement(['pending', 'paid']),
                ]);
            }
        }
    }
}
