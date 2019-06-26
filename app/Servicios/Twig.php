<?php
namespace Parzibyte\Servicios;
use Parzibyte\Servicios\SesionService;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Twig
{

    public static function obtener()
    {
        $loader = new Twig_Loader_Filesystem(DIRECTORIO_RAIZ . "/vistas");
        $twig = new Twig_Environment($loader, []);
        $twig->addGlobal("URL_DIRECTORIO_PUBLICO", URL_DIRECTORIO_PUBLICO);
        $twig->addGlobal("RUTA_API", RUTA_API);
        $twig->addGlobal("URL_RAIZ", URL_RAIZ);
        $twig->addGlobal("NOMBRE_APLICACION", NOMBRE_APLICACION);
        $twig->addGlobal("TIEMPO_ACTUAL", time());
        $twig->addGlobal("USUARIO_LOGUEADO", SesionService::leer("correoUsuario"));
        return $twig;
    }
}
