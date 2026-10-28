<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }

        body {
            color: #212529;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9px;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 6px;
        }

        .report-meta {
            color: #6c757d;
            margin-bottom: 12px;
        }

        table {
            border-collapse: collapse;
            table-layout: auto;
            width: 100%;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 5px;
            text-align: left;
            vertical-align: top;
            overflow-wrap: break-word;
        }

        th {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="report-meta">
        Generado: {{ $generatedAt }} | Registros: {{ count($rows) }}
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($row as $value)
                        <td>{{ $value ?? '—' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headings) }}">No hay registros para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
