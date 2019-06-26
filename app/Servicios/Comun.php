<?php
namespace Parzibyte\Servicios;

use Exception;

class Comun
{
    public static function generarTokenAleatorioSeguro($longitud)
    {
        if ($longitud < 4) {
            $longitud = 4;
        }
    
        return bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
    }
    public static function env($clave, $valorPorDefecto = null)
    {
        /*
         * Un pequeño sistema de caché
         * Guardar los valores de ENV en la memoria RAM cuyo período de vida será el mismo que el del script
         * Así, en múltiples llamadas a ENV, sólo la primera vez será leído del disco duro, la segunda desde
         * la constante
         * */
        if (defined("_ENV_CACHE")) {
            $configuraciones = _ENV_CACHE;
        } else {
            $archivo = DIRECTORIO_APLICACION . "/env.php";
            if (!file_exists($archivo)) {
                throw new Exception("El archivo de configuración ($archivo) no existe");
            }
            $configuraciones = parse_ini_file($archivo);
            define("_ENV_CACHE", $configuraciones);
        }
        if (isset($configuraciones[$clave])) {
            return $configuraciones[$clave];
        } else {
            if ($valorPorDefecto) {
                return $valorPorDefecto;
            }

            throw new Exception("No existe la clave (" . $clave . ") en el archivo de configuración");
        }
    }
}
