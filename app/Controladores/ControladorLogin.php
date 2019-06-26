<?php

namespace Parzibyte\Controladores;

use Parzibyte\Modelos\ModeloUsuarios;
use Parzibyte\Servicios\SesionService;

class ControladorLogin
{

    public static function index()
    {
        return view("login");
    }

    public static function login()
    {
        $datos = json_decode(file_get_contents("php://input"));
        return json(ModeloUsuarios::login($datos->correo, $datos->palabraSecreta));
    }
    public static function logout()
    {
        SesionService::destruir();
        redirect("/login");
    }
}
