<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 24px; color: #111827; font-size: 12px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .logo { height: 50px; }
        .title { font-size: 18px; font-weight: 700; color: #111827; }
        .subtitle { font-size: 12px; color: #6B7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 8px 10px; border-bottom: 1px solid #E5E7EB; text-align: left; }
        th { background: #F3F4F6; font-size: 11px; text-transform: uppercase; letter-spacing: .03em; }
        .badge { padding: 4px 8px; border-radius: 9999px; font-size: 11px; font-weight: 700; }
        .success { background: #D1FAE5; color: #065F46; }
        .warning { background: #FEF3C7; color: #92400E; }
        .meta { margin-top: 4px; color: #6B7280; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="title">Reporte de asistencias</div>
            <div class="subtitle">SIGE · TECBLE SOLUTIONS</div>
            <div class="meta">Generado: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
        <div>
            @php $logo = public_path('images/logo.png'); @endphp
            @if(file_exists($logo))
                <img src="{{ $logo }}" class="logo" alt="Logo">
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $attendance)
                <tr>
                    <td>{{ optional($attendance->date)->format('d/m/Y') }}</td>
                    <td>{{ $attendance->user->username ?? 'N/D' }}</td>
                    <td>{{ optional($attendance->time_in)->format('H:i') }}</td>
                    <td>{{ optional($attendance->time_out)->format('H:i') }}</td>
                    <td>
                        <span class="badge {{ $attendance->status === 'abierta' ? 'warning' : 'success' }}">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
