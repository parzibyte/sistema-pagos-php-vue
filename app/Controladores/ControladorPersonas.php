<?php

namespace Parzibyte\Controladores;

use Parzibyte\Modelos\ModeloPersonas;

class ControladorPersonas
{

    public static function index()
    {
        return view("personas");
    }

    public static function actualizar()
    {
        $datos = json_decode(file_get_contents("php://input"));
        return json(ModeloPersonas::actualizar($datos->nombre, $datos->id));
    }

    public static function agregar()
    {
        $datos = json_decode(file_get_contents("php://input"));
        return json(ModeloPersonas::agregar($datos->nombre));
    }

    public static function eliminar($idPersona)
    {
        return json(ModeloPersonas::eliminar($idPersona));
    }

    public static function obtener()
    {
        return json(ModeloPersonas::obtener());
    }

}
