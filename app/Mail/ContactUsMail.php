<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	public $data;

	/**
	 * Create a new message instance.
	 *
	 * @param array $data Data dari form request
	 */
	public function __construct(array $data)
	{
		// Pastikan array ini memiliki key: name, email, phone, location, message
		$this->data = $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('Pesan Baru dari Formulir Landing Page')
			->from('no-reply@jpsejahtera.co.id', 'Sistem Landing Page | ' . config('app.name'))
			->replyTo($this->data['email'], $this->data['name'])
			->view('emails.landing-page-contact-form');
	}
}
