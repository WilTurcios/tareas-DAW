<?php

require_once "/interfaces/IModelo.php";
require_once "/models/municipio.php";

class Paciente implements IModelo
{
  private $connection = null;
  public Municipio $municipio = null;

  function __construct(
    public ?int $id = null,
    public ?string $nombre = null,
    public ?string $apellido = null,
    public ?string $direccion = null,
    public ?string $sexo = null,
    public ?string $tipo_sangre = null,
    ?int $id_municipio = null,
  ) {
    try {
      $this->connection = new mysqli('localhost', 'root', '12345', 'hospital');
    } catch (Exception $error) {
      echo "Ha ocurrido un error: " . $error->getMessage();
    }

    if ($id_municipio) {
      $this->municipio = Municipio::get_by_id($id);
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
      $id_municipio = $this->municipio->id;
      $query = "INSERT INTO paciente (
        nombre,
        apellido,
        direccion, 
        sexo, 
        tipo_sangre, 
        id_municipio
      )
      VALUES (
        '$this->nombre',
        '$this->apellido',
        '$this->direccion',
        '$this->sexo',
        '$this->tipo_sangre',
        '$id_municipio'
      )";

      $result = $this->connection->query($query);

      if ($result) {
        $this->id = $this->connection->insert_id;
        $paciente = new self(
          $this->id,
          $this->nombre,
          $this->apellido,
          $this->direccion,
          $this->sexo,
          $this->tipo_sangre,
          $id_municipio
        );

        $respuesta["data"][] = $paciente;
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo insertar el paciente, por favor intentalo de nuevo';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }

  public function update(
    int $id,
    string $nombre,
    string $apellido,
    string $direccion,
    string $sexo,
    string $tipo_sangre,
    int $id_municipio
  ): array {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];

    try {
      $query = "UPDATE paciente SET 
        nombre = '$nombre', 
        apellido = '$apellido',
        direccion = '$direccion',
        sexo = '$sexo',
        tipo_sangre = '$tipo_sangre',
        id_municipio = '$id_municipio',
      WHERE id = '$id'";
      $result = $this->connection->query($query);

      if ($result) {
        $paciente = new self(
          $id,
          $nombre,
          $apellido,
          $direccion,
          $sexo,
          $tipo_sangre,
          $id_municipio
        );

        $respuesta["data"][] = $paciente;
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo actualizar el paciente, por favor intentalo de nuevo';

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
      $query = "DELETE FROM paciente WHERE id = '$id'";
      $result = (new self())->connection->query($query);

      if ($result) {
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo eliminar el paciente, por favor intentalo de nuevo';

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
      $query = "SELECT * FROM paciente;";
      $result = (new self())->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $paciente = new self(
            $registro["id"],
            $registro["nombre"],
            $registro["apellido"],
            $registro["direccion"],
            $registro["sexo"],
            $registro["tipo_sangre"],
            $registro["id_municipio"]
          );

          $respuesta["data"][] = $paciente;
        }


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener a los pacientes registrados';

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
      $query = "SELECT * FROM paciente WHERE id = '$id';";
      $result = (new self())->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $paciente = new self(
            $registro["id"],
            $registro["nombre"],
            $registro["apellido"],
            $registro["direccion"],
            $registro["sexo"],
            $registro["tipo_sangre"],
            $registro["id_municipio"]
          );

          $respuesta["data"][] = $paciente;
        }


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener al paciente especificado';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    }
  }
}
