<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\OneTimePasswords\Notifications\OneTimePasswordNotification;

class SendOtpNotification extends OneTimePasswordNotification implements ShouldQueue
{
	use Queueable;
}
