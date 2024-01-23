<html>
<body>
<form method="post" action="<?php $_SERVER['PHP_SELF']?>">
    <label for="correo">Correo:</label>
    <input type="email" id="correo" name="correo" required>

    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="contrasena" required>

    <label for="archivo">Archivo JPG o PDF:</label>
    <input type="file"  name="archivo" accept=".jpg, .jpeg, .pdf" required>

    <button type="submit">Enviar</button>
</form>

<?php
if($_SERVER['REQUEST_METHOD']=='POST'){
    // Recibir datos del formulario
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $archivoNombre = $_POST['archivo'];
    $archivoTipo = $_POST['archivo'];

    // Conexión a la base de datos (ajusta las credenciales según tu configuración)
    $conn = new mysqli("localhost","root","","ejercicio");

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }

    // Validar el tipo de archivo (puedes agregar más validaciones según tus necesidades)
    if ($archivoTipo != 'image/jpeg' && $archivoTipo != 'application/pdf') {
        die("Error: El archivo debe ser de tipo JPG o PDF.");
    }

    // Mover el archivo al directorio deseado (ajusta la ruta según tu configuración)
    $archivoDestino = "archivos/" . $archivoNombre;
    move_uploaded_file($_FILES['archivo']['tmp_name'], $archivoDestino);

    // Insertar datos en la tabla MySQL
    $sql = "INSERT INTO usuario (correo, contrasena, archivo_jpg) VALUES ('$correo', '$contrasena', '$archivoDestino')";
    $conn->query($sql);

    if ($conn->query($sql) === TRUE) {
        echo "Datos guardados correctamente.";
    } else {
        echo "Error al guardar los datos: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>
</body>
</html>
