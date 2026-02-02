<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_usuario'])) { header("Location: login.php"); exit(); }
$mi_id = $_SESSION['id_usuario'];
$msg = "";
$tipo_msg = "";

// ACTUALIZAR DATOS PERSONALES
if (isset($_POST['update_info'])) {
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $conn->query("UPDATE usuarios SET apellido='$apellido', email='$email' WHERE id=$mi_id");
    $msg = "Datos actualizados correctamente.";
    $tipo_msg = "success";
}

//  CAMBIAR CONTRASEÑA (Seguridad Alta)
if (isset($_POST['change_pass'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];

    // Buscamos la contraseña actual en la BD
    $res = $conn->query("SELECT password FROM usuarios WHERE id=$mi_id");
    $row = $res->fetch_assoc();

    // Verificamos si la contraseña vieja es correcta
    if (password_verify($old_pass, $row['password'])) {
        if (strlen($new_pass) >= 6) {
            $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $conn->query("UPDATE usuarios SET password='$new_hash' WHERE id=$mi_id");
            $msg = "¡Contraseña cambiada con éxito!";
            $tipo_msg = "success";
        } else {
            $msg = "La nueva contraseña debe tener 6 caracteres mínimo.";
            $tipo_msg = "error";
        }
    } else {
        $msg = "La contraseña actual no es correcta.";
        $tipo_msg = "error";
    }
}

// Cargar datos actuales para mostrarlos en los inputs
$usuario = $conn->query("SELECT * FROM usuarios WHERE id=$mi_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-login"> <div class="container" style="max-width:500px;">
        <div class="form-card">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h2> Mi Perfil</h2>
                <a href="index.php" class="btn-edit"> Volver</a>
            </div>

            <?php if($msg): ?>
                <div class="msg <?php echo $tipo_msg; ?>"><?php echo $msg; ?></div>
            <?php endif; ?>

            <form method="POST" style="margin-bottom:30px;">
                <h3>Mis Datos</h3>
                <div class="form-row">
                    <label style="width:100px;">Usuario:</label>
                    <input type="text" value="<?php echo $usuario['username']; ?>" disabled style="opacity:0.5; cursor:not-allowed;">
                </div>
                <div class="form-row">
                    <label style="width:100px;">Apellido:</label>
                    <input type="text" name="apellido" value="<?php echo $usuario['apellido']; ?>" required>
                </div>
                <div class="form-row">
                    <label style="width:100px;">Email:</label>
                    <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required>
                </div>
                <button type="submit" name="update_info" class="btn-main">Guardar Datos</button>
            </form>

            <hr style="border-color:#444;">

            <form method="POST">
                <h3 style="color:var(--danger);"> Seguridad</h3>
                <div class="input-group" style="margin-bottom:10px;">
                    <input type="password" name="old_pass" placeholder="Contraseña Actual" required>
                </div>
                <div class="input-group" style="margin-bottom:10px;">
                    <input type="password" name="new_pass" placeholder="Nueva Contraseña (Mín 6)" required>
                </div>
                <button type="submit" name="change_pass" class="btn-sec" style="border:1px solid var(--primary); color:var(--primary);">
                    Cambiar Contraseña
                </button>
            </form>
        </div>
    </div>
</body>
</html>