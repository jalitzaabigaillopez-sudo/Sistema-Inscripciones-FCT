<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Atletas</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 25px;
            color: #000;
        }

        /* Encabezado */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #d9d9d9;
            padding: 10px 20px;
        }
        .header img {
            width: 100px;
        }
        .titulo {
            text-align: center;
            font-weight: bold;
            color: #0c1f52;
            font-size: 16px;
        }
        .subtitulo {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
        }
        .fecha {
            text-align: right;
            font-size: 10px;
        }

        /* Línea divisoria */
        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 5px 0 15px;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #2b3a67;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #2b3a67;
            color: white;
            font-size: 10px;
        }
        td {
            font-size: 10px;
        }

        /* Total */
        .total {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
            border: 1px solid #2b3a67;
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <!-- Encabezado -->
    <div class="header">
        <div>
            <img src="{{ public_path('images/logo_fct.png') }}" alt="Logo FCT">
        </div>
        <div>
            <div class="titulo">FEDERACIÓN COSTARRICENSE DE TAEKWONDO</div>
            <div class="subtitulo">Listado de Atletas</div>
        </div>
        <div class="fecha">
            Generado el:<br>{{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <hr>

    <div class="total">
        Total de registros: {{ count($atletas) }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Tipo de ID</th>
                <th>Identificación</th>
                <th>Nombre completo</th>
                <th>Sexo</th>
                <th>División</th>
                <th>Grado</th>
                <th>Academia</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($atletas as $a)
                <tr>
                    <td>{{ $a['Tipo de identificacion'] }}</td>
                    <td>{{ $a['Identificacion'] }}</td>
                    <td>{{ $a['Nombre'] }} {{ $a['Primer apellido'] }} {{ $a['Segundo Apellido'] }}</td>
                    <td>{{ $a['Sexo'] }}</td>
                    <td>{{ $a['Division'] }}</td>
                    <td>{{ $a['Grado'] }}</td>
                    <td>{{ $a['Academia'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>