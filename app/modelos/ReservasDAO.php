<?php 
class ReservasDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getById($id): ?Reserva {
        if(!$stmt = $this->conn->prepare("SELECT * FROM reservas WHERE id = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $reserva = $result->fetch_object(Reserva::class);
            return $reserva;
        } else {
            return null;
        }
    }

    public function getByIdUsuario(int $idUsuario): array {
        if(!$stmt = $this->conn->prepare("SELECT * FROM reservas WHERE idUsuario = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $array_reservas = array();
        
        while($reserva = $result->fetch_object(Reserva::class)){
            $array_reservas[] = $reserva;
        }
        return $array_reservas;
    }

    public function getAll(): array {
        if(!$stmt = $this->conn->prepare("SELECT * FROM reservas")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $array_reservas = array();
        
        while($reserva = $result->fetch_object(Reserva::class)){
            $array_reservas[] = $reserva;
        }
        return $array_reservas;
    }

    public function getByDate($fecha) {
        $reservas = [];

        $sql = "SELECT id, idUsuario, idTramo, fecha FROM reservas WHERE fecha = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $fecha);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($reserva = $result->fetch_object('Reserva')) {
            $reservas[] = $reserva;
        }

        return $reservas;
    }

    public function delete($id): bool {
        if(!$stmt = $this->conn->prepare("DELETE FROM reservas WHERE id = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows == 1;
    }

    public function insert(Reserva $reserva): int|bool {
        if(!$stmt = $this->conn->prepare("INSERT INTO reservas (idUsuario, idTramo, fecha) VALUES (?,?,?)")){
            die("Error al preparar la consulta insert: " . $this->conn->error );
        }
        $idUsuario = $reserva->getIdUsuario();
        $idTramo = $reserva->getIdTramo();
        $fecha = $reserva->getFecha();
        $stmt->bind_param('iis', $idUsuario, $idTramo, $fecha);
        if($stmt->execute()){
            return $stmt->insert_id;
        }
        else{
            return false;
        }
    }
}
?>