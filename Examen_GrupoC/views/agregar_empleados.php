 <?php
  require_once "controllers/empleado_controller.php";

  if (isset($_POST["ok"])) {

    if (empty($_POST["nombre"]) || empty($_POST["apellido"])) {
      echo "
        <div id='message' class='alert alert-danger' role='alert'>
          Todos los campos son obligatorios
        </div>
      ";
    } else {
      $empleado = Empleado_Controller::insert_empleado($_POST["nombre"], $_POST["apellido"]);

      if ($empleado["ok"]) {
        $id = $empleado["data"][0]->id;
        $nombre = $empleado["data"][0]->nombre;
        $apellido = $empleado["data"][0]->apellido;

        echo "
        <div id='message' class='alert alert-success' role='alert'>
          Empleado creado correctamente: 
          <ul>
            <li>$id</li>
            <li>$nombre</li>
            <li>$apellido</li>
          </ul>
        </div>
      ";
      } else {
        echo $empleado["error_message"];
      }
    }
  }
  ?>


 <div class="container">
   <h1>Formulario de Empleado</h1>
   <form method="post" enctype="multipart/form-data">
     <div class="mb-3">
       <label for="nombre" class="form-label">Nombre</label>
       <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese su nombre">
     </div>
     <div class="mb-3">
       <label for="apellido" class="form-label">Apellido</label>
       <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Ingrese su apellido">
     </div>
     <button type="submit" class="btn btn-primary" name="ok">Enviar</button>
   </form>
 </div>

 <div class="container">
   <h1>Tabla de Empleados</h1>
   <table class="table">
     <thead>
       <tr>
         <th scope="col">ID</th>
         <th scope="col">Nombre</th>
         <th scope="col">Apellido</th>
         <th>Mostrar pacientes</th>
       </tr>
     </thead>
     <tbody id="tabla-empleados">
       <?php $empleados = Empleado_Controller::get_empleados(); ?>
       <?php if ($empleados["ok"]) :  ?>
         <?php foreach ($empleados["data"] as $empleado) : ?>
           <tr class="pacientes-container  bg-success text-white p-3">
             <td><?= $empleado->id ?></td>
             <td><?= $empleado->nombre ?></td>
             <td><?= $empleado->apellido ?></td>
             <td>
               <button type="button" class="btn btn-primary" id="mostrar_pacientes" value='<?= json_encode($empleado->pacientes) ?>'>
                 Mostrar Pacientes
               </button>
             </td>
           </tr>
         <?php endforeach; ?>
       <?php endif; ?>

     </tbody>
   </table>
 </div>

 <!-- Modal para mostrar pacientes registrados por empleado -->
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

 <style>
   .paciente-container {
     position: relative;
   }

   .pacientes-item {
     position: absolute;
     width: 80vw;
     height: auto;
     left: 50%;
     transform: translateX(-50%);
     bottom: -48px;
     gap: 10px;

   }
 </style>

 <script>
   const message = document.getElementById("message");
   const mostrarPacientesBtns = document.querySelectorAll('#mostrar_pacientes')

   setTimeout(() => {
     message.remove()
   }, 2000);

   function mostrarPacientes({
     currentTarget
   }) {
     const pacientes = JSON.parse(currentTarget.value)
     console.log(pacientes)

     let pacientesContainer = document.getElementById('pacientes');

     pacientesContainer.innerHTML =
       pacientes.length === 0 ?
       '<span class="bg-danger text-white p-2 rounded text-center">Este empleado no ha hospitalizado pacientes.</span>' :
       `<h3 class="text-center">Total de pacientes ingresados ${pacientes.length}</h3>`;

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
       `
     })

     let infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
     infoModal.show();
   }

   mostrarPacientesBtns.forEach(btn => {
     btn.addEventListener("click", e => {
       mostrarPacientes(e)
     })
   })
 </script>