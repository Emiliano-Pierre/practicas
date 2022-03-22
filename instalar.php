<?php 

$config = include 'config.php';
try{
$conexion = new PDO('mysql:host=' . $config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['options']);

//realizamos la consulta a la BD
$sql = file_get_contents('data/migracion.sql');
//ejecutamos la consulta 
$conexion->exec($sql);
echo "La base de datos y la tabla alumnos se han creado exitosamente";
} catch(PDOException $error){
    echo $error->getMessage();
}
?>