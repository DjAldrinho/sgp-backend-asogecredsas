<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        td, th {
            border: 1px solid black;
            font-size: 8px;
        }

        body {
            font-size: 8pt
        }

    </style>
</head>
<body>
<table style="width:100%">
    <tr>
        <th>No. Credito</th>
        <th>Pagaduria</th>
        <th>Tipo</th>
        <th>Fecha Inicio</th>
        <th>F. Aprobacion</th>
        <th>F. Finalizacion</th>
        <th>Titular</th>
        <th>V. Capital</th>
        <th>V. Intereses</th>
        <th>V. Cuota</th>
        <th>Plazo</th>
        <th>T. Credito</th>
        <th>Saldo</th>
        <th>Asesor</th>
    </tr>
    @if(count($credits) > 0)
        @foreach($credits as $credit)
            <tr>
                <td>{{$credit->code}}</td>
                <td>{{strtoupper($credit->payroll->name)}}</td>
                <td>{{strtoupper($credit->credit_type->name)}}</td>
                <td>{{$credit->start_date}}</td>
                <td>{{($credit->approval_date) ? $credit->approval_date : ''}}</td>
                <td>{{($credit->end_date) ? $credit->end_date : ''}}</td>
                <td>{{$credit->debtor->name}}</td>
                <td>{{ $credit->capital_value}}</td>
                <td>{{$credit->liquidate['total_interest']}}</td>
                <td>{{$credit->liquidate['fees'][0]['fee_value']}}</td>
                <td>{{$credit->fee}}</td>
                <td>{{$credit->liquidate['total_credit']}}</td>
                <td>{{$credit->payment}}</td>
                <td>{{($credit->adviser) ? $credit->adviser->name : ''}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="14">
                No existe informacion
            </td>
        </tr>
    @endif
</table>
</body>
</html>
