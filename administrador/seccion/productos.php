<?php include("../template/cabecera.php"); ?>

<?php 

$txtid=(isset($_POST['txtID']))?($_POST['txtID']):"";
$txtNombre=(isset($_POST['txtNombre']))?($_POST['txtNombre']):"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?($_FILES['txtImagen']['name']):"";
$accion=(isset($_POST['accion']))?($_POST['accion']):"";

include("../config/db.php");



switch ($accion) {
    case 'Agregar':
        $sentenciSQL= $conexion->prepare("INSERT INTO productos (nombre, imagen) VALUES (:nombre,:imagen);");
        $sentenciSQL->bindParam(':nombre',$txtNombre);
        $sentenciSQL->bindParam(':imagen',$txtImagen);
        $sentenciSQL->execute();
        break;

    case 'Modificar':
        echo "Presionado boton modificar";
        break;

    case 'Cancelar':
        echo "Presionado boton cancelar";
        break;
}

?>



<div class="col-md-5">

    <div class="card">
        <div class="card-header">
            Datos del producto
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">

            <div class = "form-group">
                <label for="exampleInputEmail1">ID:</label>
                <input type="text" class="form-control" name="txtID" id="txtID" placeholder="ID">
            </div>

            <div class = "form-group">
                <label for="exampleInputEmail1">Nombre:</label>
                <input type="text" class="form-control" name="txtNombre" id="txtNombre" placeholder="Nombre del producto">
            </div>

            <div class = "form-group">
                <label for="exampleInputEmail1">Imagen:</label>
                <input type="file" name="txtImagen" id="txtImagen" placeholder="Imagen">
            </div>

            <div class="btn-group" role="group" aria-label="">
                <button type="submit" name="accion" value="Agregar" class="btn btn-success">Agregar</button>
                <button type="submit" name="accion" value="Modificar" class="btn btn-warning">Modificar</button>
                <button type="submit" name="accion" value="Cancelar" class="btn btn-info">Cancelar</button>
            </div>
</form>
        </div>
    </div>

   
    
    
</div>

<div class="col-md-7">
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>3</td>
                <td>sistema PHP</td>
                <td>Imagen.jpg</td>
                <td>Seleccionar | Borrar </td>
            </tr>
        </tbody>
    </table>

</div>




<?php include("../template/pie.php"); ?>