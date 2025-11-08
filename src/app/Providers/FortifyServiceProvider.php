<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Controllers\RegisteredUserController as FortifyRegisteredUserController;
use App\Http\Controllers\RegisteredUserController;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        Fortify::authenticateUsing(function (Request $request) {
            $guard = $request->input('guard', 'web'); // hiddenフィールドなどで渡す

            $user = Auth::guard($guard)->getProvider()->retrieveByCredentials([
                Fortify::username() => $request->{Fortify::username()},
            ]);

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::guard($guard)->login($user); // ここで guard にログインを反映
                return $user;
            }

            return null;
        });

        app()->bind(FortifyLoginRequest::class, LoginRequest::class);
        app()->bind(FortifyRegisteredUserController::class, RegisteredUserController::class);

    }
}
