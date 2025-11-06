<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Reporte de Inscripciones</title>
    <style>
        /* ================== CONFIGURACIÓN GLOBAL ================== */
        @page {
            margin: 10px 15px;
            /* Márgenes del documento (arriba/izq/der/abajo) */
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        main {
            margin-top: 0;
        }

        ```

        /* ================== ENCABEZADO Y PIE ================== */
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

        /* ================== ENCABEZADO INTERNO ================== */
        .header-table {
            width: 100%;
        }

        .header-logo {
            width: 100px;
        }

        .header-center {
            text-align: center;
            line-height: 1.3;
            vertical-align: middle;
        }

        .header-title {
            color: #222A59;
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .header-subtitle {
            color: #444;
            font-size: 13px;
            font-weight: bold;
            margin-top: 3px;
            letter-spacing: 0.3px;
        }

        /* ================== INFO GENERAL ================== */
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

        /* ================== TABLAS ================== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            page-break-inside: auto;
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

        /* ================== TÍTULO DE ACADEMIA ================== */
        .academia-title {
            background-color: #e8ebf8;
            color: #222A59;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #222A59;
            border-radius: 5px;
            padding: 6px 10px;
            margin-top: 20px;
            font-size: 12px;
        }
    </style>
    ```

</head>

<body>
    <!-- ===================== ENCABEZADO ===================== -->
    <header>
        <table class="header-table">
            <tr>
                <td width="15%" align="left">
                    <img src="{{ public_path('images/LogoFCT_transpa.png') }}" class="header-logo">
                </td>
                <td width="70%" class="header-center">
                    <p class="header-title">Federación Costarricense de Taekwondo</p>
                    <p class="header-subtitle">Reporte de Inscripciones totales</p>
                </td>
                <td width="15%" align="right" style="font-size:9px; color:#555;">
                    Generado el:<br>
                    <strong>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</strong>
                </td>
            </tr>
        </table>
    </header>

    ```
    <!-- ===================== PIE DE PÁGINA ===================== -->
    <footer>
        <span>© Federación Costarricense de Taekwondo</span><br>
        <span class="page-number"></span>
    </footer>

    <!-- ===================== CUERPO DEL DOCUMENTO ===================== -->


    <main>
        @foreach ($inscripciones as $academia => $grupo)
            <div class="info">
                <div class="academia-title">{{ $academia }}</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        @foreach (array_keys($grupo->first() ?? []) as $header)
                            @if ($header !== 'Academia') {{-- Oculta la columna Academia --}}
                                <th>{{ $header }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grupo as $inscripcion)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @foreach ($inscripcion as $campo => $valor)
                                @if ($campo !== 'Academia') {{-- Oculta el valor correspondiente --}}
                                    <td>{{ $valor }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </main>


    <!-- ===================== NÚMERO DE PÁGINA ===================== -->
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
    ```

</body>

</html>