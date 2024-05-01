<?php

declare(strict_types=1);

require_once "interfaces/IModelo.php";
require_once "models/paciente.php";
require_once "models/hospitalizacion.php";
require_once "models/enfermedad.php";

class Hospitalizacion implements IModelo
{
  public Paciente $paciente;
  public Empleado $empleado;
  public Enfermedad $enfermedad;
  private $connection = null;

  function __construct(
    public ?int $id = null,
    public ?string $motivo_consulta = null,
    public ?DateTime $fecha_ingreso = null,
    public ?DateTime $fecha_alta = null,
    ?int $id_paciente = null,
    ?int $id_empleado = null,
    ?int $id_enfermedad = null
  ) {
    try {
      $this->connection = new mysqli('localhost', 'root', '12345', 'hospital');
    } catch (Exception $error) {
      echo "Ha ocurrido un error: " . $error->getMessage();
    }

    if ($id_empleado) $this->empleado = Empleado::get_by_id($id_empleado)["data"][0];
    if ($id_paciente) $this->paciente = Paciente::get_by_id($id_paciente)["data"][0];
    if ($id_enfermedad) $this->enfermedad = Enfermedad::get_by_id($id_enfermedad)["data"][0];
  }

  public function save(): array
  {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];

    try {
      $id_empleado = $this->empleado->id;
      $id_enfermedad = $this->enfermedad->id;
      $id_paciente = $this->paciente->id;
      $fecha_alta = $this->fecha_alta->format('Y-m-d');
      $fecha_ingreso = $this->fecha_ingreso->format('Y-m-d');

      $query = "INSERT INTO hospitalizacion (
        motivo_consulta, 
        fecha_ingreso, 
        fecha_alta, 
        id_paciente, 
        id_empleado, 
        id_enfermedad
      ) VALUES (
        '$this->motivo_consulta', 
        '$fecha_ingreso',
        '$fecha_alta',
        '$id_paciente',
        '$id_empleado',
        '$id_enfermedad'
      )";
      $result = $this->connection->query($query);

      if ($result) {
        $this->id = $this->connection->insert_id;
        $hospitalizacion = new self(
          $this->id,
          $this->motivo_consulta,
          $this->fecha_ingreso,
          $this->fecha_alta,
          $id_paciente,
          $id_empleado,
          $id_enfermedad,
        );

        $respuesta["data"][] = $hospitalizacion;
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo insertar el registro en hospitalizacion, por favor intentalo de nuevo';

      return $respuesta;
    } catch (Exception $error) {
      $respuesta["ok"] = false;
      $respuesta["error_message"] = $error->getMessage();

      return $respuesta;
    } finally {
      $this->connection->close();
    }
  }

  public static function update(
    int $id,
    string $motivo_consulta,
    DateTime $fecha_ingreso,
    DateTime $fecha_alta,
    int $id_paciente,
    int $id_empleado,
    int $id_enfermedad,
  ): array {
    $respuesta = [
      "ok" => true,
      "error_message" => "",
      "data" => []
    ];

    $db = new self();

    try {
      $query = "UPDATE hospitalizacion 
      SET
        motivo_consulta = '$motivo_consulta', 
        fecha_ingreso = '$fecha_ingreso', 
        fecha_alta = '$fecha_alta', 
        id_paciente = '$id_paciente', 
        id_empleado = '$id_empleado', 
        id_enfermedad = '$id_enfermedad'
      WHERE id = '$id'";

      $result = $db->connection->query($query);

      if ($result) {
        $hospitalizacion = new self(
          $id,
          $motivo_consulta,
          $fecha_ingreso,
          $fecha_alta,
          $id_paciente,
          $id_empleado,
          $id_enfermedad
        );

        $respuesta["data"][] = $hospitalizacion;


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo actualizar el registro de hospitalizacion, por favor intentalo de nuevo';

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
      $query = "DELETE FROM hospitalizacion WHERE id = '$id'";
      $result = $db->connection->query($query);

      if ($result) {
        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo eliminar el registro hospitalizacion, por favor intentalo de nuevo';


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
      $query = "SELECT * FROM hospitalizacion;";
      $result = $db->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $fecha_ingreso = new DateTime($registro["fecha_ingreso"]);
          $fecha_alta =  new DateTime($registro["fecha_alta"]);
          $hospitalizacion = new self(
            (int)$registro["id"],
            $registro["motivo_consulta"],
            $fecha_ingreso,
            $fecha_alta,
            (int)$registro["id_paciente"],
            (int)$registro["id_empleado"],
            (int)$registro["id_enfermedad"]
          );

          $respuesta["data"][] = $hospitalizacion;
        }



        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener los registros de hospitalizaciones.';

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
      $query = "SELECT * FROM hospitalizacion WHERE id = '$id';";
      $result = $db->connection->query($query);

      if ($result) {
        while ($registro = $result->fetch_assoc()) {
          $hospitalizacion = new self(
            $registro["id"],
            $registro["motivo_consulta"],
            $registro["fecha_ingreso"],
            $registro["fecha_alta"],
            $registro["id_paciente"],
            $registro["id_empleado"],
            $registro["id_enfermedad"]
          );



          $respuesta["data"][] = $hospitalizacion;
        }


        return $respuesta;
      }

      $respuesta["ok"] = false;
      $respuesta["error_message"] = 'No se pudo obtener el registro hospitalizacion especificado';

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
