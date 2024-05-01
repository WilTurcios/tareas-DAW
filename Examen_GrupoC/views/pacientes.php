<?php

require_once "C:/xampp/htdocs/tareas DAW/Examen_GrupoC/controllers/paciente_controller.php";

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $pacientes = Paciente_Controller::get_pacientes_by_municipio_id($id);

  if ($pacientes['ok']) {
    echo json_encode(['ok' => true, 'data' => $pacientes['data']]);
  } else {

    echo json_encode(['ok' => false, 'error_message' => $pacientes["error_message"]]);
  }
} else {
  echo json_encode(['ok' => false, 'error_message' => 'No se recibió el parámetro ID']);
}
