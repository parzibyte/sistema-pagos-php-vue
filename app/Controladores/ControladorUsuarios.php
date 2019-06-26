<?php

namespace Parzibyte\Controladores;

use Parzibyte\Modelos\ModeloUsuarios;

class ControladorUsuarios
{

    public static function index()
    {
        return view("usuarios");
    }

    public static function actualizarPalabraSecreta()
    {
        $datos = json_decode(file_get_contents("php://input"));
        return json(ModeloUsuarios::actualizarPalabraSecreta($datos->id, $datos->palabraSecretaActual));
    }

    public static function agregar()
    {
        $datos = json_decode(file_get_contents("php://input"));
        return json(ModeloUsuarios::agregar($datos->correo, $datos->palabraSecreta));
    }

    public static function eliminar($idUsuario)
    {
        return json(ModeloUsuarios::eliminar($idUsuario));
    }

    public static function obtener()
    {
        return json(ModeloUsuarios::obtener());
    }

}
