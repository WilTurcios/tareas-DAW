<?php
if (!defined("URL")) define("URL", "http://localhost/Examen_GrupoC");
?>

<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
  <header>
    <?php
    require_once("views/components/menu.php");
    ?>
  </header>
  <main>


    <?php
    $paginas = [
      "inicio" => "views/inicio.php",
      "addempleados" => "views/agregar_empleados.php",
      "addenfermedades" => "views/agregar_enfermedades.php",
      "addmunicipios" => "views/agregar_municipios.php",
      "addhospitalizacion" => "views/agregar_hospitalizacion.php",
      "addpaciente" => "views/agregar_pacientes.php",
    ];

    if (isset($_GET["url"])) {

      if (array_key_exists($_GET["url"], $paginas)) {
        require_once($paginas[$_GET["url"]]);
      } else {
        require_once("views/404.php");
      }
    } else {
      require_once $paginas["inicio"];
    }
    ?>

  </main>
  <footer>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>