# vulnerable-demo-app

> **ADVERTENCIA:** Proyecto **intencionalmente vulnerable**, solo para fines educativos.
> **No desplegar en producción ni en internet.** Úsalo únicamente en `localhost`.

App web pequeña (PHP + SQLite) con fallos de seguridad puestos a propósito para practicar auditorías con la [Security Audit Skill de Cloudflare](https://github.com/cloudflare/skills) (u otras herramientas de revisión de código).

## Requisitos

- PHP 8+ con extensión PDO SQLite (incluido en XAMPP)
- Navegador
- Ejecutar **solo en local** (por ejemplo XAMPP)

## Cómo arrancar (local)

1. Clona el repo dentro de tu carpeta web, por ejemplo:
   ```bash
   git clone https://github.com/cchourio/vulnerable-demo-app.git
   ```
2. Con Apache/PHP activos, abre:
   ```
   http://localhost/vulnerable-demo-app/init_db.php
   ```
3. Luego ve a:
   ```
   http://localhost/vulnerable-demo-app/
   ```

### Credenciales demo (falsas)

| Usuario | Contraseña |
|---------|------------|
| `admin` | `admin123` |
| `maria`  | `password` |

No son credenciales reales. No uses estas claves en ningún sistema de verdad.

## Fallos incluidos a propósito

| Área | Archivo | Problema |
|------|---------|----------|
| Secretos en código | `config.php` | API key y tokens hardcodeados (inventados) |
| Login | `login.php` | SQL injection + sin límite de intentos |
| Búsqueda | `search.php` | SQL injection por concatenación |
| Saludo | `index.php` | XSS reflejado |
| Comentarios | `comments.php` | XSS almacenado |
| Admin | `admin.php` | Muestra secretos y passwords en texto plano |

La idea es que una skill o auditoría los detecte; **no** que alguien ataque un servidor real.

## Qué NO hacer

- No subir esto a cPanel, VPS, hosting compartido ni Cloudflare Pages/Workers públicos
- No meter secretos reales (`.env`, cPanel, API keys de verdad)
- No quitar el banner de advertencia si vas a compartir el repo

## Rama `fixed` (opcional para el video)

En el video puedes crear una rama `fixed` con las correcciones (prepared statements, escape de salida, hashing de passwords, rate limiting, secretos fuera del código) para mostrar el antes y el después.

```bash
git checkout -b fixed
# ... aplicar correcciones ...
git push -u origin fixed
```

## Licencia / uso

Material educativo. Úsalo para aprender a **auditar y endurecer** código, no para atacar sistemas ajenos.
