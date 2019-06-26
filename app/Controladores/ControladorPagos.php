<?php

namespace Parzibyte\Controladores;

use Parzibyte\Modelos\ModeloPagos;

class ControladorPagos
{

    public static function index()
    {
        return view("pagos/pagos");
    }

    public static function actualizar()
    {
        $datos = json_decode(file_get_contents("php://input"));
        $resultado = ModeloPagos::actualizar($datos->id, $datos->idPersona, $datos->monto, $datos->fecha);
        return json($resultado);
    }

    public static function agregar()
    {
        $datos = json_decode(file_get_contents("php://input"));
        $resultado = ModeloPagos::agregar($datos->idPersona, $datos->monto, $datos->fecha);
        return json($resultado);
    }

    public static function eliminar($idPago)
    {
        return json(ModeloPagos::eliminar($idPago));
    }

    public static function obtener()
    {
        return json([
            "pagos" => ModeloPagos::obtener(),
            "total" => ModeloPagos::total(),
        ]);
    }

}
