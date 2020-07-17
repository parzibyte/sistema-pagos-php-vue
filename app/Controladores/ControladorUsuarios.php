<?php

namespace Parzibyte\Controladores;

use Parzibyte\Modelos\ModeloUsuarios;
use Parzibyte\Servicios\Comun;

class ControladorUsuarios
{

    public static function index()
    {
        return view("usuarios");
    }

    public static function actualizarPalabraSecreta()
    {
        if (Comun::env("DENEGAR_USUARIOS", false)) {
            return json(true);
        }
        $datos = json_decode(file_get_contents("php://input"));
        return json(ModeloUsuarios::actualizarPalabraSecreta($datos->id, $datos->palabraSecretaActual, $datos->palabraSecretaNueva));
    }

    public static function agregar()
    {
        if (Comun::env("DENEGAR_USUARIOS", false)) {
            return json(true);
        }

        $datos = json_decode(file_get_contents("php://input"));
        return json(ModeloUsuarios::agregar($datos->correo, $datos->palabraSecreta));
    }

    public static function eliminar($idUsuario)
    {
        if (Comun::env("DENEGAR_USUARIOS", false)) {
            return json(true);
        }

        return json(ModeloUsuarios::eliminar($idUsuario));
    }

    public static function obtener()
    {
        return json(ModeloUsuarios::obtener());
    }

}
