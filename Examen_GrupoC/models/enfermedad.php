<?php

require_once "interfaces/IModelo.php";

class Enfermedad implements IModelo
{
  private $connection = null;

  function __construct(
    public ?int $id = null,
    public ?string $nombre = null
  ) {
    try {
      $this->connection = new mysqli('localhost', 'root', '12345', 'hospital');
    } catch (Exception $error) {
      echo "Ha ocurrido un error: " . $error->getMessage();
    }
  }

  public function save(): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];

    try {
      $query = "INSERT INTO enfermedad (nombre) VALUES ('$this->nombre')";
      $result = $this->connection->query($query);

      if ($result) {
        $this->id = $this->connection->insert_id;
        $enfermedad = new self(
          $this->id,
          $this->nombre
        );

        $respuesta["data"][] = $enfermedad;
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo insertar el enfermedad, por favor intentalo de nuevo';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }

  public static function update(int $id, string $nombre): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];

    try {
      $query = "UPDATE enfermedad SET nombre = '$nombre' WHERE id = '$id'";
      $result = (new self())->connection->query($query);

      if ($result) {
        $enfermedad = new self(
          $id,
          $nombre
        );

        $respuesta["data"][] = $enfermedad;
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo actualizar el enfermedad, por favor intentalo de nuevo';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }

  public static function delete(int $id): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => ""
    ];

    try {
      $query = "DELETE FROM enfermedad WHERE id = '$id'";
      $result = (new self)->connection->query($query);

      if ($result) {
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo eliminar el enfermedad, por favor intentalo de nuevo';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }

  public static function get_all(): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];

    try {
      $query = "SELECT * FROM enfermedad;";
      $result = (new self())->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $enfermedad = new self(
            $registro["id"],
            $registro["nombre"]
          );

          $respuesta["data"][] = $enfermedad;
        }


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener las enfermedades registradas';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }

  public static function get_by_id(int $id): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];

    try {
      $query = "SELECT * FROM enfermedad WHERE id = '$id';";
      $result = (new self())->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $enfermedad = new self(
            $registro["id"],
            $registro["nombre"]
          );

          $respuesta["data"][] = $enfermedad;
        }


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener la enfermedad especificada.';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }
}
