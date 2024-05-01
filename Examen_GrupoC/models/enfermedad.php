<?php

require_once "C:/xampp/htdocs/tareas DAW/Examen_GrupoC/interfaces/IModelo.php";

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
    $db = new self();

    try {
      $query = "INSERT INTO enfermedad (nombre) VALUES ('$this->nombre')";
      $result = $db->connection->query($query);

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
    } finally {
      $db->connection->close();
    }
  }

  public static function update(int $id, string $nombre): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];

    $db = new self();

    try {
      $query = "UPDATE enfermedad SET nombre = '$nombre' WHERE id = '$id'";
      $result = $db->connection->query($query);

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
    } finally {
      $db->connection->close();
    }
  }

  public static function delete(int $id): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => ""
    ];
    $db = new self();

    try {
      $query = "DELETE FROM enfermedad WHERE id = '$id'";
      $result = $db->connection->query($query);

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
    } finally {
      $db->connection->close();
    }
  }

  public static function get_all(): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];
    $db = new self();

    try {
      $query = "SELECT * FROM enfermedad;";
      $result = $db->connection->query($query);

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
    } finally {
      $db->connection->close();
    }
  }

  public static function get_by_id(int $id): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];
    $db = new self();

    try {
      $query = "SELECT * FROM enfermedad WHERE id = '$id';";
      $result = $db->connection->query($query);

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
    } finally {
      $db->connection->close();
    }
  }

  public static function get_enfermedades_by_paciente_id(int $id): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];
    $db = new self();

    try {
      $query = "
        select e.id, e.nombre from enfermedad e 
          inner join hospitalizacion h on e.id = h.id_enfermedad 
          inner join paciente p on p.id = h.id_paciente where p.id = '$id';";

      $result = $db->connection->query($query);

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
    } finally {
      $db->connection->close();
    }
  }
}
