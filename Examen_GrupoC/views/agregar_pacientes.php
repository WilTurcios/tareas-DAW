 <?php
  require_once "controllers/paciente_controller.php";
  require_once "controllers/municipio_controller.php";

  // Insertar registro de municipio
  if (isset($_POST["ok"])) {

    if (empty($_POST["nombre"])) {
      echo "
        <div id='message' class='alert alert-danger' role='alert'>
          El nombre del municipio es obligatorio
        </div>
      ";
    } else {
      $municipio = Paciente_Controller::insert_paciente(
        $_POST["nombre"],
        $_POST["apellido"],
        $_POST["direccion"],
        $_POST["sexo"],
        $_POST["tipo_sangre"],
        $_POST["municipio"]
      );

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
    $info_paciente = json_decode($_POST["update_ok"]);

    echo $_POST["updated_municipio"];
    $updated_nombre = !empty($_POST["updated_nombre"])
      ? $_POST["updated_nombre"]
      : $info_paciente->nombre;
    $updated_apellido = !empty($_POST["updated_apellido"])
      ? $_POST["updated_apellido"]
      : $info_paciente->apellido;
    $updated_direccion = !empty($_POST["updated_direccion"])
      ? $_POST["updated_direccion"]
      : $info_paciente->direccion;
    $updated_sexo = !empty($_POST["updated_sexo"])
      ? $_POST["updated_sexo"]
      : $info_paciente->sexo;
    $updated_tipo_sangre = !empty($_POST["updated_tipo_sangre"])
      ? $_POST["updated_tipo_sangre"]
      : $info_paciente->tipo_sangre;
    $updated_municipio = !empty($_POST["updated_municipio"])
      ? $_POST["updated_municipio"]
      : $info_paciente->municipio->id;


    $paciente = Paciente_Controller::update_paciente(
      $info_paciente->id,
      $updated_nombre,
      $updated_apellido,
      $updated_direccion,
      $updated_sexo,
      $updated_tipo_sangre,
      $updated_municipio
    );

    if ($paciente["ok"]) {
      $id = $paciente["data"][0]->id;
      $nombre = $paciente["data"][0]->nombre;

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
      echo $paciente["error_message"];
    }
  }


  // Eliminar registro de municipio
  if (isset($_POST["eliminar"])) {

    $municipio_eliminada = Paciente_Controller::delete_paciente_by_id($_POST["eliminar"]);

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
   <form method="post">
     <div class="mb-3">
       <label for="nombre" class="form-label">Nombre</label>
       <input type="text" class="form-control" id="nombre" name="nombre" required>
     </div>
     <div class="mb-3">
       <label for="apellido" class="form-label">Apellido</label>
       <input type="text" class="form-control" id="apellido" name="apellido" required>
     </div>
     <div class="mb-3">
       <label for="direccion" class="form-label">Dirección</label>
       <input type="text" class="form-control" id="direccion" name="direccion" required>
     </div>
     <div class="mb-3">
       <label for="sexo" class="form-label">Sexo</label>
       <select class="form-select" id="sexo" name="sexo" required>
         <option value="">Seleccionar sexo</option>
         <option value="Masculino">Masculino</option>
         <option value="Femenino">Femenino</option>
       </select>
     </div>
     <div class="mb-3">
       <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
       <input type="text" class="form-control" id="tipo_sangre" name="tipo_sangre" required>
     </div>
     <div class="mb-3">
       <label for="id_municipio" class="form-label">Municipio</label>
       <select class="form-select" id="municipio" name="municipio" required>
         <option value="">Seleccionar municipio</option>
         <?php $municipios = Municipio_Controller::get_municipios() ?>
         <?php if ($municipios["ok"]) :  ?>
           <?php foreach ($municipios["data"] as $municipio) : ?>
             <option value="<?= $municipio->id ?>"><?= $municipio->nombre ?></option>
           <?php endforeach; ?>
         <?php endif; ?>
       </select>
     </div>
     <button type="submit" class="btn btn-primary" name="ok">Enviar</button>
   </form>

 </div>
 <div class="container">
   <h1>Tabla de pacientes</h1>
   <table class="table">
     <thead>
       <tr>
         <th>Acción</th>
         <th scope="col">ID</th>
         <th scope="col">Nombre</th>
         <th scope="col">Apellido</th>
         <th scope="col">Dirección</th>
         <th scope="col">Sexo</th>
         <th scope="col">Tipo de sangre</th>
         <th scope="col">Municipio</th>
         <th scope="col">Mostrar Enfermedades</th>
       </tr>
     </thead>
     <tbody id="tabla-municipios">
       <?php
        $pacientes = Paciente_Controller::get_pacientes();
        ?>
       <?php if ($pacientes["ok"]) : ?>
         <?php foreach ($pacientes["data"] as $paciente) : ?>
           <tr>
             <td class="d-flex gap-2">
               <form method="post">
                 <button class="btn btn-danger" name="eliminar" value="<?= $paciente->id ?>">
                   Eliminar
                 </button>
               </form>
               <button type="button" class="btn btn-primary" id="actualizar" data-bs-toggle="modal" data-bs-target="#modalFormulario" data-info-paciente='<?= json_encode($paciente) ?>'>
                 Actualizar
               </button>
             </td>
             <td><?= $paciente->id ?></td>
             <td><?= $paciente->nombre ?></td>
             <td><?= $paciente->apellido ?></td>
             <td><?= $paciente->direccion ?></td>
             <td><?= $paciente->sexo ?></td>
             <td><?= $paciente->tipo_sangre ?></td>
             <td><?= $paciente->municipio->nombre ?></td>
             <td>
               <button type="button" class="btn btn-primary" id="mostrar_enfermedades" value='<?= json_encode($paciente->enfermedades) ?>'>
                 Mostrar Enfermedades
               </button>
             </td>
           </tr>
         <?php endforeach; ?>
       <?php endif; ?>


     </tbody>
   </table>
 </div>

 <!-- Modal de actualizacion -->
 <div class="modal fade" id="modalFormulario" tabindex="-1" aria-labelledby="modalFormularioLabel" aria-hidden="true">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="modalFormularioLabel">Actualizar el nombre de municipio</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
         <form method="post">
           <div class="mb-3">
             <label for="nombre" class="form-label">Nombre</label>
             <input type="text" class="form-control" id="nombre" name="updated_nombre">
           </div>
           <div class="mb-3">
             <label for="apellido" class="form-label">Apellido</label>
             <input type="text" class="form-control" id="apellido" name="updated_apellido">
           </div>
           <div class="mb-3">
             <label for="direccion" class="form-label">Dirección</label>
             <input type="text" class="form-control" id="direccion" name="updated_direccion">
           </div>
           <div class="mb-3">
             <label for="sexo" class="form-label">Sexo</label>
             <select class="form-select" id="sexo" name="updated_sexo">
               <option value="">Seleccionar sexo</option>
               <option value="Masculino">Masculino</option>
               <option value="Femenino">Femenino</option>
             </select>
           </div>
           <div class="mb-3">
             <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
             <input type="text" class="form-control" id="tipo_sangre" name="updated_tipo_sangre">
           </div>
           <div class="mb-3">
             <label for="id_municipio" class="form-label">Municipio</label>
             <select class="form-select" id="municipio" name="updated_municipio">
               <option value="">Seleccionar municipio</option>
               <?php $municipios = Municipio_Controller::get_municipios() ?>
               <?php if ($municipios["ok"]) :  ?>
                 <?php foreach ($municipios["data"] as $municipio) : ?>
                   <option value="<?= $municipio->id ?>"><?= $municipio->nombre ?></option>
                 <?php endforeach; ?>
               <?php endif; ?>
             </select>
           </div>
           <button type="submit" class="btn btn-primary" id="update_ok" name="update_ok">Enviar</button>
         </form>
       </div>
     </div>
   </div>
 </div>

 <!-- Modal para mostrar enfermedades registradas por paciente -->
 <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="infoModalLabel">Información</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
         <div id="enfermedades" class="d-flex flex-column gap-3">
           <!-- La información se agregará aquí -->
         </div>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
       </div>
     </div>
   </div>
 </div>

 <script>
   const message = document.getElementById("message");
   const actualizarPacientesBtns = document.querySelectorAll("#actualizar")
   const btnOKActualizar = document.getElementById("update_ok")

   actualizarPacientesBtns.forEach(btn => btn.addEventListener('click', (e) => {
     const data = e.currentTarget.dataset.infoPaciente
     btnOKActualizar.value = data
   }))

   const mostrarEnfermedadesBtns = document.querySelectorAll('#mostrar_enfermedades')

   setTimeout(() => {
     message.remove()
   }, 2000);

   function mostrarEnfermedades({
     currentTarget
   }) {
     const enfermedades = JSON.parse(currentTarget.value)
     console.log(enfermedades)

     let enfermedadesContainer = document.getElementById('enfermedades');

     enfermedadesContainer.innerHTML =
       enfermedades.length === 0 ?
       '<span class="bg-danger text-white p-2 rounded text-center">No se han registrado enfermedades para este paciente</span>' :
       `<h3 class="text-center">Total de enfermedades registradas ${enfermedades.length}</h3>`;

     enfermedades.forEach(enfermedad => {
       enfermedadesContainer.innerHTML += /*html*/ `
        <div class="border p-2 border-rounded">
          <h5>Código del enfermedad: ${enfermedad.id}</h5> 
          <h6>Nombre del enfermedad: ${enfermedad.nombre}</h6>
        </div>
       `
     })

     let infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
     infoModal.show();
   }

   mostrarEnfermedadesBtns.forEach(btn => {
     btn.addEventListener("click", e => {
       mostrarEnfermedades(e)
     })
   })
 </script>