<?php
// require_once "\services\mongodb\connection.php";
require_once "models/municipio.php";
class Municipio_Controller
{
  function __construct()
  {
  }

  public static function insert_municipio(string $nombre)
  {
    if (!$nombre) return;

    $nuevo_municipio = new Municipio(null, $nombre);

    $result =  $nuevo_municipio->save();

    return $result;
  }

  public static function delete_municipio_by_id(int $id)
  {
    if (!$id) return;

    $result =  Municipio::delete($id);

    return $result;
  }
  public static function update_municipio(int $id, string $nombre)
  {
    if (!$id) return;
    if (!$nombre) return;

    $result =  Municipio::update($id, $nombre);

    return $result;
  }

  public static function get_municipio_by_id(int $id)
  {
    if ($id && is_int($id)) return;

    return Municipio::get_by_id($id);
  }

  public static function get_municipios()
  {
    return Municipio::get_all();
  }
}
