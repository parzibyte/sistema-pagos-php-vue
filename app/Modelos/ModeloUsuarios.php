<?php

namespace Parzibyte\Modelos;

use Parzibyte\Servicios\BD;
use Parzibyte\Servicios\SesionService;
use PDO;

class ModeloUsuarios
{

    public static function login($correo, $palabraSecreta)
    {
        if (!self::coincideUsuarioYPassPorCorreo($correo, $palabraSecreta)) {
            return false;
        }

        SesionService::escribir("correoUsuario", $correo);
        return true;
    }

    private static function cifrarPalabraSecreta($palabraSecreta)
    {
        return password_hash(md5($palabraSecreta), PASSWORD_BCRYPT);
    }

    public static function actualizarPalabraSecreta($id, $palabraSecretaActual, $palabraSecretaNueva)
    {
        if (!self::coincideUsuarioYPassPorId($id, $palabraSecretaActual)) {
            return false;
        }

        $bd = BD::obtener();
        $palabraSecretaCifrada = self::cifrarPalabraSecreta($palabraSecretaNueva);
        $sentencia = $bd->prepare("update usuarios set palabra_secreta = ? where id = ?");
        return $sentencia->execute([$palabraSecretaCifrada, $id]);
    }

    public static function agregar($correo, $palabraSecreta)
    {
        $bd = BD::obtener();
        $palabraSecretaCifrada = self::cifrarPalabraSecreta($palabraSecreta);
        $sentencia = $bd->prepare("insert into usuarios(correo, palabra_secreta) values (?, ?)");
        return $sentencia->execute([$correo, $palabraSecretaCifrada]);
    }

    private static function coincideUsuarioYPassPorCorreo($correo, $palabraSecreta)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select id, correo, palabra_secreta from usuarios where correo = ?");
        $sentencia->execute([$correo]);
        $usuario = $sentencia->fetchObject();
        if (!$usuario) {
            return false;
        }

        return password_verify(md5($palabraSecreta), $usuario->palabra_secreta);
    }

    private static function coincideUsuarioYPassPorId($id, $palabraSecreta)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select id, correo, palabra_secreta from usuarios where id = ?");
        $sentencia->execute([$id]);
        $usuario = $sentencia->fetchObject();
        if (!$usuario) {
            return false;
        }

        return password_verify(md5($palabraSecreta), $usuario->palabra_secreta);
    }

    public static function eliminar($idUsuario)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("delete from usuarios where id = ?");
        return $sentencia->execute([$idUsuario]);
    }

    public static function obtener()
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select id, correo from usuarios");
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

}
