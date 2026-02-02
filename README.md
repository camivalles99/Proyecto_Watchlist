# Proyecto_Watchlist - Gestor de Películas y Series

**Proyecto Final Full Stack** Una aplicación web completa para gestionar tus películas y series pendientes, llevar un control del progreso y mantener un historial de lo que ya has visto.

## Descripción

Este proyecto es una plataforma web desarrollada con PHP y MySQL que permite a los usuarios registrarse, iniciar sesión y gestionar su propia lista de seguimiento de series y películas (Watchlist) de manera privada. 

La aplicación diferencia entre películas (controlando horas/minutos) y series (controlando temporada/episodio), permitiendo editar el progreso en tiempo real.

## Funcionalidades Principales

* **Autenticación Segura:**
    * Login y Registro de usuarios.
    * Encriptación de contraseñas (`password_hash`).
    * Validación de seguridad (mínimo de caracteres, usuarios únicos).
* **Gestión de Contenidos (CRUD):**
    * Añadir películas o series a "Pendientes" o directamente a "Vistas".
    * Sistema inteligente de progreso: Campos dinámicos según si es Serie (Temp/Cap) o Película (Horas/Min).
    * Actualizar/Editar el punto exacto donde dejaste la película o serie.
    * Mover de "Pendiente" a "Visto" y viceversa (Restaurar).
    * Eliminar contenido.
* **Perfil de Usuario:**
    * Modificación de datos personales (Nombre, Apellidos, Email).
    * Cambio de contraseña seguro (verificando la actual).
* **Interfaz Moderna:**
    * Diseño "Dark Mode" con paleta de colores Neón (Violeta/Verde).
    * Diseño Responsive (adaptable a móviles).
    * Separación visual clara entre listas "Pendientes" e "Historial".

## Tecnologías Utilizadas

* **Frontend:** HTML5, CSS3, JavaScript.
* **Backend:** PHP.
* **Base de Datos:** MySQL.
* **Servidor Local:** XAMPP (Apache).

## Instalación y Despliegue

1.  **Clonar/Descargar:**
    Descarga este repositorio y coloca la carpeta en el directorio `htdocs` de tu instalación de XAMPP (normalmente `C:\xampp\htdocs`).

2.  **Base de Datos:**
    * Abre **XAMPP** e inicia los servicios Apache y MySQL.
    * Entra en [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
    * Crea una nueva base de datos llamada `Proyecto_Watchlist`.
    * Importa el archivo `database.sql` que encontrarás en la carpeta raíz de este proyecto.

3. **Ejecutar:**
    * Abre tu navegador y entra en: `http://localhost/Proyecto_Watchlist`

## Estructura del Proyecto

* `index.php`: Dashboard principal (Listas y formularios).
* `login.php`: Pantalla de acceso y registro.
* `editar.php`: Pantalla para actualizar el progreso de una serie/pelíucla.
* `perfil.php`: Gestión de cuenta de usuario.
* `db.php`: Conexión a la base de datos.
* `style.css`: Estilos visuales.
* `script.js`: Lógica del frontend (mostrar/ocultar campos).
* `proyecto_watchlist.sql`: Script de creación de las tablas.


