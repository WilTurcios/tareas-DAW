<?php
// require_once "\services\mongodb\connection.php";
require_once "models/enfermedad.php";
class Enfermedad_Controller
{
  function __construct()
  {
  }

  public static function insert_enfermedad(string $nombre)
  {
    if (!$nombre) return;

    $nuevo_empleado = new Enfermedad(null, $nombre);

    $result =  $nuevo_empleado->save();

    return $result;
  }

  public static function delete_enfermedad_by_id(int $id)
  {
    if (!$id) return;

    $result =  Enfermedad::delete($id);

    return $result;
  }
  public static function update_enfermedad(int $id, string $nombre)
  {
    if (!$id) return;
    if (!$nombre) return;

    $result =  Enfermedad::update($id, $nombre);

    return $result;
  }

  public static function get_enfermedad_by_id(int $id)
  {
    if ($id && is_int($id)) return;

    return Enfermedad::get_by_id($id);
  }

  public static function get_enfermedades()
  {
    return Enfermedad::get_all();
  }
}
