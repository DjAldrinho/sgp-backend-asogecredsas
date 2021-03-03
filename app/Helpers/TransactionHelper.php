<?php


namespace App\Helpers;


class TransactionHelper
{
    public static function getOriginName($value)
    {
        switch ($value) {
            case "credit":
                return 'Desembolso de credito';
            case "commission":
                return "Pago de comision";
            case "credit_payment":
                return "Abono de credito";
            case "deposit":
                return "Abono de cuenta";
            case "retire":
                return "Retiro de cuenta";
            default:
                return "Otro";
        }
    }
}
