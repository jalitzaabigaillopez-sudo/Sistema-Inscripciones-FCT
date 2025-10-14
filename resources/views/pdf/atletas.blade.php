<!DOCTYPE html>
<html>

<head>
    <title>Atletas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>
    <h2>Listado de Atletas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo de identificacion</th>
                <th>Identificacion</th>
                <th>Primer apellido</th>
                <th>Segundo Apellido</th>
                <th>Nombre</th>
                <th>Sexo</th>
                <th>Fecha de nacimiento</th>
                <th>Division</th>
                <th>Grado</th>
                <th>Academia</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($atletas as $a)
                <tr>
                    @foreach($a as $valor)
                        <td>{{ $valor }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>