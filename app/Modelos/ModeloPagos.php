<?php

namespace Parzibyte\Modelos;

use Parzibyte\Servicios\BD;
use Parzibyte\Servicios\Comun;
use PDO;

class ModeloPagos
{

    public static function agregar($idPersona, $monto, $fecha)
    {
        $hash = self::obtenerHashUnico();
        $bd = BD::obtener();
        $sentencia = $bd->prepare("insert into pagos(id_persona, monto, fecha, hash) values (?, ?, ?, ?)");
        return $sentencia->execute([$idPersona, $monto, $fecha, $hash]);
    }

    private static function obtenerHashUnico()
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select id from pagos where hash = ? limit 1");
        do {
            $hash = Comun::generarTokenAleatorioSeguro(10);
            $sentencia->execute([$hash]);
        } while ($sentencia->fetchObject());
        return $hash;
    }

    public static function eliminar($idPago)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("delete from pagos where id = ?");
        return $sentencia->execute([$idPago]);
    }

    public static function obtener()
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select pa.id, pa.id_persona as idPersona, pa.monto, pa.fecha,
        pa.hash, p.nombre as persona
        from pagos pa
        inner join personas p on p.id = pa.id_persona
        order by pa.fecha desc;");
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerPorId($idPago)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select pa.id, pa.id_persona as idPersona, pa.monto, pa.fecha,
        pa.hash, p.nombre as persona
        from pagos pa
        inner join personas p on p.id = pa.id_persona
        and pa.id = ?;");
        $sentencia->execute([$idPago]);
        return $sentencia->fetchObject();
    }

    public static function actualizar($idPago, $idPersona, $monto, $fecha)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("update pagos set
        id_persona = ?,
        monto = ?,
        fecha = ?
        where id = ?");
        return $sentencia->execute([$idPersona, $monto, $fecha, $idPago]);
    }

    public static function total()
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select coalesce(sum(monto), 0) as total from pagos;");
        $sentencia->execute();
        return $sentencia->fetchObject()->total;
    }

    public static function totalDePersona($idPersona)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select coalesce(sum(monto), 0) as total from pagos
        where id_persona = ?;");
        $sentencia->execute([$idPersona]);
        return $sentencia->fetchObject()->total;
    }

}
