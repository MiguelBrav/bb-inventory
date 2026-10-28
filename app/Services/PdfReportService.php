<?php

declare(strict_types=1);

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PdfReportService
{
    public function download(
        FromQuery&WithHeadings&WithMapping $export,
        string $title,
        string $filename,
    ): Response {
        $rows = [];

        foreach ($export->query()->get() as $row) {
            if (! $row instanceof Model) {
                throw new \UnexpectedValueException('The PDF export query must return Eloquent models.');
            }

            $rows[] = $export->map($row);
        }

        return Pdf::loadView('reports.pdf.table', [
            'title' => $title,
            'headings' => $export->headings(),
            'rows' => $rows,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ])
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }
}
