<?php
session_start();
include 'db.php';

// Seguridad: Si no hay login, fuera
if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit(); }
$mi_id = $_SESSION['id_usuario'];

$mensaje = "";

// --- LÓGICA PARA GUARDAR LOS CAMBIOS (UPDATE) ---
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    
    // Recogemos los datos del formulario
    $hora = !empty($_POST['hora']) ? $_POST['hora'] : 0;
    $minuto = !empty($_POST['minuto']) ? $_POST['minuto'] : 0;
    $temporada = !empty($_POST['temporada']) ? $_POST['temporada'] : 0;
    $episodio = !empty($_POST['episodio']) ? $_POST['episodio'] : 0;
    
    // Ejecutamos la actualización en la Base de Datos
    $sql = "UPDATE contenidos SET 
            hora = '$hora', 
            minuto = '$minuto', 
            temporada = '$temporada', 
            episodio = '$episodio' 
            WHERE id = '$id' AND id_usuario = '$mi_id'";

    if ($conn->query($sql) === TRUE) {
        // Si sale bien, volvemos a la página principal
        header("Location: index.php");
        exit();
    } else {
        $mensaje = "Error al actualizar: " . $conn->error;
    }
}

// --- LÓGICA PARA OBTENER LOS DATOS ACTUALES ---
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Buscamos el contenido para rellenar los inputs
    $res = $conn->query("SELECT * FROM contenidos WHERE id='$id' AND id_usuario='$mi_id'");
    
    if ($res->num_rows > 0) {
        $item = $res->fetch_assoc();
    } else {
        header("Location: index.php"); // Si no existe, fuera
        exit();
    }
} else {
    header("Location: index.php"); // Si no hay ID, fuera
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar - Watchlist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-login"> <div class="login-container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0; color:white;"> Editar</h2>
            <a href="index.php" class="btn-volver">↩ Volver</a>
        </div>

        <h3 style="color:var(--primary); margin-top:0;">
            <?php echo $item['titulo']; ?> 
            <small style="color:#aaa; font-weight:normal;">(<?php echo $item['plataforma']; ?>)</small>
        </h3>
        
        <?php if($mensaje) echo "<p class='msg error'>$mensaje</p>"; ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
            
            <?php if($item['tipo'] == 'serie'): ?>
                <div class="form-row">
                    <div style="flex:1">
                        <label style="color:#aaa; font-size:0.8rem;">Temporada</label>
                        <input type="number" name="temporada" value="<?php echo $item['temporada']; ?>">
                    </div>
                    <div style="flex:1">
                        <label style="color:#aaa; font-size:0.8rem;">Episodio</label>
                        <input type="number" name="episodio" value="<?php echo $item['episodio']; ?>">
                    </div>
                </div>
            <?php endif; ?>

            <label style="color:#aaa; font-size:0.8rem; display:block; margin-bottom:5px;">Tiempo visto:</label>
            <div class="form-row" style="align-items:center;">
                <input type="number" name="hora" value="<?php echo $item['hora']; ?>" min="0" placeholder="H">
                <span style="color:white; font-weight:bold;">:</span>
                <input type="number" name="minuto" value="<?php echo $item['minuto']; ?>" min="0" max="59" placeholder="Min">
            </div>

            <button type="submit" name="actualizar" class="btn-main" style="margin-top:20px;">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>