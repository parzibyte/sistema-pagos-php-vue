; <?php exit; ?>
; El comentario de arriba es para que, si el archivo es visto
; desde el navegador, se salga inmediatamente del script, ocultando
; la información que aquí existe

; En cambio, cuando es tratado como un archivo .ini, las
; líneas que comienzan con un ; son ignoradas


; Un archivo de configuración
; que guarda todas las credenciales
; para cada cosa

; Las líneas en blanco y aquellas que comienzan
; con un punto y coma (;) son ignoradas

; URL base del proyecto, algo como https://sitio.com
URL_RAIZ = "sitio.com"


USUARIO_MYSQL = "root"
PASS_MYSQL = ""
NOMBRE_BD_MYSQL = "ejemplo"
HOST_MYSQL = "localhost"
NOMBRE_IMPRESORA = "POS-58"

;El offset para las rutas. Simplemente hay que contar el número
; de barras (/) desde la raíz
; Por ejemplo, si el index.php está en localhost/app/index.php
; el offset sería 2
; si estuviera en localhost/app/otro_dir/index.php
; el offset sería 3
; si estuviera en https://parzibyte.me/apps/app/index.php
; el offset sería 3
OFFSET_RUTAS = 2