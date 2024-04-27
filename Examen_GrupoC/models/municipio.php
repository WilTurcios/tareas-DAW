<?php

require_once "interfaces/IModelo.php";

class municipio implements IModelo
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
      $query = "INSERT INTO municipio (nombre) VALUES ('$this->nombre')";
      $result = $this->connection->query($query);

      if ($result) {
        $this->id = $this->connection->insert_id;
        $municipio = new self(
          $this->id,
          $this->nombre
        );

        $respuesta["data"][] = $municipio;
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo insertar el municipio, por favor intentalo de nuevo';

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
      $query = "UPDATE municipio SET nombre = '$nombre' WHERE id = '$id'";
      $result = (new self())->connection->query($query);

      if ($result) {
        $municipio = new self(
          $id,
          $nombre
        );

        $respuesta["data"][] = $municipio;
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo actualizar el municipio, por favor intentalo de nuevo';

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
      $query = "DELETE FROM municipio WHERE id = '$id'";
      $result = (new self())->connection->query($query);

      if ($result) {
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo actualizar el municipio, por favor intentalo de nuevo';

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
      $query = "SELECT * FROM municipio;";
      $result = (new self())->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $municipio = new self(
            $registro["id"],
            $registro["nombre"]
          );

          $respuesta["data"][] = $municipio;
        }


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener a los municipios registrados';

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
      $query = "SELECT * FROM municipio WHERE id = '$id';";
      $result = (new self())->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $municipio = new self(
            $registro["id"],
            $registro["nombre"]
          );

          $respuesta["data"][] = $municipio;
        }


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener al municipio especificado';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }
}
