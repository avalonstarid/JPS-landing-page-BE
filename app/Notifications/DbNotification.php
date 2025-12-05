<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/*
 * This is a custom notification class that extends the default Laravel notification class.
 * It is used to send notifications to the database and broadcast them to the front-end.
 *
 * Usage:
 * $user = User::find(1);
	$user->notify(new DbNotification('information', [
		'title' => 'Test Notification',
		'message' => 'This is a test notification. ' . now(),
		'url' => '/settings/menu',
	]));
 */

class DbNotification extends Notification implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(public string $type, public array $data)
	{
	}

	/**
	 * Get the notification's database type.
	 *
	 * @param object $notifiable
	 *
	 * @return string
	 */
	public function databaseType(object $notifiable): string
	{
		return $this->type;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via(object $notifiable): array
	{
		return ['broadcast', 'database'];
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(object $notifiable): array
	{
		return $this->data;
	}

	/**
	 * Get the broadcastable representation of the notification.
	 */
	public function toBroadcast(object $notifiable): BroadcastMessage
	{
		return new BroadcastMessage($this->data);
	}

	/**
	 * The event's broadcast name.
	 */
	public function broadcastAs(): string
	{
		return 'notifications.created';
	}
}
