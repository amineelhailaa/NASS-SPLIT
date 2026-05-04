<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        UploadedFile::macro('isImage', function () {
            return str_starts_with($this->getMimeType(), 'image/');
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // gates:
        Gate::define('owner', function (User $user, Group $group) {
            return $user->groups()->whereKey($group->id)->wherePivot('role', 'owner')->wherePivot('status', 'active')->exists();
        });
        Gate::define('member', function (User $user, Group $group) {
            return $user->groups()->whereKey($group->id)->wherePivot('status', 'active')->exists();
        });

        Gate::define('admin', function (User $user) {
            return $user->admin()->exists();
        });
    }
}
