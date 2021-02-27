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

        th {
            font-weight: bold;
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
<h1 style="text-align: center">Lista de Creditos</h1>
<p>Listando: {{count($credits)}} Creditos</p>
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
                <td>${{number_format($credit->capital_value, 2, '.', ',')}}</td>
                <td>${{number_format($credit->liquidate['total_interest'], 2, '.', ',')}}</td>
                <td>${{number_format($credit->liquidate['fees'][0]['fee_value'], 2, '.', ',')}}</td>
                <td>{{$credit->fee}}</td>
                <td>${{number_format($credit->liquidate['total_credit'], 2, '.', ',')}}</td>
                <td>${{number_format($credit->payment, 2, '.', ',')}}</td>
                <td>{{($credit->adviser) ? $credit->adviser->name : ''}}</td>
            </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;font-size: 10px" colspan="7">Total</td>
            <td style="font-weight: bold;font-size: 10px">${{number_format($total_capital, 2, '.', ',')}}</td>
            <td style="font-weight: bold;font-size: 10px">${{number_format($total_interest, 2, '.', ',')}}</td>
            <td style="font-weight: bold;font-size: 10px">${{number_format($fee_value, 2, '.', ',')}}</td>
            <td></td>
            <td style="font-weight: bold;font-size: 10px">${{number_format($total_credit, 2, '.', ',')}}</td>
            <td style="font-weight: bold;font-size: 10px">${{number_format($total_payment, 2, '.', ',')}}</td>
            <td></td>
        </tr>
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
