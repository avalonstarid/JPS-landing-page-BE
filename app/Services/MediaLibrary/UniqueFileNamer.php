<?php

namespace App\Services\MediaLibrary;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Support\FileNamer\FileNamer;

class UniqueFileNamer extends FileNamer
{
	public function originalFileName(string $fileName): string
	{
		$hashedName = md5(date('YmdHis') . $fileName) . '_' . Str::random(5);

		return pathinfo($hashedName, PATHINFO_FILENAME);
	}

	public function conversionFileName(string $fileName, Conversion $conversion): string
	{
		$strippedFileName = pathinfo($fileName, PATHINFO_FILENAME);

		return "{$strippedFileName}-{$conversion->getName()}";
	}

	public function responsiveFileName(string $fileName): string
	{
		return pathinfo($fileName, PATHINFO_FILENAME);
	}
}
