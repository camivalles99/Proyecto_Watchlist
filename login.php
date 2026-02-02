<?php
session_start();
include 'db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- REGISTRO ---
    if (isset($_POST['registro'])) {
        $user = trim($_POST['username']);
        $apellido = trim($_POST['apellido']);
        $email = trim($_POST['email']);
        $pass = $_POST['password'];

        if (strlen($pass) < 6) {
            $error = "La contraseña debe tener al menos 6 caracteres.";
        } 
        elseif ($conn->query("SELECT id FROM usuarios WHERE username='$user'")->num_rows > 0) {
            $error = "El usuario '$user' ya existe.";
        } 
        else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (username, apellido, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $user, $apellido, $email, $hash);
            
            if ($stmt->execute()) {
                $success = "¡Cuenta creada! Sube arriba para entrar.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
      
    // --- LOGIN ---
    elseif (isset($_POST['login'])) {
        $user = trim($_POST['username']); 
        $pass = $_POST['password'];
        
        $result = $conn->query("SELECT * FROM usuarios WHERE username='$user'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['password'])) {
                $_SESSION['usuario'] = $user;
                $_SESSION['id_usuario'] = $row['id'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Watchlist Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-login">
    <div class="login-container">
        <h1> Watchlist Pro</h1>
        
        <?php if($error): ?><div class="msg error"><?php echo $error; ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg success"><?php echo $success; ?></div><?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" name="login" class="btn-main">ENTRAR</button>
        </form>

        <div class="separator">
            <span>¿Nuevo por aquí?</span>
        </div>

        <form method="POST">
            <input type="text" name="username" placeholder="Nombre de Usuario" required>
            <input type="text" name="apellido" placeholder="Apellidos" required>
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Crea tu Contraseña" required>
            
            <button type="submit" name="registro" class="btn-sec">CREAR CUENTA</button>
        </form>
    </div>
</body>
</html>