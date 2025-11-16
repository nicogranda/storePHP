<?php
session_status() === PHP_SESSION_NONE && session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/models//Model.php';
require_once 'app/models/admin/Auth.php';

use App\Models\Admin\Auth;

class AuthsController
{
    private mysqli $mysqli;
    private Auth $auth; // ✅ Declarada, evita el warning

    public function __construct()
    {
        global $mysqli;

        if (!isset($mysqli)) {
            $mysqli = new mysqli('localhost', 'user', 'password', 'database');
            if ($mysqli->connect_errno) {
                die("Error al conectar con MySQL: " . $mysqli->connect_error);
            }
        }

        $this->mysqli = $mysqli;
        $this->auth = new Auth(); // ✅ Ahora está declarada correctamente
    }

    public function auth()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header("Location: index.php?page=admin&action=auth");
                exit();
            }

            $user = $this->auth->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {

                if ($user['role'] !== 'admin') {
                    $_SESSION['error'] = "Acceso denegado. Solo los administradores pueden entrar.";
                    header("Location: index.php?page=admin&action=auth");
                    exit();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = htmlspecialchars($user['username']);
                $_SESSION['user_nick'] = htmlspecialchars(($user['name'] ?? '') . ' ' . ($user['lastname'] ?? ''));

                header("Location: admin/index.php");
                exit();
            }

            $_SESSION['error'] = $user ? "Password incorrecto." : "Usuario no encontrado.";
            header("Location: index.php?page=admin&action=auth");
            exit();
        } else {
            include "app/views/admin/auth/login.php";
        }
    }
}
?>
