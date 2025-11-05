<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Atletas</title>
    <style>
        /* =====================================================
           CONFIGURACIÓN GLOBAL
        ===================================================== */
        @page {
            margin: 115px 30px 70px 30px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #333;
        }

        /* =====================================================
           ENCABEZADO Y PIE
        ===================================================== */
        header {
            position: fixed;
            top: -95px;
            left: 0;
            right: 0;
            height: 95px;
            border-bottom: 2px solid #222A59;
        }

        footer {
            position: fixed;
            bottom: -45px;
            left: 0;
            right: 0;
            height: 40px;
            border-top: 1px solid #ccc;
            text-align: center;
            line-height: 20px;
            font-size: 9px;
            color: #555;
        }

        /* =====================================================
           ENCABEZADO INTERNO
        ===================================================== */
        .header-table {
            width: 100%;
        }

        .header-logo {
            width: 100px;
        }

        .header-center {
            text-align: center;
            vertical-align: middle;
            /* 🔹 centra verticalmente el texto */
            line-height: 1.3;
        }

        .header-title {
            color: #222A59;
            font-size: 17px;
            /* 🔹 más visible */
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .header-subtitle {
            color: #444;
            font-size: 13px;
            /* 🔹 un poco más grande */
            font-weight: bold;
            margin-top: 3px;
            letter-spacing: 0.3px;
        }

        /* =====================================================
           INFO DEL REPORTE
        ===================================================== */
        .info {
            text-align: center;
            margin-top: 25px;
            margin-bottom: 25px;
            font-size: 11px;
        }

        .info-box {
            display: inline-block;
            border: 1px solid #222A59;
            border-radius: 6px;
            padding: 6px 20px;
            background-color: #f5f7fa;
            color: #222A59;
            font-weight: bold;
        }

        /* =====================================================
           TABLA DE DATOS
        ===================================================== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px 4px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #222A59;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            font-size: 9.5px;
        }

        tr:nth-child(even) td {
            background-color: #f4f6fc;
        }
    </style>
</head>

<body>

    <!-- ===================== ENCABEZADO ===================== -->
    <header>
        <table class="header-table">
            <tr>
                <td width="15%" align="left" valign="middle">
                    <img src="{{ public_path('images/LogoFCT_transpa.png') }}" class="header-logo">
                </td>
                <td width="70%" class="header-center">
                    <p class="header-title">Federación Costarricense de Taekwondo</p>
                    <p class="header-subtitle">Listado de Atletas</p>
                </td>
                <td width="15%" align="right" valign="middle" style="font-size:9px; color:#555;">
                    Generado el:<br>
                    <strong>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</strong>
                </td>
            </tr>
        </table>
    </header>

    <!-- ===================== PIE DE PÁGINA ===================== -->
    <footer>
        <span style="font-size: 9px;">© Federación Costarricense de Taekwondo</span><br>
        <span class="page-number"></span>
    </footer>

    <!-- ===================== CUERPO DEL DOCUMENTO ===================== -->
    <main>
        <div class="info">
            <div class="info-box">
                Total de registros: {{ count($atletas) }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    @foreach (array_keys($atletas->first() ?? []) as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($atletas as $a)
                    <tr>
                        @foreach ($a as $valor)
                            <td>{{ $valor }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

    <!-- ===================== SCRIPT PARA NÚMERO DE PÁGINA ===================== -->
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $size = 9;
                $pageText = "Página " . $PAGE_NUM . " de " . $PAGE_COUNT;
                $y = 825;
                $x = 520;
                $pdf->text($x, $y, $pageText, $font, $size);
            ');
        }
    </script>

</body>

</html>
