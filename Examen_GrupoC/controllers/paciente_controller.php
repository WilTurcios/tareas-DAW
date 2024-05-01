<?php
// require_once "\services\mongodb\connection.php";
require_once "C:/xampp/htdocs/tareas DAW/Examen_GrupoC/models/paciente.php";
class Paciente_Controller
{
  public static function insert_paciente(
    string $nombre,
    string $apellido,
    string $direccion,
    string $sexo,
    string $tipo_sangre,
    int $id_municipio
  ) {
    if (!$nombre) return;
    if (!$apellido) return;
    if (!$direccion) return;
    if (!$sexo) return;
    if (!$tipo_sangre) return;
    if (!$id_municipio) return;

    $nuevo_paciente = new Paciente(
      null,
      $nombre,
      $apellido,
      $direccion,
      $sexo,
      $tipo_sangre,
      $id_municipio
    );

    $result =  $nuevo_paciente->save();

    return $result;
  }

  public static function delete_paciente_by_id(
    int $id
  ) {
    if (!$id) return;

    $result =  Paciente::delete($id);

    return $result;
  }
  public static function update_paciente(
    int $id,
    string $nombre,
    string $apellido,
    string $direccion,
    string $sexo,
    string $tipo_sangre,
    int $id_municipio
  ) {

    if (!$id) return;
    if (!$nombre) return;

    $result =  Paciente::update(
      $id,
      $nombre,
      $apellido,
      $direccion,
      $sexo,
      $tipo_sangre,
      $id_municipio
    );

    return $result;
  }

  public static function get_paciente_by_id(int $id)
  {
    if ($id && is_int($id)) return;

    return Paciente::get_by_id($id);
  }

  public static function get_pacientes()
  {
    return Paciente::get_all();
  }

  public static function get_pacientes_by_municipio_id(int $id)
  {
    return Paciente::get_pacientes_by_municipio_id($id);
  }
}
