# vulnerable-demo-app

> **Nota:** este proyecto nació como app **intencionalmente vulnerable** para practicar auditorías con la [Security Audit Skill de Cloudflare](https://github.com/cloudflare/skills). La rama `main` ya incluye las correcciones; la versión vulnerable original está en el commit `7ec5132`.
> Sigue siendo material educativo: úsalo solo en `localhost`.

App web pequeña (PHP + SQLite).

## Requisitos

- PHP 8+ con extensión PDO SQLite (incluido en XAMPP)
- Apache con `AllowOverride All` (para que se apliquen los `.htaccess`)
- Navegador

## Cómo arrancar (local)

1. Clona el repo dentro de tu carpeta web, por ejemplo:
   ```bash
   git clone https://github.com/cchourio/vulnerable-demo-app.git
   ```
2. Inicializa la base de datos **desde la terminal** (por seguridad no funciona desde el navegador):
   ```bash
   php init_db.php
   ```
   El script muestra **una sola vez** las contraseñas aleatorias de `admin`, `maria` y `pedro`. Guárdalas.
   Para borrar y recrear la base: `php init_db.php --force`.
3. Abre:
   ```
   http://localhost/vulnerable-demo-app/
   ```

## Correcciones aplicadas

| Área | Archivo | Problema original | Corrección |
|------|---------|-------------------|------------|
| Login | `login.php` | SQL injection | Consulta preparada; contraseña comprobada con `password_verify` |
| Login | `login.php` | Sin límite de intentos | Máximo 5 fallos por IP cada 15 minutos (`login_attempts`) |
| Login | `login.php` | Fijación de sesión, errores SQL visibles | `session_regenerate_id(true)` y mensaje genérico (detalle a `error_log`) |
| Búsqueda | `search.php` | SQL injection por concatenación | Consulta preparada con `LIKE ... ESCAPE` |
| Saludo | `index.php` | XSS reflejado | Escape de salida con `e()` |
| Comentarios | `comments.php` | XSS almacenado | Escape de salida, límites de longitud y token CSRF |
| Admin | `admin.php` | Cualquier usuario veía secretos y contraseñas | `require_admin()` por rol; ya no se muestran contraseñas ni secretos |
| Base de datos | `init_db.php` | Reset anónimo desde la web y credenciales fijas | Solo CLI, no sobrescribe sin `--force`, contraseñas aleatorias con hash |
| Base de datos | `data/` | `demo.db` descargable desde la web | `data/.htaccess` con `Require all denied` |
| Secretos | `config.php` | API key y tokens en el código | Eliminados; los secretos reales van en variables de entorno |
| General | `config.php` | Cookies y cabeceras por defecto | Cookie `HttpOnly` + `SameSite=Lax` (+ `Secure` con HTTPS), modo estricto de sesión, CSP, `nosniff`, `X-Frame-Options` |
| Logout | `logout.php` | Cierre de sesión por GET | Solo POST con token CSRF |

## Qué NO hacer

- No subir esto a hosting público sin revisar de nuevo la configuración
- No meter secretos reales en el repositorio (`.env`, API keys, contraseñas)

## Licencia / uso

Material educativo. Úsalo para aprender a **auditar y endurecer** código, no para atacar sistemas ajenos.
