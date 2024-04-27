 <?php


  require_once "controllers/Municipio_Controller.php";
  require_once "controllers/Paciente_Controller.php";

  // Insertar registro de municipio
  if (isset($_POST["ok"])) {

    if (empty($_POST["nombre"])) {
      echo "
        <div id='message' class='alert alert-danger' role='alert'>
          El nombre del municipio es obligatorio
        </div>
      ";
    } else {
      $municipio = Municipio_Controller::insert_municipio($_POST["nombre"]);

      if ($municipio["ok"]) {
        $id = $municipio["data"][0]->id;
        $nombre = $municipio["data"][0]->nombre;

        echo "
        <div id='message' class='alert alert-success' role='alert'>
          municipio creada correctamente: 
          <ul>
            <li>$id</li>
            <li>$nombre</li>
          </ul>
        </div>
      ";
      } else {
        echo $municipio["error_message"];
      }
    }
  }

  // Actualizar registro de municipio
  if (isset($_POST["update_ok"])) {
    $info_municipio = json_decode($_POST["update_ok"]);

    if (empty($_POST["updated_municipio"])) {
      echo "
        <div id='message' class='alert alert-danger' role='alert'>
          El nombre actualizado de la municipio es obligatorio
        </div>
      ";
    } else {

      if ($info_municipio->nombre != $_POST["updated_municipio"]) {
        $municipio = Municipio_Controller::update_municipio(
          $info_municipio->id,
          $_POST["updated_municipio"]
        );

        if ($municipio["ok"]) {
          $id = $municipio["data"][0]->id;
          $nombre = $municipio["data"][0]->nombre;

          echo "
            <div id='message' class='alert alert-success' role='alert'>
              Municipio creado correctamente: 
              <ul>
                <li>$id</li>
                <li>$nombre</li>
              </ul>
            </div>
          ";
        } else {
          echo $municipio["error_message"];
        }
      }
    }
  }

  // Eliminar registro de municipio
  if (isset($_POST["eliminar"])) {

    $municipio_eliminada = Municipio_Controller::delete_municipio_by_id($_POST["eliminar"]);

    if ($municipio_eliminada["ok"]) {
      echo "
        <div id='message' class='alert alert-success' role='alert'>
          Registro de municipio eliminado correctamente
        </div>
      ";
    }
  }
  ?>


 <div class="container">
   <h1>Formulario de municipio</h1>
   <form method="post" enctype="multipart/form-data">
     <div class="mb-3">
       <label for="nombre" class="form-label">Nombre</label>
       <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese su nombre">
     </div>
     <button type="submit" class="btn btn-primary" name="ok">Enviar</button>
   </form>
 </div>
 <div class="container">
   <h1>Tabla de municipios</h1>
   <table class="table">
     <thead>
       <tr>
         <th>Acción</th>
         <th scope="col">ID</th>
         <th scope="col">Nombre</th>
       </tr>
     </thead>
     <tbody id="tabla-municipios">
       <?php $municipios = Municipio_Controller::get_municipios(); ?>
       <?php if ($municipios["ok"]) :  ?>
         <?php foreach ($municipios["data"] as $municipio) : ?>
           <tr>
             <td class="d-flex gap-2">
               <form method="post">
                 <button class="btn btn-danger" name="eliminar" value="<?= $municipio->id ?>">
                   Eliminar
                 </button>
               </form>
               <button type="button" class="btn btn-primary" id="actualizar" data-bs-toggle="modal" data-bs-target="#modalFormulario" data-info-municipio='<?= json_encode($municipio) ?>'>
                 Actualizar
               </button>
             </td>
             <td><?= $municipio->id ?></td>
             <td><?= $municipio->nombre ?></td>
           </tr>
         <?php endforeach; ?>
       <?php endif; ?>

     </tbody>
   </table>
 </div>
 <div class="modal fade" id="modalFormulario" tabindex="-1" aria-labelledby="modalFormularioLabel" aria-hidden="true">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="modalFormularioLabel">Actualizar el nombre de municipio</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
         <!-- Formulario -->
         <form method="post">
           <div class="mb-3">
             <label for="nombremunicipio" class="form-label">Nombre de la municipio:</label>
             <input type="text" class="form-control" id="nombremunicipio" placeholder="Ingrese el nombre de la municipio" name="updated_municipio">
           </div>
           <button type="submit" class="btn btn-primary" id="update_ok" name="update_ok">Enviar</button>
         </form>
       </div>
     </div>
   </div>
 </div>

 <script>
   const message = document.getElementById("message");
   const btnActualizarMunicipio = document.getElementById("actualizar")
   const btnOKActualizar = document.getElementById("update_ok")

   btnActualizarMunicipio.addEventListener('click', (e) => {
     const data = e.currentTarget.dataset.infoMunicipio
     btnOKActualizar.value = data
   })

   setTimeout(() => {
     message.remove()
   }, 2000);
 </script>