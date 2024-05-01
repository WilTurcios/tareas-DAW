<?php
// require_once "\services\mongodb\connection.php";
require_once "models/hospitalizacion.php";
class Hospitalizacion_Controller
{
  public static function insert_hospitalizacion(
    string $motivo_consulta,
    DateTime $fecha_ingreso,
    DateTime $fecha_alta,
    int $id_paciente,
    int $id_empleado,
    int $id_enfermedad
  ) {

    if (!$motivo_consulta) return;
    if (!$fecha_ingreso) return;
    if (!$fecha_alta) return;
    if (!$id_paciente) return;
    if (!$id_empleado) return;
    if (!$id_enfermedad) return;

    $nueva_hospitalizacion = new Hospitalizacion(
      null,
      $motivo_consulta,
      $fecha_ingreso,
      $fecha_alta,
      $id_paciente,
      $id_empleado,
      $id_enfermedad
    );

    $result =  $nueva_hospitalizacion->save();

    return $result;
  }

  public static function delete_hospitalizacion_by_id(
    int $id
  ) {
    if (!$id) return;

    $result =  Hospitalizacion::delete($id);

    return $result;
  }
  public static function update_hospitalizacion(
    int $id,
    string $motivo_consulta,
    DateTime $fecha_ingreso,
    DateTime $fecha_alta,
    int $id_paciente,
    int $id_empleado,
    int $id_enfermedad
  ) {
    if (!$motivo_consulta) return;
    if (!$fecha_ingreso) return;
    if (!$fecha_alta) return;
    if (!$id_paciente) return;
    if (!$id_empleado) return;
    if (!$id_enfermedad) return;
    $result =  Hospitalizacion::update(
      $id,
      $motivo_consulta,
      $fecha_ingreso,
      $fecha_alta,
      $id_paciente,
      $id_empleado,
      $id_enfermedad
    );

    return $result;
  }

  public static function get_hospitalizacion_by_id(int $id)
  {
    if ($id && is_int($id)) return;

    return Hospitalizacion::get_by_id($id);
  }

  public static function get_hospitalizaciones()
  {
    return Hospitalizacion::get_all();
  }
}
