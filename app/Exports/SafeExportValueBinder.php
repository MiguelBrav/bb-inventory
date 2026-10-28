<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Shared\StringHelper;

abstract class SafeExportValueBinder extends DefaultValueBinder implements WithCustomValueBinder
{
    public function bindValue(Cell $cell, mixed $value): bool
    {
        if (is_string($value)) {
            $cell->setValueExplicit(
                StringHelper::sanitizeUTF8($value),
                DataType::TYPE_STRING,
            );

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
