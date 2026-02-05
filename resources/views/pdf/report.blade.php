<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Control de Vacunas</title>
    <style>
        @page { margin: 100px 30px 60px 30px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #334155; line-height: 1.4; }
        
        /* Layout */
        header { position: fixed; top: -80px; left: 0px; right: 0px; height: 70px; border-bottom: 2px solid #0ea5e9; padding-bottom: 10px; }
        footer { position: fixed; bottom: -50px; left: 0px; right: 0px; height: 40px; border-top: 1px solid #cbd5e1; padding-top: 10px; font-size: 9px; color: #94a3b8; text-align: center; }

        .logo { float: left; width: 60px; height: auto; }
        .header-text { margin-left: 70px; padding-top: 5px; }
        .hospital-name { font-size: 16px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .service-name { font-size: 12px; color: #0ea5e9; font-weight: bold; margin-top: 2px; }
        .meta-info { float: right; text-align: right; margin-top: 5px; font-size: 10px; color: #64748b; }

        /* Titles */
        h1 { font-size: 18px; font-weight: bold; text-align: center; margin-top: 20px; color: #1e293b; letter-spacing: -0.5px; }
        h2 { font-size: 12px; font-weight: bold; text-align: center; color: #64748b; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 1px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        th { background-color: #f1f5f9; color: #334155; font-weight: bold; text-transform: uppercase; font-size: 9px; padding: 10px 8px; border-bottom: 2px solid #cbd5e1; text-align: left; }
        td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        tr:nth-child(even) { background-color: #f8fafc; }
        
        /* Utils */
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', monospace; }
        .font-bold { font-weight: bold; }
        
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; display: inline-block; }
        .badge-admin { background: #d1fae5; color: #047857; }
        .badge-dispatch { background: #e0f2fe; color: #0284c7; }
        .badge-loss { background: #ffe4e6; color: #e11d48; }

        .user-filter { text-align: center; margin-bottom: 15px; color: #059669; font-weight: bold; font-size: 12px; }
    </style>
</head>
<body>
    <header>
        <img src="{{ $logoBase64 }}" class="logo" alt="Logo">
        <div class="header-text">
            <div class="hospital-name">Hospital de Puerto Aysén</div>
            <div class="service-name">SISTEMA INTEGRAL DE CONTROL DE VACUNAS</div>
        </div>
        <div class="meta-info">
            Fecha de Emisión: {{ date('d/m/Y H:i') }}<br>
            Generado por: {{ $user }}
        </div>
    </header>

    <footer>
        Página <span class="pagenum"></span> - Documento generado automáticamente por Vax Control v1.1.0
    </footer>

    <h1>REPORTE DE MOVIMIENTOS DETALLADO</h1>
    <h2>{{ $title }}</h2>
    
    <div style="text-align: center; margin-bottom: 20px; font-size: 11px; color: #64748b;">
        Periodo: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> - <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
    </div>

    @if($userFilter)
        <div class="user-filter">
            FILTRADO POR USUARIO: {{ strtoupper($userFilter) }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th width="15%">FECHA</th>
                <th width="30%">VACUNA</th>
                <th width="20%">RESPONSABLE</th>
                <th width="10%" class="text-right">CANTIDAD</th>
                <th width="25%">DETALLE / NOTAS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
            <tr>
                <td class="font-mono">{{ $row->movement->posted_at ? \Carbon\Carbon::parse($row->movement->posted_at)->format('d/m/Y H:i') : '-' }}</td>
                <td>
                    <div class="font-bold">{{ $row->vaccine->name }}</div>
                </td>
                <td>{{ $row->movement->user->name ?? 'Sistema' }}</td>
                <td class="text-right font-mono font-bold">{{ number_format($row->quantity) }}</td>
                <td style="font-size: 10px; color: #64748b; font-style: italic;">
                    {{ \Illuminate\Support\Str::limit($row->movement->notes, 60) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px; color: #94a3b8; font-style: italic;">
                    No se encontraron registros para este criterio.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right; border-top: 2px solid #e2e8f0; padding-top: 10px;">
        <strong>TOTAL REGISTROS:</strong> {{ count($data) }} | 
        <strong>CANTIDAD TOTAL:</strong> {{ number_format($data->sum('quantity')) }}
    </div>

    <!-- Script for Page Numbers if supported by DomPDF (CSS content counter usually better) -->
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $size = 9;
            $font = $fontMetrics->getFont("Arial");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size, array(0.58, 0.64, 0.72));
        }
    </script>
</body>
</html>
