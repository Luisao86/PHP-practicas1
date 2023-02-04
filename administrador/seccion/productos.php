<?php include("../template/cabecera.php"); ?>

<?php 

$txtid=(isset($_POST['txtID']))?($_POST['txtID']):"";
$txtNombre=(isset($_POST['txtNombre']))?($_POST['txtNombre']):"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?($_FILES['txtImagen']['name']):"";
$accion=(isset($_POST['accion']))?($_POST['accion']):"";

include("../config/db.php");



switch ($accion) {
    case 'Agregar':
        $sentenciaSQL= $conexion->prepare("INSERT INTO productos (nombre, imagen) VALUES (:nombre,:imagen);");
        $sentenciaSQL->bindParam(':nombre',$txtNombre);

        $fecha= new DateTime();
        $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];   

        if($tmpImagen!=""){
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
        }

        $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
        $sentenciaSQL->execute();
        break;

    case 'Modificar':

        $sentenciaSQL= $conexion->prepare("UPDATE productos SET nombre=:nombre WHERE ID=:ID");
        $sentenciaSQL->bindParam(':nombre',$txtNombre);
        $sentenciaSQL->bindParam(':ID',$txtid);
        $sentenciaSQL->execute();

        if($txtImagen!=""){ 

            //Seleccion de archivo
            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];   
            
            //copia y pega archivo
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
            
            //borra imagen anterior
            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM productos WHERE ID=:ID");
            $sentenciaSQL->bindParam(':ID',$txtid);
            $sentenciaSQL->execute();
            $producto=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

                if(isset($producto["imagen"])&&($producto["imagen"]!="imagen.jpg") ){
            
                    if(file_exists("../../img/".$producto["imagen"])){

                        unlink("../../img/".$producto["imagen"]);
                    }
                }


            $sentenciaSQL= $conexion->prepare("UPDATE productos SET imagen=:imagen WHERE ID=:ID");
            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->bindParam(':ID',$txtid);
            $sentenciaSQL->execute();
        }

        break;

    case 'Cancelar':
        echo "Presionado boton cancelar";
        break;

    case 'Seleccionar':

        $sentenciaSQL= $conexion->prepare("SELECT * FROM productos WHERE ID=:ID");
        $sentenciaSQL->bindParam(':ID',$txtid);
        $sentenciaSQL->execute();
        $producto=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre=$producto['nombre'];
        $txtImagen=$producto['imagen'];

        break;

    case 'Borrar':

        $sentenciaSQL= $conexion->prepare("SELECT imagen FROM productos WHERE ID=:ID");
        $sentenciaSQL->bindParam(':ID',$txtid);
        $sentenciaSQL->execute();
        $producto=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if(isset($producto["imagen"])&&($producto["imagen"]!="imagen.jpg") ){
            
            if(file_exists("../../img/".$producto["imagen"])){

                unlink("../../img/".$producto["imagen"]);
            }
        }


        $sentenciaSQL= $conexion->prepare("DELETE FROM productos WHERE ID=:ID");
        $sentenciaSQL->bindParam(':ID',$txtid);
        $sentenciaSQL->execute();
        break;
}

    $sentenciaSQL= $conexion->prepare("SELECT * FROM productos");
    $sentenciaSQL->execute();
    $listaProductos=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
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
                <input type="text" class="form-control" value="<?php echo $txtid;?>" name="txtID" id="txtID" placeholder="ID">
            </div>

            <div class = "form-group">
                <label for="exampleInputEmail1">Nombre:</label>
                <input type="text" class="form-control" value="<?php echo $txtNombre;?>" name="txtNombre" id="txtNombre" placeholder="Nombre del producto">
            </div>

            <div class = "form-group">
                <label for="exampleInputEmail1">Imagen:</label>
                
                <br/>

                <?php if($txtImagen!=""){ ?>

                    <img  class="img-thumbnail rounded"  src="../../img/<?php echo $txtImagen;?>" width="50px" alt="" srcset="">

                <?php } ?>
                
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
            <?php foreach($listaProductos as $producto) { ?> 
            <tr>
                <td><?php echo $producto['ID']?></td>
                <td><?php echo $producto['nombre']?></td>
                <td>
                    <img class="img-thumbnail rounded" src="../../img/<?php echo $producto['imagen']?>" width="50px" alt="" srcset="">
                </td>
                
                <td>
                    
                    <form method="POST">
                        
                        
                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $producto['ID'];?>">
                        
                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary">
                        <input type="submit" name="accion" value="Borrar" class="btn btn-danger">

                    </form>
                           
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>

</div>




<?php include("../template/pie.php"); ?>