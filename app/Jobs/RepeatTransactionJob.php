<?php

namespace App\Jobs;

use App\Models\RepeatTransaction;
use App\Models\Transaction;
use App\Notifications\RepeatTransactionNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RepeatTransactionJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
	public function handle(): void
	{
		// Process YEARLY
		$this->yearly();

		// Process MONTHLY
		$this->monthly();

		// Process DAILY
		$this->daily();
	}

	private function yearly(): void
	{
		$data = RepeatTransaction::with('user')->where('repeat->periode', 'YEARLY')
			->where('repeat->month', Carbon::now()->month)
			->where(function (Builder $query) {
				$query->whereYear('last_execute', '<', Carbon::now()->year)
					->orWhereNull('last_execute');
			})
			->where(function (Builder $query) {
				$query->whereDate('expired_time', '>=', Carbon::today()->toDateString())
					->orWhereNull('expired_time');
			})
			->where(function (Builder $query) {
				$query->where('max_transaction', 0)
					->orWhereColumn('max_transaction', '>', 'current_transaction')
					->orWhereNull('max_transaction');
			})
			->get();

		foreach ($data as $item) {
			$this->process($item);
		}
	}

	private function monthly(): void
	{
		$data = RepeatTransaction::with('user')->where('repeat->periode', 'MONTHLY')
			->where(function (Builder $query) {
				if (Carbon::now()->isLastOfMonth()) {
					$query->where('repeat->date', '>=', Carbon::now()->day);
				} else {
					$query->where('repeat->date', Carbon::now()->day);
				}
			})
			->where(function (Builder $query) {
				$query->whereDate('expired_time', '>=', Carbon::today()->toDateString())
					->orWhereNull('expired_time');
			})
			->where(function (Builder $query) {
				$query->where('max_transaction', 0)
					->orWhereColumn('max_transaction', '>', 'current_transaction')
					->orWhereNull('max_transaction');
			})
			->get();

		foreach ($data as $item) {
			$this->process($item);
		}
	}

	private function daily(): void
	{
		$data = RepeatTransaction::with('user')->where('repeat->periode', 'DAILY')
			->whereJsonContains('repeat->days', Carbon::now()->dayOfWeek)
			->where(function (Builder $query) {
				$query->whereDate('expired_time', '>=', Carbon::today()->toDateString())
					->orWhereNull('expired_time');
			})
			->where(function (Builder $query) {
				$query->where('max_transaction', 0)
					->orWhereColumn('max_transaction', '>', 'current_transaction')
					->orWhereNull('max_transaction');
			})
			->get();

		foreach ($data as $item) {
			$this->process($item);
		}
	}

	private function process(RepeatTransaction $repeatTransaction): void
	{
		$repeatTransaction->last_execute = Carbon::now();
		$repeatTransaction->current_transaction++;
		$repeatTransaction->save();

		Transaction::create([
			'amount' => $repeatTransaction->amount,
			'desc' => $repeatTransaction->desc,
			'tanggal' => Carbon::now(),
			'type' => $repeatTransaction->type,
			'category_id' => $repeatTransaction->category_id,
			'created_by_id' => $repeatTransaction->created_by_id,
			'from_account_id' => $repeatTransaction->from_account_id,
			'to_account_id' => $repeatTransaction->to_account_id,
		]);

		// Send notification to user
		$repeatTransaction->createdBy->notify(new RepeatTransactionNotification($repeatTransaction));
	}
}
