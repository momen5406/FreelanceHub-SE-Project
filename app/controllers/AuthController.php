<?php

require_once "../../app/core/database.php";
require_once "../../app/models/User.php";

class AuthController
{
  protected $db;

  public function login(User $user)
  {
    $this->db = new Database;

    if ($this->db->openConnection()) {
      $query = "SELECT * FROM users WHERE email='$user->email' AND password='$user->password'";
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
      $query = "INSERT INTO users (name, email, password, role, is_verified) VALUES ('$user->name', '$user->email', '$user->password', '$user->role', 1)";
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

    header("Location: ../../views/auth/login.php");
    exit();
  }
}