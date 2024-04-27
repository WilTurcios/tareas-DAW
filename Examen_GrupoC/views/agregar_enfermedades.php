 <?php


  require_once "controllers/enfermedad_controller.php";
  // Insertar registro de enfermedad
  if (isset($_POST["ok"])) {

    if (empty($_POST["nombre"])) {
      echo "
        <div id='message' class='alert alert-danger' role='alert'>
          El nombre de la enfermedad es obligatorio
        </div>
      ";
    } else {
      $Enfermedad = Enfermedad_Controller::insert_enfermedad($_POST["nombre"]);

      if ($Enfermedad["ok"]) {
        $id = $Enfermedad["data"][0]->id;
        $nombre = $Enfermedad["data"][0]->nombre;

        echo "
        <div id='message' class='alert alert-success' role='alert'>
          Enfermedad creada correctamente: 
          <ul>
            <li>$id</li>
            <li>$nombre</li>
          </ul>
        </div>
      ";
      } else {
        echo $Enfermedad["error_message"];
      }
    }
  }

  // Actualizar registro de enfermedad
  if (isset($_POST["update_ok"])) {
    $info_enfermedad = json_decode($_POST["update_ok"]);

    if (empty($_POST["updated_enfermedad"])) {
      echo "
        <div id='message' class='alert alert-danger' role='alert'>
          El nombre actualizado de la enfermedad es obligatorio
        </div>
      ";
    } else {

      if ($info_enfermedad->nombre != $_POST["updated_enfermedad"]) {
        $enfermedad = Enfermedad_Controller::update_enfermedad(
          $info_enfermedad->id,
          $_POST["updated_enfermedad"]
        );

        if ($enfermedad["ok"]) {
          $id = $enfermedad["data"][0]->id;
          $nombre = $enfermedad["data"][0]->nombre;

          echo "
            <div id='message' class='alert alert-success' role='alert'>
              Enfermedad creada correctamente: 
              <ul>
                <li>$id</li>
                <li>$nombre</li>
              </ul>
            </div>
          ";
        } else {
          echo $enfermedad["error_message"];
        }
      }
    }
  }

  // Eliminar registro de enfermedad
  if (isset($_POST["eliminar"])) {

    $enfermedad_eliminada = Enfermedad_Controller::delete_enfermedad_by_id($_POST["eliminar"]);

    if ($enfermedad_eliminada["ok"]) {
      echo "
        <div id='message' class='alert alert-success' role='alert'>
          Registro de enfermedad eliminado correctamente
        </div>
      ";
    }
  }
  ?>


 <div class="container">
   <h1>Formulario de Enfermedad</h1>
   <form method="post" enctype="multipart/form-data">
     <div class="mb-3">
       <label for="nombre" class="form-label">Nombre</label>
       <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese su nombre">
     </div>
     <button type="submit" class="btn btn-primary" name="ok">Enviar</button>
   </form>
 </div>
 <div class="container">
   <h1>Tabla de Enfermedades</h1>
   <table class="table">
     <thead>
       <tr>
         <th>Acción</th>
         <th scope="col">ID</th>
         <th scope="col">Nombre</th>
       </tr>
     </thead>
     <tbody id="tabla-Enfermedads">
       <?php $enfermedades = Enfermedad_Controller::get_enfermedades(); ?>
       <?php if ($enfermedades["ok"]) :  ?>
         <?php foreach ($enfermedades["data"] as $enfermedad) : ?>
           <tr>
             <td class="d-flex gap-2">
               <form method="post">
                 <button class="btn btn-danger" name="eliminar" value="<?= $enfermedad->id ?>">
                   Eliminar
                 </button>
               </form>
               <button type="button" class="btn btn-primary" id="actualizar" data-bs-toggle="modal" data-bs-target="#modalFormulario" data-info-enfermedad=<?= json_encode($enfermedad) ?>>
                 Actualizar
               </button>
             </td>
             <td><?= $enfermedad->id ?></td>
             <td><?= $enfermedad->nombre ?></td>
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
         <h5 class="modal-title" id="modalFormularioLabel">Actualizar el nombre de enfermedad</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
         <!-- Formulario -->
         <form method="post">
           <div class="mb-3">
             <label for="nombreEnfermedad" class="form-label">Nombre de la enfermedad:</label>
             <input type="text" class="form-control" id="nombreEnfermedad" placeholder="Ingrese el nombre de la enfermedad" name="updated_enfermedad">
           </div>
           <button type="submit" class="btn btn-primary" id="update_ok" name="update_ok">Enviar</button>
         </form>
       </div>
     </div>
   </div>
 </div>

 <script>
   const message = document.getElementById("message");
   const btnActualizarEnfermedad = document.getElementById("actualizar")
   const btnOKActualizar = document.getElementById("update_ok")

   btnActualizarEnfermedad.addEventListener('click', (e) => {
     const data = e.currentTarget.dataset.infoEnfermedad
     btnOKActualizar.value = data
   })

   setTimeout(() => {
     message.remove()
   }, 2000);
 </script>