<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado de Credito</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            border-collapse: collapse;
            border: 1px solid black;
            margin-left: auto;
            margin-right: auto;
        }

        td, th {
            border: 1px solid black;
            font-weight: bold;
            text-align: center;
        }

        p {
            text-align: justify;
        }

        #watermark {
            position: fixed;

            /**
                Establece una posición en la página para tu imagen
                Esto debería centrarlo verticalmente
            **/
            bottom: 10cm;
            left: 5.5cm;

            /** Cambiar las dimensiones de la imagen **/
            width: 8cm;
            height: 8cm;

            /** Tu marca de agua debe estar detrás de cada contenido **/
            z-index: -1000;
        }
    </style>
</head>
<body>
<div>
    <div id="watermark">
        <img src="{{public_path('pdf/logo-fondo.jpg')}}" height="100%" width="100%" style="opacity: 0.2"/>
    </div>
    <div style="text-align: right">
        <img src="{{public_path('pdf/logo-asogecred.png')}}" style="width: 200px">
    </div>
    <div style="margin-top: 2em">
        <p>Cartagena, {{\Carbon\Carbon::now()->format('d \\d\\e M \\d\\e Y')}}</p>
    </div>
    <div style="margin-top: 0.50em">
        <h2 style="text-align: center;margin-top: 2em;font-weight: bold">A QUIEN INTERESE</h2>
        <p style="margin-top: 3em"> Certificamos que <b>{{$credit->debtor->name}}</b>, identificado(a) con
            Cédula de Ciudadanía No. <b>{{$credit->debtor->document_number}}</b> a la fecha presenta el siguiente Saldo
            por recaudar de sus créditos actuales así:</p>
    </div>
    <div style="margin-top: 0.50em;text-align: center">
        <table style="width: 250px">
            <tr>
                <td>Crédito Número</td>
            </tr>
            <tr>
                <td>
                    {{strtoupper($credit->credit_type->name)}} {{$credit->code}} <br>
                    Cuota mensual <br>
                    ${{number_format($credit->liquidate['fees'][0]['fee_value'], 2, '.', ',')}}
                </td>
            </tr>
            <tr>
                <td>Saldo a la Fecha</td>
            </tr>
            <tr>
                <td>${{number_format($credit->payment, 2, '.', ',')}}</td>
            </tr>
        </table>
        <p style="margin-top: 2em;margin-bottom:2em;text-align: center;font-weight: bold">
            Validez: {{\Carbon\Carbon::now()->format('d \\d\\e M \\d\\e Y')}}
        </p>
        <p>
            <b>Nota: </b>El pago de este saldo debe ser cancelado mediante CONSIGNACIÓN en la cuenta de
            Ahorros No.: 678-000102-29 de BANCOLOMBIA a nombre de <b>ASOGECRED SAS</b>
            NIT: 901628394-1, luego de consignado por favor notificar el pago al correo
            <b>asogecred@gmail.com</b>.
        </p>
        <div style="width: 250px;text-align: left;">
            <div style="border-bottom: 2px solid black">
                <img src="{{public_path('pdf/Firma.png')}}" style="width: 100px">
            </div>
            <br>
            <b>YULIANA GUARDO JIMENEZ <br>
                JEFE DE CARTERA
            </b>
        </div>
        <div style="margin-top: 2em">
            <p style="text-align: center">
                BRR LA CAROLINA CRR 91 TRANVS 54-120 <br>
                Tels.: 3157673739-3013159380 <br>
                asogecred@gmail.com
                <br>
                CARTAGENA-BOLIVAR
            </p>
        </div>
    </div>
</div>
</body>
</html>
