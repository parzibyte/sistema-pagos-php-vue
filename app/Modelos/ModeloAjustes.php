<?php
namespace Parzibyte\Modelos;

use Parzibyte\Servicios\BD;
use PDO;

class ModeloAjustes
{
    public static function guardarMuchos($ajustes)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("update comun set valor = ? where clave = ?");
        $bd->beginTransaction();
        foreach ($ajustes as $ajuste) {
            $sentencia->execute([$ajuste->valor, $ajuste->clave]);
        }
        return $bd->commit();
    }

    public static function obtenerTodos()
    {
        $bd = BD::obtener();
        $sentencia = $bd->query("select clave, valor from comun");
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerPorClave($clave)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select clave, valor from comun where clave = ?");
        $sentencia->execute([$clave]);
        return $sentencia->fetchObject();
    }

    public static function insertar($clave, $valor)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("insert into comun(clave, valor) values (?, ?)");
        return $sentencia->execute([$clave, $valor]);
    }

    public static function actualizar($clave, $valor)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("update comun set valor = ? where clave = ?");
        return $sentencia->execute([$valor, $clave]);
    }

    public static function eliminar($clave)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("delete from comun where clave = ?");
        return $sentencia->execute([$clave]);
    }

}
