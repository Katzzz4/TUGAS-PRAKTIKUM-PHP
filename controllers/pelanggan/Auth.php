<?php
require_once('Connection.php');

class Auth {
    
    private $conn;

    public function __construct(){
        $db = new Connection();
        $this->conn = $db->getConnection();
    }

    public function login($email, $password){
        if (!$this->conn) {
            die("Koneksi ke database gagal.");
        }

        $stmt = $this->conn->prepare("SELECT * FROM pengguna WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            if(password_verify($password, $row['password'])){
                session_start();
                $_SESSION['id'] = $row['id'];
                header("Location: sukses_login.php");
                exit();
            } else {
                echo "Password salah";
                return false;
            }
        } else {
            echo "Email tidak ditemukan";
            return false;
        }
    }

    public function logout(){
        session_start();
        session_destroy();
        header("Location: ../../view/login/login.php");
    }
}



