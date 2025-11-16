<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../app/libraries/admin/Model.php';
require_once '../app/models/admin/User.php';

use App\Models\Admin\User;

class UsersController
{
    private mysqli $mysqli; // ✅ Declare the property to avoid "dynamic property" warning
    private User $user;     // ✅ Declare your User model

    public function __construct()
    {
        global $mysqli; // ✅ Use the global mysqli connection if it’s defined elsewhere
        if (!isset($mysqli)) {
            // Fallback: create the connection if not already available
            $mysqli = new mysqli('localhost', 'user', 'password', 'database');
            if ($mysqli->connect_errno) {
                die("Failed to connect to MySQL: " . $mysqli->connect_error);
            }
        }

        $this->mysqli = $mysqli;
        $this->user = new User();
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

            $user = $this->user->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
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
            include "../app/views/admin/auth/login.php";
        }
    }
}
?>
