<?php

namespace App\Models;


use App\Notifications\VerifyEmailQueued;
use App\Traits\InteractsWithHashedMedia;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MeShaon\RequestAnalytics\Contracts\CanAccessAnalyticsDashboard;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanAccessAnalyticsDashboard, HasMedia, MustVerifyEmail
{
	/** @use HasFactory<UserFactory> */
	use HasApiTokens,
		HasFactory,
		HasOneTimePasswords,
		HasRoles,
		HasUuids,
		InteractsWithHashedMedia,
		LogsActivity,
		Notifiable;

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var list<string>
	 */
	protected $appends = ['avatar'];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var list<string>
	 */
	protected $guarded = [];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var list<string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * Interact with the user's avatar.
	 *
	 * @return Attribute
	 */
	protected function avatar(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->getFirstMedia('avatar')?->getFullUrl(),
		);
	}

	public function canAccessAnalyticsDashboard(): bool
	{
		return $this->hasRole('super-admin');
	}

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'active' => 'boolean',
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];
	}

	/**
	 * @return LogOptions
	 */
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty()
			->dontSubmitEmptyLogs();
	}

	/**
	 * The channels the user receives notification broadcasts on.
	 */
	public function receivesBroadcastNotificationsOn(): string
	{
		return 'users.' . $this->id;
	}

	/**
	 * @return void
	 */
	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('avatar')->singleFile();
	}

	/**
	 * Send the queued email verification notification.
	 *
	 * @return void
	 */
	public function sendEmailVerificationNotification(): void
	{
		$this->notify(new VerifyEmailQueued);
	}
}
