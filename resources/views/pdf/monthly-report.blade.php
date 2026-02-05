<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte Mensual de Vacunatorio</title>
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
        .text-center { text-align: center; }
        .font-mono { font-family: 'Courier New', monospace; }
        .font-bold { font-weight: bold; }
        
        /* Badges & Colors */
        .badge-positive { color: #059669; font-weight: bold; } /* Green */
        .badge-negative { color: #e11d48; font-weight: bold; } /* Red */
        .badge-neutral { color: #94a3b8; }
        
        .code { font-size: 9px; color: #64748b; margin-top: 2px; }
        
        /* Summary Box */
        .summary-box { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 4px; margin-top: 20px; page-break-inside: avoid; }
        .summary-title { font-weight: bold; color: #0f172a; border-bottom: 1px solid #cbd5e1; padding-bottom: 5px; margin-bottom: 10px; font-size: 12px; }
        .summary-grid { width: 100%; }
        .summary-item { width: 33.33%; float: left; text-align: center; }
        .summary-value { font-size: 16px; font-weight: bold; color: #0ea5e9; }
        .summary-label { font-size: 9px; color: #64748b; uppercase; margin-top: 3px; }

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
            Fecha de Emisión: {{ date('d/m/Y') }}<br>
            Generado por: {{ $user }}
        </div>
    </header>

    <footer>
        Página <span class="pagenum"></span> - Documento generado automáticamente por Vax Control v1.1.0
    </footer>

    <h1>INFORME MENSUAL DE GESTIÓN</h1>
    <h2>PERIODO: {{ strtoupper($month) }} {{ $year }}</h2>

    <table>
        <thead>
            <tr>
                <th width="35%">VACUNA / PRODUCTO</th>
                <th width="13%" class="text-right">INGRESOS</th>
                <th width="13%" class="text-right">ADMINISTRADAS</th>
                <th width="13%" class="text-right">DESPACHOS</th>
                <th width="13%" class="text-right">MERMAS</th>
                <th width="13%" class="text-right">STOCK ACTUAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td>
                        <div class="font-bold">{{ $row['name'] }}</div>
                        <div class="code">COD: {{ $row['code'] }}</div>
                    </td>
                    <td class="text-right font-mono">
                        @if($row['inputs'] > 0) <span class="badge-positive">+{{ number_format($row['inputs']) }}</span> @else <span class="badge-neutral">-</span> @endif
                    </td>
                    <td class="text-right font-mono">
                        @if($row['administered'] > 0) {{ number_format($row['administered']) }} @else <span class="badge-neutral">-</span> @endif
                    </td>
                    <td class="text-right font-mono">
                        @if($row['dispatched'] > 0) {{ number_format($row['dispatched']) }} @else <span class="badge-neutral">-</span> @endif
                    </td>
                    <td class="text-right font-mono">
                        @if($row['wastage'] > 0) <span class="badge-negative">{{ number_format($row['wastage']) }}</span> @else <span class="badge-neutral">-</span> @endif
                    </td>
                    <td class="text-right font-mono font-bold" style="background-color: #f1f5f9;">
                        {{ number_format($row['current_stock']) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 30px; font-style: italic; color: #94a3b8;">
                        No se registraron movimientos contables en este periodo.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-title">RESUMEN EJECUTIVO</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value">{{ number_format(collect($data)->sum('inputs')) }}</div>
                <div class="summary-label">TOTAL INGRESOS</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ number_format(collect($data)->sum('administered')) }}</div>
                <div class="summary-label">DOSIS ADMINISTRADAS</div>
            </div>
            <div class="summary-item">
                <div class="summary-value" style="color: #e11d48;">{{ number_format(collect($data)->sum('wastage')) }}</div>
                <div class="summary-label">MERMAS DECLARADAS</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>
</html>
