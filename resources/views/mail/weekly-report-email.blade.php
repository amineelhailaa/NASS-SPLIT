<x-mail::message>
# Weekly Financial Report

Hi **{{ $report['user_name'] }}**, here's your summary for the past 7 days.

---

@foreach ($report['groups'] as $group)
## {{ $group['group_name'] }}

| | |
|---|---|
| **Your Balance** | {{ $group['balance'] >= 0 ? '+' : '' }}{{ number_format($group['balance'], 2) }} |
| **New Expenses** | {{ $group['new_expenses_count'] }} |
| **Group Spending** | {{ number_format($group['total_spending_this_week'], 2) }} |

@endforeach

---

**You settled this week:** {{ number_format($report['total_settled_this_week'], 2) }}

---

Thanks,
**{{ config('app.name') }}**
</x-mail::message>