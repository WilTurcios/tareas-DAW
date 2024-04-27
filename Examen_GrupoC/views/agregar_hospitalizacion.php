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
       </tr>
     </thead>
     <tbody id="tabla-empleados">
       <?php $empleados = Empleado_Controller::get_empleados(); ?>
       <?php if ($empleados["ok"]) :  ?>
         <?php foreach ($empleados["data"] as $empleado) : ?>
           <tr>
             <td><?= $empleado->id ?></td>
             <td><?= $empleado->nombre ?></td>
             <td><?= $empleado->apellido ?></td>
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