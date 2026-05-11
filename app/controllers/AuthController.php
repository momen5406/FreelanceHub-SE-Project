<?php

require_once __DIR__ . "/../core/database.php";
require_once __DIR__ . "/../models/User.php";

class AuthController
{
  protected $db;

  public function login(User $user)
  {
    $this->db = new Database;

    if ($this->db->openConnection()) {
      $safe_email = $this->db->connection->real_escape_string($user->email);
      $query = "SELECT * FROM users WHERE email='$safe_email'";
      $result = $this->db->select($query);

      if ($result === false) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION["errMsg"] = "Database query failed.";
        $this->db->closeConnection();
        return false;
      } else {
        if (count($result) == 0) {
          if (session_status() === PHP_SESSION_NONE) session_start();
          $_SESSION["errMsg"] = "Invalid Credentials.";
          $this->db->closeConnection();
          return false;
        } else {
          $hashed_password = $result[0]["password"];
          $isPassCorrect = password_verify($user->password, $hashed_password);

          if ($isPassCorrect) {
            session_start();
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION["user_id"] = $result[0]["id"];
            $_SESSION["username"] = $result[0]["name"];
            $_SESSION["role"] = $result[0]["role"];
            $_SESSION["user"] = [
              "id" => $result[0]["id"],
              "name" => $result[0]["name"],
              "email" => $result[0]["email"],
              "role" => $result[0]["role"]
            ];

            $this->db->closeConnection();
            return true;
          } else {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION["errMsg"] = "Invalid Credentials.";
            $this->db->closeConnection();
            return false;
          }
        }
      }
    } else {
      if (session_status() === PHP_SESSION_NONE) session_start();
      $_SESSION["errMsg"] = "Database connection error.";
      return false;
    }
  }

  public function register(User $user)
  {
    $this->db = new Database;
    if ($this->db->openConnection()) {
      $hashed_password = password_hash($user->password, PASSWORD_DEFAULT);
      $query = "INSERT INTO users (name, email, password, role, is_verified) VALUES ('$user->name', '$user->email', '$hashed_password', '$user->role', 1)";
      $result = $this->db->insert($query);
      if ($result != false) {
        session_start();
        $this->db->closeConnection();
        return true;
      } else {
        $_SESSION["errMsg"] = "Something went wrong... try again later";
        $this->db->closeConnection();
        return false;
      }
    } else {
      echo "Error in connection with database";
      return false;
    }
  }

  public function logout()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $_SESSION = array();
    session_destroy();

    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = '';

    if (($viewsPosition = strpos($scriptName, '/views/')) !== false) {
      $basePath = substr($scriptName, 0, $viewsPosition);
    } elseif (($publicPosition = strpos($scriptName, '/public/')) !== false) {
      $basePath = substr($scriptName, 0, $publicPosition);
    }

    header("Location: {$basePath}/views/auth/login.php");
    exit();
  }
}
