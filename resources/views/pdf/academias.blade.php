<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listado de Academias</title>
    <style>
        @page { margin: 115px 30px 70px 30px; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #333; }
        header { position: fixed; top: -95px; left: 0; right: 0; height: 95px; border-bottom: 2px solid #222A59; }
        footer { position: fixed; bottom: -45px; left: 0; right: 0; height: 40px; border-top: 1px solid #ccc; text-align: center; font-size: 9px; color: #555; }
        .header-table { width: 100%; }
        .header-logo { width: 90px; }
        .header-title { color: #222A59; font-size: 17px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .header-subtitle { color: #444; font-size: 13px; font-weight: bold; margin-top: 3px; }
        .info { text-align: center; margin-top: 25px; margin-bottom: 25px; font-size: 11px; }
        .info-box { display: inline-block; border: 1px solid #222A59; border-radius: 6px; padding: 6px 20px; background-color: #f5f7fa; color: #222A59; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #ccc; padding: 5px 4px; text-align: center; vertical-align: middle; }
        th { background-color: #222A59; color: white; font-weight: bold; font-size: 10px; }
        tr:nth-child(even) td { background-color: #f4f6fc; }
    </style>
</head>
<body>
    <header>
        <table class="header-table">
            <tr>
                <td width="15%" align="left">
                    <img src="{{ public_path('images/LogoFCT_transpa.png') }}" class="header-logo">
                </td>
                <td width="70%" align="center">
                    <p class="header-title">Federación Costarricense de Taekwondo</p>
                    <p class="header-subtitle">Listado de Academias</p>
                </td>
                <td width="15%" align="right" style="font-size:9px; color:#555;">
                    Generado el:<br><strong>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</strong>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <span style="font-size: 9px;">© Federación Costarricense de Taekwondo</span>
    </footer>

    <main>
        <div class="info">
            <div class="info-box">
                Total de academias: {{ count($academias) }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    @foreach(array_keys($academias->first() ?? []) as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($academias as $a)
                    <tr>
                        @foreach($a as $valor)
                            <td>{{ $valor }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>
</html>
