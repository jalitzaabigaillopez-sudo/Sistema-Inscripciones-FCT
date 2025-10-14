<!DOCTYPE html>
<html>

<head>
    <title>Inscripciones</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 4px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>
    <h2>Listado de Inscripciones</h2>
    @foreach($inscripciones as $academia => $grupo)
        <h3>{{ $academia }}</h3>
        <table>
            <thead>
                <tr>
                    @foreach(array_keys($grupo->first()) as $columna)
                        <th>{{ $columna }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($grupo as $i)
                    <tr>
                        @foreach($i as $valor)
                            <td>{{ $valor }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endforeach
</body>

</html>