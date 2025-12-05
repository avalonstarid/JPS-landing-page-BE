<?php

namespace App\Providers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Pulse\Facades\Pulse;

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
		// Implicitly grant "Super Admin" role all permissions
		// This works in the app by using gate-related functions like auth()->user->can() and @can()
		Gate::before(function ($user, $ability) {
			return $user->hasRole('super-admin') ? true : null;
		});
		Gate::define('viewPulse', function (User $user) {
			return $user->hasRole('super-admin');
		});
		Gate::define('viewLogViewer', function (User $user) {
			return $user->hasRole('super-admin');
		});

		Pulse::user(fn($user) => [
			'name' => $user->name,
			'extra' => $user->email,
			'avatar' => $user->avatar,
		]);

//		RateLimiter::for('api', function (Request $request) {
//			return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
//		});

		ResetPassword::createUrlUsing(function ($user, string $token) {
			return config('app.frontend_url') . '/auth/reset-password?token=' . $token . '&email=' . $user->email;
		});

		Storage::disk('download')->buildTemporaryUrlsUsing(function ($path, $expiration, $options) {
			return URL::temporarySignedRoute(
				'files.download',
				$expiration,
				array_merge($options, ['path' => $path])
			);
		});

		VerifyEmail::createUrlUsing(function ($notifiable) {
			$params = [
				"expires" => Carbon::now()
					->addMinutes(60)
					->getTimestamp(),
				"id" => $notifiable->getKey(),
				"hash" => sha1($notifiable->getEmailForVerification()),
			];

			ksort($params);

			// then create API url for verification. my API have `/api` prefix,
			// so I don't want to show that url to users
			$url = URL::route("verification.verify", $params);

			// get APP_KEY from config and create signature
			$key = config("app.key");
			$signature = hash_hmac("sha256", $url, $key);

			// generate url for yous SPA page to send it to user\
			return config('app.frontend_url') .
				"/auth/verify-email?id=" .
				$params["id"] .
				"&hash=" .
				$params["hash"] .
				"&expires=" .
				$params["expires"] .
				"&signature=" .
				$signature;
		});
	}
}
