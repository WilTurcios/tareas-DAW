<?php

require_once "interfaces/IModelo.php";

class Empleado implements IModelo
{
  public $connection = null;

  function __construct(
    public ?int $id = null,
    public ?string $nombre = null,
    public ?string $apellido = null,
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
      $query = "INSERT INTO empleado (nombre, apellido) VALUES ('$this->nombre', '$this->apellido')";
      $result = $this->connection->query($query);

      if ($result) {
        $this->id = $this->connection->insert_id;

        $empleado = new self(
          $this->id,
          $this->nombre,
          $this->apellido
        );

        $respuesta["data"][] = $empleado;
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo insertar el empleado, por favor intentalo de nuevo';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }

  public function update(int $id, string $nombre, string $apellido): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];

    try {
      $query = "UPDATE empleado SET nombre = '$nombre', apellido = '$apellido' WHERE id = '$id'";
      $result = $this->connection->query($query);

      if ($result) {
        $empleado = new self(
          $id,
          $nombre,
          $apellido
        );

        $respuesta["data"][] = $empleado;
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo actualizar el empleado, por favor intentalo de nuevo';

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
      $query = "DELETE FROM empleado WHERE id = '$id'";
      $result = (new self())->connection->query($query);

      if ($result) {
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo actualizar el empleado, por favor intentalo de nuevo';

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
      $query = "SELECT * FROM empleado;";
      $result = (new self())->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $empleado = new self(
            $registro["id"],
            $registro["nombre"],
            $registro["apellido"]
          );

          $respuesta["data"][] = $empleado;
        }


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener a los empleados registrados';

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
      $query = "SELECT * FROM empleado WHERE id = '$id';";
      $result = (new self())->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $empleado = new self(
            $registro["id"],
            $registro["nombre"],
            $registro["apellido"]
          );

          $respuesta["data"][] = $empleado;
        }


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener al empleado especificado';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }
}
