<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Transaction;

class TransactionObserver
{
	/**
	 * Handle the Transaction "created" event.
	 */
	public function created(Transaction $transaction): void
	{
		$this->processTransaction(
			$transaction->type,
			$transaction->amount,
			$transaction->from_account_id,
			$transaction->to_account_id,
			isReversal: false,
		);
	}

	/**
	 * Handle the Transaction "updated" event.
	 */
	public function updated(Transaction $transaction): void
	{
		// 1. REVERT (Batalkan) efek data LAMA (Original)
		// Kita ambil raw attributes lama
		$oldData = $transaction->getOriginal();

		$this->processTransaction(
			$oldData['type'],
			$oldData['amount'],
			$oldData['from_account_id'],
			$oldData['to_account_id'] ?? null,
			isReversal: true, // Penting: ini pembatalan
		);

		// 2. APPLY (Terapkan) efek data BARU
		$this->processTransaction(
			$transaction->type,
			$transaction->amount,
			$transaction->from_account_id,
			$transaction->to_account_id,
			isReversal: false,
		);
	}

	/**
	 * Handle the Transaction "deleted" event.
	 */
	public function deleted(Transaction $transaction): void
	{
		$this->processTransaction(
			$transaction->type,
			$transaction->amount,
			$transaction->from_account_id,
			$transaction->to_account_id,
			isReversal: true,
		);
	}

	/**
	 * Handle the Transaction "restored" event.
	 */
	public function restored(Transaction $transaction): void
	{
		//
	}

	/**
	 * Handle the Transaction "force deleted" event.
	 */
	public function forceDeleted(Transaction $transaction): void
	{
		//
	}

	/**
	 * Core Logic untuk mengatur saldo
	 */
	private function processTransaction($type, $amount, $accountId, $destAccountId, $isReversal): void
	{
		// Load Akun Utama
		$account = Account::find($accountId);
		// Load Akun Tujuan (jika ada)
		$destAccount = $destAccountId ? Account::find($destAccountId) : null;

		if (!$account) return; // Safety check

		// Tentukan multiplier.
		// Jika Reversal (pembatalan/delete/update-old), logikanya dibalik.
		// Normal: 1, Reversal: -1
		$m = $isReversal ? -1 : 1;
		$realAmount = $amount * $m;

		if ($type === 'I') {
			// Logika Pemasukan
			$this->handleIncomingMoney($account, $realAmount);
		} else {
			// Logika Pengeluaran & Transfer (Sumber Dana)
			$this->handleOutgoingMoney($account, $realAmount);
		}

		if ($destAccount) {
			// Akun tujuan selalu dalam posisi "Menerima Uang"
			$this->handleIncomingMoney($destAccount, $realAmount);
		}
	}

	/**
	 * Helper: Menangani akun yang MENERIMA uang
	 * (Income, atau Akun Tujuan Transfer/Pembayaran)
	 */
	private function handleIncomingMoney(Account $account, $amount): void
	{
		if ($account->type_id === 'CC') {
			// CC Menerima Uang = Bayar Tagihan = Hutang Berkurang
			$account->decrement('saldo', $amount);
		} else {
			// Bank Menerima Uang = Saldo Bertambah
			$account->increment('saldo', $amount);
		}
	}

	/**
	 * Helper: Menangani akun yang MENGELUARKAN uang
	 * (Expense, atau Akun Sumber Transfer)
	 */
	private function handleOutgoingMoney(Account $account, $amount): void
	{
		if ($account->type_id === 'CC') {
			// CC Dipakai Belanja = Hutang/Pemakaian Bertambah
			$account->increment('saldo', $amount);
		} else {
			// Bank Dipakai Belanja = Saldo Berkurang
			$account->decrement('saldo', $amount);
		}
	}
}
