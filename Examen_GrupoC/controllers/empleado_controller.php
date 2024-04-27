<?php
// require_once "\services\mongodb\connection.php";
require_once "models/empleado.php";
class Empleado_Controller
{
  function __construct()
  {
  }

  public static function insert_empleado(string $nombre, string $apellido)
  {
    if (!$nombre) return;
    if (!$apellido) return;

    $nuevo_empleado = new Empleado(null, $nombre, $apellido);

    $result =  $nuevo_empleado->save();

    return $result;
  }

  public static function get_empleado_by_id(int $id)
  {
    if ($id && is_int($id)) return;

    return Empleado::get_by_id($id);
  }

  public static function get_empleados()
  {
    return Empleado::get_all();
  }
}
