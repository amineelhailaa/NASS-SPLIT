<?php

namespace App\Jobs;

use App\Mail\WeeklyReportEmail;
use App\Models\User;
use App\Services\FinancialReportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendWeeklyReportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(FinancialReportService $reportService): void
    {
        User::whereHas('memberships', fn ($query) => $query->where('status', 'active'))
            ->each(function (User $user) use ($reportService) {
                $report = $reportService->generateReport($user);
                Mail::to($user->email)->send(new WeeklyReportEmail($report));
            });
    }
}
