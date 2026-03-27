<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\User;
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
        //for is image function ( simple asf)
        UploadedFile::macro('isImage',function(){
            return str_starts_with($this->getMimeType(), 'image/');
            //getmime return image/extension , and mine verify if string start with image haha
        });
        Gate::define('owner', function (User $user, Group $group){
            return $group->ownerMembership?->user->id === $user->id || $user->admin()->exists();
        });

        Gate::define('member', function (User $user, Group $group){
            return $group->members()
                    ->where('user_id',$user->id)
                    ->exists()
                || $user->admin()->exists();
        });

    }
}
