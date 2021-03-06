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
        <img src="{{public_path('pdf/logo.jpg')}}" height="100%" width="100%"/>
    </div>
    <div style="text-align: right">
        <img src="{{public_path('pdf/logo2.png')}}" style="width: 200px">
    </div>
    <div style="margin-top: 2em">
        <p>Cartagena, {{\Carbon\Carbon::now()->format('d \\d\\e M \\d\\e Y')}}</p>
    </div>
    <div style="margin-top: 0.50em">
        <h2 style="text-align: center;margin-top: 2em;font-weight: bold">A QUIEN INTERESE</h2>
        <p style="margin-top: 3em"> Certificamos que CARO GARCIA MARISELA, identificado(a) con
            Cédula de Ciudadanía No. {{$credit->debtor->document_number}} a la fecha presenta el siguiente Saldo
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
            <b>Nota: </b>El pago de este saldo debe ser cancelado mediante CONSIGNACIÓN en la cuenta de Ahorros No.:
            085-000003-51 de BANCOLOMBIA a nombre de INVERSIONES EN FAMILIA
            SAS NIT.: 901211975, luego de consignado por favor notificar el pago al correo
            Valoresenfamiliacorreo@gmail.com
        </p>
        <div style="width: 200px;text-align: left;">
            <div style="border-bottom: 2px solid black">
                <img src="{{public_path('pdf/Firma.png')}}" style="width: 100px">
            </div>
            <br>
            <b>YULIANA GUARDO JIMENEZ <br>
                JEFE DE CARTERA</b>
        </div>
        <div style="margin-top: 2em">
            <p style="text-align: center">
                CENTRO EDIFICIO BOMBAY 3 PISO OFICINA 305 <br>
                Tels.: 3205140197-6653788 <br>
                Valoresenfamiliacorreo@gmail.com <br>
                CARTAGENA-BOLIVAR
            </p>
        </div>
    </div>
</div>
</body>
</html>
