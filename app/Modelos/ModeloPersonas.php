<?php

namespace Parzibyte\Modelos;

use Parzibyte\Servicios\BD;
use Parzibyte\Servicios\SesionService;
use PDO;

class ModeloPersonas
{

    public static function agregar($nombre)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("insert into personas(nombre) values (?)");
        return $sentencia->execute([$nombre]);
    }

    public static function eliminar($idPersona)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("delete from personas where id = ?");
        return $sentencia->execute([$idPersona]);
    }

    public static function obtener()
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select id, nombre from personas");
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    public static function actualizar($nuevoNombre, $id)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("update personas set nombre = ? where id = ?");
        return $sentencia->execute([$nuevoNombre, $id]);
    }

}
