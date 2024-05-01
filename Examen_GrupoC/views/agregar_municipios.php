 <?php


  require_once "controllers/municipio_controller.php";
  require_once "controllers/paciente_controller.php";
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

  // Enviar al front-end los pacientes segun el id proporcionado


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
         <th scope="col">Mostrar Pacientes</th>
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
             <td>
               <button type="button" class="btn btn-primary" id="mostrar_pacientes" value='<?= json_encode($municipio->id) ?>'>
                 Mostrar Pacientes
               </button>
             </td>
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

 <!-- Modal para mostrar enfermedades registradas por paciente -->
 <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="infoModalLabel">Información</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
         <div id="pacientes" class="d-flex flex-column gap-3">
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
   const mostrarPacientesBtns = document.querySelectorAll('#mostrar_pacientes');
   const btnActualizarMunicipio = document.getElementById('actualizar');
   const btnOKActualizar = document.getElementById('update_ok');
   const pacientesContainer = document.getElementById("pacientes")

   btnActualizarMunicipio.addEventListener('click', (e) => {
     const data = e.currentTarget.dataset.infoMunicipio;
     btnOKActualizar.value = data;
   });

   const message = document.getElementById('message');
   setTimeout(() => {
     if (message) {
       message.remove();
     }
   }, 2000);

   const getPacientesByMunicipioID = async (id) => {
     try {
       const result = await fetch(
         `http://localhost/tareas%20DAW/Examen_GrupoC/views/pacientes.php?id=${id}`
       );

       console.log(result)

       if (!result.ok) {
         throw new Error('Hubo un problema al obtener los pacientes');
       }

       const receivedPatients = await result.json();
       console.log(receivedPatients)

       return receivedPatients.data;
     } catch (error) {
       console.error('Error al obtener los pacientes:', error.message);
       return {
         ok: false,
         error_message: error.message
       };
     }
   };

   async function mostrarPacientes({
     currentTarget
   }) {
     const municipioID = currentTarget.value;

     const pacientes = await getPacientesByMunicipioID(municipioID);

     console.log(pacientes)
     let pacientesContainer = document.getElementById('pacientes');

     pacientesContainer.innerHTML = pacientes.length === 0 ? /*html*/ `<span class="bg-danger text-white p-2 rounded text-center">Este empleado no ha hospitalizado pacientes.</span>` : `<h3 class="text-center">Total de pacientes ingresados ${pacientes.length}</h3>`;

     pacientes.forEach(paciente => {
       pacientesContainer.innerHTML += /*html*/ `
        <div class="border p-2 border-rounded">
          <p>Código del Paciente: ${paciente.id}</p> 
          <h6>Nombre del paciente: ${paciente.nombre} ${paciente.apellido} </h6>
          <p>Dirección: ${paciente.direccion}</p>
          <p>Sexo: ${paciente.sexo}</p>
          <p>Tipo de Sangre: ${paciente.tipo_sangre}</p>
          <p>ID del municipio: ${paciente.municipio.id}</p>
          <p>Nombre del Municipio: ${paciente.municipio.nombre}</p>
        </div>
      `;
     });

     let infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
     infoModal.show();
   }

   mostrarPacientesBtns.forEach(btn => {
     btn.addEventListener("click", e => {
       mostrarPacientes(e);
     });
   });
 </script>