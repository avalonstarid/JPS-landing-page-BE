<?php

namespace App\Exports\UserManagement;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\QueryBuilder\QueryBuilder;

class UserExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
	use Exportable;

	protected $users;

	public function __construct($query)
	{
		$this->users = $query;
	}

	public function query(): QueryBuilder
	{
		return $this->users;
	}

	public function headings(): array
	{
		return [
			'Nama Lengkap',
			'Email',
			'Is Active',
			'Created At',
		];
	}

	/**
	 * @param $row
	 *
	 * @return array
	 */
	public function map($row): array
	{
		return [
			$row->name,
			$row->email,
			$row->active,
			$row->created_at,
		];
	}
}
