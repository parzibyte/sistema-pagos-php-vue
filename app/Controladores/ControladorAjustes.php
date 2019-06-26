<?php
namespace Parzibyte\Controladores;

use Parzibyte\Modelos\ModeloAjustes;

class ControladorAjustes
{
    public static function index()
    {
        return view("ajustes");
    }

    public static function guardarMuchos($ajustes)
    {
        $ajustes = json_decode(file_get_contents("php://input"));
        return json(ModeloAjustes::guardarMuchos($ajustes));
    }

    public static function obtenerTodos()
    {
        return json(ModeloAjustes::obtenerTodos());
    }

}
