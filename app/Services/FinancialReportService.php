<?php

namespace App\Services;
use App\Models\Membership;
use App\Models\User;
use Carbon\Carbon;

class FinancialReportService
{
    public function __construct(private SettlementService $settlementService) {}

    /**
     * Generate the full weekly report data for a user.
     */
    public function generateReport(User $user): array
    {
        $memberships = $user->memberships()->where('status', 'active')->with('group')->get();

        $groups = [];
        $totalSettled = 0;

        foreach ($memberships as $membership) {
            $stat = $this->ExpenseStat($membership);
            $groups[] = [
                'group_name' => $membership->group->name,
                'balance' => $this->settlementService->memberBalance($membership),
                'new_expenses_count' => $stat->newExpensesCount ?? 0,
                'total_spending_this_week' => $stat->totalSpending ?? 0,
            ];

            $totalSettled += $this->settledThisWeek($membership);
        }

        return [
            'user_name' => $user->name,
            'groups' => $groups,
            'total_settled_this_week' => $totalSettled,
        ];
    }

    private function ExpenseStat(Membership $membership)
    {
        return $membership->group->expenses()
            ->where('date', '>=', Carbon::now()->subWeek())
            ->selectRaw('SUM(amount) as totalSpending, COUNT(*) as newExpensesCount')
            ->first();
    }


    private function settledThisWeek($membership): float
    {
        return $membership->paymentsAsDebtor()
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->sum('amount');
    }
}
