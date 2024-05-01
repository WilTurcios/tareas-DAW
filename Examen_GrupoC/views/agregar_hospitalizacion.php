 <?php
  require_once "controllers/paciente_controller.php";
  require_once "controllers/empleado_controller.php";
  require_once "controllers/enfermedad_controller.php";
  require_once "controllers/hospitalizacion_controller.php";

  // Insertar registro de municipio
  if (isset($_POST["ok"])) {

    if (empty($_POST["motivo_consulta"])) {
      echo "
        <div id='message' class='alert alert-danger' role='alert'>
          Todos los campos son requeridos
        </div>
      ";
    } else {

      $fecha_ingreso = new DateTime($_POST["fecha_ingreso"]);
      $fecha_alta = new DateTime($_POST["fecha_alta"]);
      $hospitalizacion = Hospitalizacion_Controller::insert_hospitalizacion(
        $_POST["motivo_consulta"],
        $fecha_ingreso,
        $fecha_alta,
        $_POST["paciente"],
        $_POST["empleado"],
        $_POST["enfermedad"]
      );


      if ($hospitalizacion["ok"]) {
        $id = $hospitalizacion["data"][0]->id;
        $nombre = $hospitalizacion["data"][0]->motivo_consulta;

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
        echo $hospitalizacion["error_message"];
      }
    }
  }

  // Actualizar registro de municipio
  if (isset($_POST["update_ok"])) {
    $info_paciente = json_decode($_POST["update_ok"]);

    if (empty($_POST["updated_municipio"])) {
      echo "
        <div id='message' class='alert alert-danger' role='alert'>
          El nombre actualizado de la municipio es obligatorio
        </div>
      ";
    } else {

      if ($info_paciente->nombre != $_POST["updated_nombre"]) {
        $paciente = Hospitalizacion_Controller::update_hospitalizacion(
          $info_paciente->id,
          $_POST["updated_nombre"],
          $_POST["updated_apellido"],
          $_POST["updated_direccion"],
          $_POST["updated_sexo"],
          $_POST["updated_tipo_sangre"],
          $_POST["updated_municipio"]
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
    }
  }

  // Eliminar registro de municipio
  if (isset($_POST["eliminar"])) {

    $municipio_eliminada = Hospitalizacion_Controller::delete_hospitalizacion_by_id($_POST["eliminar"]);

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
   <h1>Formulario de Hospitalización</h1>
   <form method="post">
     <div class="mb-3">
       <label for="motivo_consulta" class="form-label">Motivo de la consulta</label>
       <input type="text" class="form-control" id="motivo_consulta" name="motivo_consulta" required>
     </div>
     <div class="mb-3">
       <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
       <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
     </div>
     <div class="mb-3">
       <label for="fecha_alta" class="form-label">Fecha de Alta</label>
       <input type="date" class="form-control" id="fecha_alta" name="fecha_alta" required>
     </div>
     <div class="mb-3">
       <label for="paciente" class="form-label">Paciente</label>
       <select class="form-select" id="paciente" name="paciente" required>
         <option value="">Seleccionar paciente</option>
         <?php $pacientes = Paciente_Controller::get_pacientes() ?>
         <?php if ($pacientes["ok"]) :  ?>
           <?php foreach ($pacientes["data"] as $paciente) : ?>
             <option value="<?= $paciente->id ?>"><?= $paciente->nombre ?></option>
           <?php endforeach; ?>
         <?php endif; ?>
       </select>
     </div>
     <div class="mb-3">
       <label for="enfermedad" class="form-label">Enfermedad detectada</label>
       <select class="form-select" id="enfermedad" name="enfermedad" required>
         <option value="">Seleccionar enfermedad</option>
         <?php $enfermedades = Enfermedad_Controller::get_enfermedades() ?>
         <?php if ($enfermedades["ok"]) :  ?>
           <?php foreach ($enfermedades["data"] as $enfermedad) : ?>
             <option value="<?= $enfermedad->id ?>"><?= $enfermedad->nombre ?></option>
           <?php endforeach; ?>
         <?php endif; ?>
       </select>
     </div>
     <div class="mb-3">
       <label for="empleado" class="form-label">Empleado por el que fue atendido</label>
       <select class="form-select" id="empleado" name="empleado" required>
         <option value="">Seleccionar empleado</option>
         <?php $empleados = Empleado_Controller::get_empleados() ?>
         <?php if ($empleados["ok"]) :  ?>
           <?php foreach ($empleados["data"] as $empleado) : ?>
             <option value="<?= $empleado->id ?>"><?= $empleado->nombre ?></option>
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
         <th scope="col">Motivo de consulta</th>
         <th scope="col">Fecha de ingreso</th>
         <th scope="col">Fecha de alta</th>
         <th scope="col">Paciente</th>
         <th scope="col">Enfermedad</th>
         <th scope="col">Empleado</th>
       </tr>
     </thead>
     <tbody id="tabla-municipios">
       <?php
        $hospitalizaciones = Hospitalizacion_Controller::get_hospitalizaciones();
        ?>
       <?php if ($hospitalizaciones["ok"]) : ?>
         <?php foreach ($hospitalizaciones["data"] as $hospitalizacion) : ?>
           <tr>
             <td class="d-flex gap-2">
               <form method="post">
                 <button class="btn btn-danger" name="eliminar" value="<?= $hospitalizacion->id ?>">
                   Eliminar
                 </button>
               </form>
             </td>
             <td><?= $hospitalizacion->id ?></td>
             <td><?= $hospitalizacion->motivo_consulta ?></td>
             <td><?= $hospitalizacion->fecha_ingreso->format('d-m-Y') ?></td>
             <td><?= $hospitalizacion->fecha_alta->format('d-m-Y') ?></td>
             <td><?= $hospitalizacion->paciente->nombre ?></td>
             <td><?= $hospitalizacion->enfermedad->nombre ?></td>
             <td><?= $hospitalizacion->empleado->nombre ?></td>
           </tr>
         <?php endforeach; ?>
       <?php endif; ?>


     </tbody>
   </table>
 </div>

 <script>
   const message = document.getElementById("message");
   setTimeout(() => {
     message.remove()
   }, 2000);
 </script>