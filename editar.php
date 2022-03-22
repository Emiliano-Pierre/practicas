<?php 
 include 'funciones.php';
 
 $config = include 'config.php';

 $resultado =[
     'error' => false,
     'mensaje' =>''
 ];
//verificamos que el get si esta nulo
 if(!isset($_GET['id'])){
     $resultado['error'] = true;
     $resultado['mensaje'] = 'el alumno no existe';
 }
 // obtenemos la informaciÓN A TRAVES DEL ID=========================================
 try{//Nos conectamos a la base de datos
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    //recepcionamos la id del formulario(tabla de info mostrada) index.php
    $id = $_GET['id'];
    $consultaSQL = "SELECT * FROM alumnos WHERE id= " . $id;
    $sentencia = $conexion ->prepare($consultaSQL);
    $sentencia->execute();
    //almacenamos la información obtenida en $alumno para luego trasladar a editar.php en formulario nuevo
    $alumno = $sentencia->fetch(PDO::FETCH_ASSOC);
    //verificamos que la variable $alumno exista  para que cumpla de la condición
    if(!$alumno){
        $resultado['error'] =  true;
        $resultado['mensaje'] ='No se ha encontrado el alumno'; 
    }

 }catch(PDOException $error){
    $resultado['error'] = true;
    $resultado['mensaje']=$error->getMessage();
 }
 //FIN DE OBTENCIÓN DE DATOS BASE DE DATOS====================================================================================
 //Detectamos el parametro submit_editar - ACTUALIZACIÓN DE DATOS ===================================================
if(isset($_POST['submit_editar'])){
    try{//Nos conectamos a la base de datos
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
        //obtenemos los datos enviamos desde index.hph en un array consuulta SQL para la actualización de datos
        $alumno = [
            "id"        =>  $_GET['id'],
            "nombre"    =>  $_POST['nombre'],
            "apellido"  =>  $_POST['apellido'],
            "email"     =>  $_POST['email'],
            "edad"      =>  $_POST['edad']    
        ];
        $consultaSQL ="UPDATE alumnos SET
        nombre  =   :nombre,
        apellido=   :apellido,
        email   =   :email,
        edad    =   :edad,
        updated_at=  NOW()
        WHERE id =  :id";
        $consulta = $conexion ->prepare($consultaSQL);  
        $consulta->execute($alumno);
        //header("Location:index.php");
        
    }catch(PDOException $error){
        $resultado['error'] = true;
        $resultado['mensaje']= $error->getMessage();
    }
}
?>
<?php include "templates/header.php"?>
<!--Mostramos lo errores ocurridos al obtener el registro-->
<?php if($resultado['error']){   ?>
<div class="container mt-2">
    <div class="row"> 
        <div class="col-md-12">
            <div class="aler alert-danger" role="alert">
                <?=$resultado['mensaje']?>
            </div>
        </div>
    </div>
</div>
<?php }?>    
<!--FIN DE MOSTRAR ERRORES===================================================-->

<!--MENSAJE DE CONFIRMACIÓN MIENTRAS NO HAYA ERROR===================================================-->
<?php if(isset($_POST['submit_editar']) && !$resultado['error']){?>
    <div class="container mt-2">
        <div class="row">
            <div class="alert alert-success" role="alert">
                ¡El alumno ha sido actualizado correctamente!
            </div>
        </div>
    </div>
<?php }?>
<!--FIN DE MOSTRAR MENSAJE DE CONFIRMACIÓN====================================-->

<!--Formulario para la edición de datos   -- Editar.php-->
<?php if(isset($alumno) && $alumno){?>
    <div class="container">
        <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4">Editando al alumno <?= escapar($alumno['nombre']) .' '. escapar($alumno['apellido'])?></h2>
                    <hr>
                    <form method="post">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input class="form-control" type="text" id="nombre" name="nombre" value="<?=escapar($alumno['nombre'])?>">
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input class="form-control" type="text" id="apellido" name="apellido" value="<?=escapar($alumno['apellido'])?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" type="text" id="email" name="email" value="<?=escapar($alumno['email'])?>">
                        </div>
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input class="form-control" type="text" id="edad" name="edad" value="<?= escapar($alumno['edad'])?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit_editar" class="btn btn-primary" value="Actualizar">
                            <a href="index.php" class="btn btn-primary">Regresar al inicio</a>
                        </div>
                    </form>
                </div>  
        </div>
    </div>
<?php }?><!--FIN DE EDITAR ================================================================================-->
<?php include "templates/footer.php";?>