<?php
session_start();
include 'db.php';


if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit(); }
$mi_id = $_SESSION['id_usuario'];

// GUARDAR NUEVO
if (isset($_POST['guardar'])) {
    $titulo = $_POST['titulo'];
    $plataforma = $_POST['plataforma'];
    $tipo = $_POST['tipo'];
    $hora = !empty($_POST['hora']) ? $_POST['hora'] : 0;     // Corrección para evitar error si está vacío
    $minuto = !empty($_POST['minuto']) ? $_POST['minuto'] : 0;
    $temporada = ($tipo == 'serie') ? $_POST['temporada'] : 0;
    $episodio = ($tipo == 'serie') ? $_POST['episodio'] : 0;
    $estado = $_POST['estado']; 

    $sql = "INSERT INTO contenidos (id_usuario, titulo, plataforma, tipo, hora, minuto, temporada, episodio, estado) 
            VALUES ('$mi_id', '$titulo', '$plataforma', '$tipo', '$hora', '$minuto', '$temporada', '$episodio', '$estado')";
    $conn->query($sql);
    header("Location: index.php");
}

// MOVER A VISTO / BORRAR / RESTAURAR
if (isset($_GET['id']) && isset($_GET['accion'])) {
    $id = $_GET['id'];
    $accion = $_GET['accion'];

    if ($accion == 'completar') {
        $conn->query("UPDATE contenidos SET estado='vista' WHERE id=$id AND id_usuario=$mi_id");
    }
    if ($accion == 'restaurar') {
        $conn->query("UPDATE contenidos SET estado='pendiente' WHERE id=$id AND id_usuario=$mi_id");
    }
    if ($accion == 'borrar') {
        $conn->query("DELETE FROM contenidos WHERE id=$id AND id_usuario=$mi_id");
    }
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel - Watchlist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="logo">
            Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 
        </div>
        
        <div style="display:flex; gap:10px;">
            <a href="perfil.php" class="btn-logout" style="border-color:var(--primary); color:var(--primary);"> Mi Perfil</a>
            <a href="login.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>
    <div class="container">
        
        <section class="add-section">
            <form action="index.php" method="POST" class="form-card">
                <h3>Añadir Contenido</h3>
                
                <div class="form-row">
                    <input type="text" name="titulo" placeholder="Título (ej. Inception)" required>
                    <select name="plataforma">
                        <option>Netflix</option><option>HBO Max</option><option>Prime</option><option>Disney+</option><option>Cine</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <select name="tipo" id="selectorTipo">
                        <option value="pelicula">Película </option>
                        <option value="serie">Serie </option>
                    </select>
                    
                    <div class="time-inputs">
                        <input type="number" name="hora" placeholder="H" min="0"> :
                        <input type="number" name="minuto" placeholder="Min" min="0" max="59">
                    </div>
                </div>

                <div class="form-row hidden" id="camposSerie">
                    <input type="number" name="temporada" placeholder="Temp.">
                    <input type="number" name="episodio" placeholder="Cap.">
                </div>

                <div class="form-row">
                    <label style="color:#aaa; align-self:center;">Estado:</label>
                    <select name="estado">
                        <option value="pendiente"> Para ver luego</option>
                        <option value="vista"> Ya terminada</option>
                    </select>
                </div>

                <button type="submit" name="guardar" class="btn-main">Guardar Contenido</button>
            </form>
        </section>

        <section class="dashboard-grid">
            
            <div class="column">
                <h2 class="col-title"> Pendientes</h2>
                <div class="scroll-area">
                    <?php
                    $res = $conn->query("SELECT * FROM contenidos WHERE id_usuario=$mi_id AND estado='pendiente' ORDER BY id DESC");
                    if ($res->num_rows > 0) {
                        while($row = $res->fetch_assoc()) {
                            
                            $icono = ($row['tipo'] == 'serie') ? '📺' : '🎬'; 
                            

                            echo "<div class='card-item'>";
                            echo "<div class='card-info'>";
                            echo "<h4>$icono " . $row['titulo'] . "</h4>";
                            echo "<p class='meta'>" . $row['plataforma'] . " • ";
                            
                            if ($row['tipo'] == 'serie') echo "T" . $row['temporada'] . ":E" . $row['episodio'] . " • ";
                            
                            $tiempo = "";
                            if ($row['hora'] > 0) $tiempo .= $row['hora'] . "h ";
                            $tiempo .= $row['minuto'] . "m";
                            echo "Punto: " . $tiempo;
                            
                            echo "</p></div>";
                            
                            echo "<div class='card-actions'>";
                            echo "<a href='editar.php?id=" . $row['id'] . " ' class='btn-edit' title='Actualizar progreso'> Actualizar </a>";
                            echo "<a href='index.php?accion=completar&id=" . $row['id'] . "' class='btn-edit' title='Ya vista'> Visto </a>";
                
                            echo "</div></div>";
                        }
                    } else {
                        echo "<p class='empty-msg'>¡Estás al día! Añade algo nuevo.</p>";
                    }
                    ?>
                </div>
            </div>
            
            <div class="column done-column">
                <h2 class="col-title"> Historial Visto</h2>
                <div class="scroll-area">
                    <?php
                    $res = $conn->query("SELECT * FROM contenidos WHERE id_usuario=$mi_id AND estado='vista' ORDER BY id DESC");
                    while($row = $res->fetch_assoc()) {
                        echo "<div class='card-item done'>";
                        echo "<div style='flex:1;'>"; 
                        echo "<strong>" . $row['titulo'] . "</strong>";
                        echo "<br><small style='color:#666'>" . $row['plataforma'] . "</small>";
                        echo "</div>";

                        echo "<div class='card-actions'>";
                        echo "<a href='index.php?accion=restaurar&id=" . $row['id'] . "' class='btn-edit' title='Volver a Pendientes'> Pendiente </a>";
                        echo "<a href='index.php?accion=borrar&id=" . $row['id'] . "' class='btn-mini-trash' title='Eliminar'> Borrar </a>";
                        echo "</div></div>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>
    <script src="script.js"></script>
</body>
</html>
