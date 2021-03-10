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
<h1 style="text-align: center">Lista de Transacciones</h1>
<p>Listando: {{count($transactions)}} Creditos</p>
<table style="width:100%">
    <tr>
        <th>No. Transaccion</th>
        <th>Cuenta</th>
        <th>Credito</th>
        <th>Tipo</th>
        <th>Valor</th>
        <th>Tipo de transaccion</th>
        <th>Fecha</th>
        <th>Comentario</th>
    </tr>
    @if(count($transactions) > 0)
        @foreach($transactions as $transaction)
            <tr>
                <td>{{$transaction->code}}</td>
                <td>{{($transaction->account) ? $transaction->account->account_number . ' - ' . $transaction->account->name : ''}}</td>
                <td>{{($transaction->credit) ? $transaction->credit->code . ' - ' . $transaction->credit->debtor->name : ''}}</td>
                <td>{{\App\Helpers\TransactionHelper::getOriginName($transaction->origin)}}</td>
                <td>{{number_format($transaction->value, 2, '.', ',')}}</td>
                <td> {{($transaction->credit_type) ? $transaction->credit_type->name : ''}}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->isoFormat('DD/MM/Y')}}</td>
                <td>{{$transaction->commentary}}</td>
            </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;font-size: 10px" colspan="4">Total</td>
            <td style="font-weight: bold;font-size: 10px">${{number_format($total, 2, '.', ',')}}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @else
        <tr>
            <td colspan="8">
                No existe informacion
            </td>
        </tr>
    @endif
</table>
</body>
</html>
