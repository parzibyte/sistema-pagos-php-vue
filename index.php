<?php
require __DIR__ . '/vendor/autoload.php'; #Cargar todas las dependencias

use Parzibyte\Servicios\Comun;
use Parzibyte\Servicios\SesionService;
use Parzibyte\Servicios\Twig;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\RouteCollector;

define("DIRECTORIO_RAIZ", __DIR__);
define("DIRECTORIO_APLICACION", DIRECTORIO_RAIZ . "/app");
define("RUTA_LOGS", __DIR__ . DIRECTORY_SEPARATOR . "logs");
define("URL_RAIZ", Comun::env("URL_RAIZ"));
define("URL_DIRECTORIO_PUBLICO", URL_RAIZ . "/public");
define("RUTA_API", URL_RAIZ . "/api");
define("NOMBRE_APLICACION", "Sistema de pagos");
define("AUTOR", "Luis Cabrera Benito a.k.a <a href='https://parzibyte.me'>parzibyte</a>");
ini_set('display_errors', 0);
ini_set("log_errors", 1);
ini_set("error_log", __DIR__ . "/logs/" . date("Y-m-d") . ".log");
if (!file_exists(RUTA_LOGS)) {
    mkdir(RUTA_LOGS);
}

function view($nombre)
{
    echo Twig::obtener()->render("$nombre.twig");
    return;
}

function json($datos)
{
    header("Content-type: application/json");
    echo json_encode($datos);
    return;
}

function redirect($ruta)
{
    header("Location: " . URL_RAIZ . $ruta);
    exit;
}

$enrutador = new RouteCollector();

$enrutador->filter("logueado", function () {
    if (empty(SesionService::leer("correoUsuario"))) {
        return redirect("/login");
    }
});

$enrutador
    ->group(["before" => "logueado"], function ($enrutadorVistasPrivadas) {
        $enrutadorVistasPrivadas
            ->get("/ajustes", ["Parzibyte\Controladores\ControladorAjustes", "index"])
            ->get("/usuarios", ["Parzibyte\Controladores\ControladorUsuarios", "index"])
            ->get("/personas", ["Parzibyte\Controladores\ControladorPersonas", "index"])
            ->get("/pagos", ["Parzibyte\Controladores\ControladorPagos", "index"])
            ->get("/logout", ["Parzibyte\Controladores\ControladorLogin", "logout"]);
    });

$enrutador->get("/login", ["Parzibyte\Controladores\ControladorLogin", "index"]);

$enrutador
    ->group(
        ["prefix" => "api"],
        function ($enrutadorApi) {
            $enrutadorApi
                ->put("/usuario/login", ["Parzibyte\Controladores\ControladorLogin", "login"]);
            $enrutadorApi->group(
                ["before" => "logueado"],
                function ($enrutadorApiLogueado) {
                    $enrutadorApiLogueado
                        ->get("/ajustes", ["Parzibyte\Controladores\ControladorAjustes", "obtenerTodos"])
                        ->post("/ajustes", ["Parzibyte\Controladores\ControladorAjustes", "guardarMuchos"])
                        ->get("/usuarios", ["Parzibyte\Controladores\ControladorUsuarios", "obtener"])
                        ->post("/usuario", ["Parzibyte\Controladores\ControladorUsuarios", "agregar"])
                        ->put("/usuario", ["Parzibyte\Controladores\ControladorUsuarios", "actualizarPalabraSecreta"])
                        ->delete("/usuario/{idUsuario}", ["Parzibyte\Controladores\ControladorUsuarios", "eliminar"])
                        ->get("/personas", ["Parzibyte\Controladores\ControladorPersonas", "obtener"])
                        ->post("/persona", ["Parzibyte\Controladores\ControladorPersonas", "agregar"])
                        ->put("/persona", ["Parzibyte\Controladores\ControladorPersonas", "actualizar"])
                        ->delete("/persona/{idPersona}", ["Parzibyte\Controladores\ControladorPersonas", "eliminar"])
                        ->get("/pagos", ["Parzibyte\Controladores\ControladorPagos", "obtener"])
                        ->post("/pago", ["Parzibyte\Controladores\ControladorPagos", "agregar"])
                        ->put("/pago", ["Parzibyte\Controladores\ControladorPagos", "actualizar"])
                        ->delete("/pago/{idPago}", ["Parzibyte\Controladores\ControladorPagos", "eliminar"])
                        ->get("/ticket/{idPago}", ["Parzibyte\Controladores\ControladorTickets", "imprimirTicketDePago"]);
                });

        });

$despachador = new Dispatcher($enrutador->getData());
$rutaCompleta = $_SERVER["REQUEST_URI"];
$metodo = $_SERVER['REQUEST_METHOD'];
$rutaLimpia = parsearUrl($rutaCompleta);
try {
    $despachador->dispatch($metodo, $rutaLimpia);
} catch (HttpRouteNotFoundException $e) {
    echo "Error: Ruta [ $rutaLimpia ] no encontrada";
} catch (HttpMethodNotAllowedException $e) {
    echo "Error: Ruta [ $rutaLimpia ] encontrada pero método [ $metodo ] no permitido";
}
function parsearUrl($uri)
{
    return implode('/',
        array_slice(
            explode('/', $uri), Comun::env("OFFSET_RUTAS", 2)));
}
