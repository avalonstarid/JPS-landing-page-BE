<?php

if (!function_exists('flat_ancestors')) {
	function flat_ancestors($model): array
	{
		$result = [];
		if ($model->parent) {
			$result[] = $model->parent;
			$result = array_merge($result, flat_ancestors($model->parent));
		}
		return $result;
	}
}

if (!function_exists('flat_descendants')) {
	function flat_descendants($model): array
	{
		$result = [];
		foreach ($model->children as $child) {
			$result[] = $child;
			if ($child->children) {
				$result = array_merge($result, flat_descendants($child));
			}
		}
		return $result;
	}
}