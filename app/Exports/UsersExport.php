<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport extends SafeExportValueBinder implements FromQuery, WithHeadings, WithMapping
{
    public function query(): Builder
    {
        return User::query()
            ->orderBy('name')
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Correo electrónico',
            'Tipo',
            'Fecha de registro',
        ];
    }

    /**
     * @param  User  $row
     * @return array<int, string>
     */
    public function map(mixed $row): array
    {
        return [
            $row->name,
            $row->email,
            $row->is_admin ? 'Administrador' : 'Usuario',
            $row->created_at->format('Y-m-d'),
        ];
    }
}
