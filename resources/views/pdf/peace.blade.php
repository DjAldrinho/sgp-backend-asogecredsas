<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado de Paz y Salvo</title>
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
        <h2 style="text-align: center;margin-top: 5em;font-weight: bold">CERTIFICADO DE PAZ Y SALVO</h2>
        <p style="margin-top: 5em">Certificamos que el señor(a) <b>{{$credit->debtor->name}}</b>, identificado(a) con
            Cédula
            de ciudadanía No. <b>{{$credit->debtor->document_number}}</b>
            se encuentra a PAZ Y SALVO con la empresa SOGECRED SAS NIT 901628394-1
            por <b>{{strtoupper($credit->credit_type->name)}} #{{$credit->code}}</b>
            de {{strtoupper($credit->payroll->name)}}.
            La presente sólo es válida para el crédito en mención</p>
    </div>
    <div style="margin-top: 5em;text-align: center">
        <div style="width: 250px;text-align: left;">
            <div style="border-bottom: 2px solid black">
                <img src="{{public_path('pdf/Firma.png')}}" style="width: 100px">
            </div>
            <br>
            <b>YULIANA GUARDO JIMENEZ <br>
                JEFE DE CARTERA</b>
        </div>
        <div style="margin-top: 2em">
            <p style="text-align: center">
                BRR LA CAROLINA CRA 91 TRANSV 54-120 <br>
                Tels.: 315-7673739-301-3159380 <br>
                asogecred@gmail.com <br>
                CARTAGENA-BOLIVAR
            </p>
        </div>
    </div>
</div>
</body>
</html>
