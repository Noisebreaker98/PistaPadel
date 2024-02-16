<?php 
class UsuariosDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getById($id): ?Usuario {
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        } else {
            return null;
        }
    }

    public function getByEmail($email): ?Usuario {
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        } else {
            return null;
        }
    }

    public function getAll(): array {
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuarios")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $array_usuarios = array();
        
        while($usuario = $result->fetch_object(Usuario::class)){
            $array_usuarios[] = $usuario;
        }
        return $array_usuarios;
    }

    public function delete($id): bool {
        if(!$stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows == 1;
    }

    public function insert(Usuario $usuario): int|bool {
        if(!$stmt = $this->conn->prepare("INSERT INTO usuarios (email, password, nombre) VALUES (?,?,?)")) {
            die("Error al preparar la consulta insert: " . $this->conn->error );
        }
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $nombre = $usuario->getNombre();
        $stmt->bind_param('sss', $email, $password, $nombre);
        if($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    public function update(Usuario $usuario): bool {
        if(!$stmt = $this->conn->prepare("UPDATE usuarios SET email=?, password=?, nombre=? WHERE id=?")) {
            die("Error al preparar la consulta update: " . $this->conn->error );
        }
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $nombre = $usuario->getNombre();
        $id = $usuario->getId();
        $stmt->bind_param('sssi', $email, $password, $nombre, $id);
        return $stmt->execute();
    }
}
?>