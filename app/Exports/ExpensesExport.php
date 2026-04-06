<?php

namespace App\Exports;

use App\Models\Group;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpensesExport implements FromCollection, WithHeadings
{
    public function __construct(public Group $group) {}

    public function headings(): array
    {
        return ['Date', 'Description', 'Amount', 'Category', 'Paid By'];
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->group->expenses()->with('category',
            'payer.user')->get()
            ->map(fn ($e) => [
                $e->date,
                $e->title,
                $e->amount,
                $e->category->name ?? '',
                $e->payer->user->name ?? '',
            ]);
    }
}
