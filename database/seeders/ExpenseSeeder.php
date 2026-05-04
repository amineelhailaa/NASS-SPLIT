<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Group;
use App\Models\Split;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = Group::with('members')->get();
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        foreach ($groups as $group) {
            $members = $group->members;
            if ($members->count() < 2) {
                continue;
            }

            // Create 3-5 expenses per group
            for ($i = 0; $i < rand(3, 5); $i++) {
                $payer = $members->random();
                $amount = fake()->randomFloat(2, 10, 500);

                $expense = Expense::create([
                    'group_id' => $group->id,
                    'payer_id' => $payer->id,
                    'category_id' => $categories->random()->id,
                    'title' => fake()->words(3, true),
                    'date' => fake()->date(),
                    'amount' => $amount,
                ]);

                // Split equally among all members
                $splitAmount = round($amount / $members->count(), 2);
                foreach ($members as $member) {
                    Split::create([
                        'expense_id' => $expense->id,
                        'debtor_id' => $member->id,
                        'amount' => $splitAmount,
                    ]);
                }
            }
        }
    }
}
