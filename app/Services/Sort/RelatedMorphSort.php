<?php

namespace App\Services\Sort;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\QueryBuilder\Sorts\Sort;

class RelatedMorphSort implements Sort
{
	public function __invoke(Builder $query, bool $descending, string $property): void
	{
		[$relationName, $columnName] = explode('.', $property);

		/** @var MorphToMany $relation */
		$relation = $query->getRelation($relationName);

		$subquery = $relation
			->getQuery()
			->select($columnName)
			->whereColumn($relation->getQualifiedForeignPivotKeyName(), $relation->getQualifiedParentKeyName());

		$query->orderBy($subquery, $descending ? 'asc' : 'desc');
	}
}
