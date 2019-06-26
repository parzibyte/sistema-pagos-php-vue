<?php
namespace Parzibyte\Controladores;

use Exception;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Parzibyte\Modelos\ModeloPagos;
use Parzibyte\Servicios\Comun;

class ControladorTickets
{
    public static function imprimirTicketDePago($idPago)
    {
        try {
            $nombreImpresora = Comun::env("NOMBRE_IMPRESORA");
        } catch (Exception $e) {
            return false;
        }
        $connector = new WindowsPrintConnector($nombreImpresora);
        $printer = new Printer($connector);
        $pago = ModeloPagos::obtenerPorId($idPago);
        if (!$pago) {
            return false;
        }
        $repeticiones = 20;
        $printer->setTextSize(2, 2);
        $printer->text("Comprobante");
        $printer->feed();
        $printer->setTextSize(1, 1);
        $printer->text(str_repeat("=", $repeticiones));
        $printer->feed();
        $printer->text("Nombre: " . $pago->persona);
        $printer->feed();
        $printer->text("Monto: $" . number_format($pago->monto, 2, ".", ","));
        $printer->feed();
        $printer->text("Fecha: " . $pago->fecha);
        $printer->feed();
        $printer->text("Hash: " . $pago->hash);
        $printer->feed();
        $printer->text(str_repeat("=", $repeticiones));
        $printer->feed();
        
        $printer->feed();
        $printer->text("Total abonado por ti: $" . number_format(ModeloPagos::totalDePersona($pago->idPersona), 2, ".", ","));
        $printer->feed();
        $printer->text("Total abonado por todos: $" . number_format(ModeloPagos::total(), 2, ".", ","));
        $printer->feed(2);
        $printer->text("(Calculados en la fecha de impreso este ticket)");

        $printer->feed(5);
        $printer->cut();
        $printer->pulse();
        $printer->close();
        return true;
    }
}
