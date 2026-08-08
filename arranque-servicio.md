🚀 Flujo de Arranque Diario (3 Ventanas de Terminal)
Para tener el sistema funcionando al 100% necesitas abrir 3 pestañas de terminal:

1️⃣ Terminal 1 — Base de Datos (MariaDB)
En PowerShell o Git Bash:

Bash
mysqld --console
(Déjala abierta. Sabrás que está lista cuando leas: ready for connections)

2️⃣ Terminal 2 — Servidor Web (Laravel)
En Git Bash:

Bash
cd /c/Users/Adria/Desktop/Factory-Cards
php artisan serve
(Déjala abierta. Tu web estará lista localmente en [http://127.0.0.1:8000](http://127.0.0.1:8000))

3️⃣ Terminal 3 — Túnel Público HTTPS (Cloudflare)
En Git Bash:

Bash
cloudflared tunnel --url http://127.0.0.1:8000
(Copia la URL [https://...trycloudflare.com](https://...trycloudflare.com) que te imprimirá en pantalla para enviarla o abrirla en el móvil)

🔄 Comandos Útiles para Reiniciar / Resetear la Web
🛠️ Reconstruir la Base de Datos desde 0
Si quieres borrar todo y volver a cargar los 60 productos, 11 franquicias, categorías y eventos limpios:

Bash
cd /c/Users/Adria/Desktop/Factory-Cards
php artisan migrate:fresh --seed
🧹 Limpiar Caché (Si algo no se refresca o ves un fallo raro)
Bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
📂 Subir Cambios a Git (Recordatorio con la regla en español)
Bash
git add .
git commit -m "feat: Descripción de los cambios en español"
git push