<?php 

class Database {
  public $dbHost = "localhost";
  public $dbUser = "root";
  public $dbPassword = "";
  public $dbName = "freelance_platform";
  public $connection;

  public function openConnection() {
    $this->connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);

    if ($this->connection->connect_error) {
      echo "Error in Connection: " . $this->connection->connect_error;
      return false;
    } else {
      return true;
    }
  }

  public function closeConnection() {
    if ($this->connection) {
      $this->connection->close();
    } else {
      echo "Connection is not opened.";
    }
  }

  public function select($query) {
    $result = $this->connection->query($query);
    if (!$result) {
      echo "Error: " . mysqli_error($this->connection);
      return false;
    } else {
      return $result->fetch_all(MYSQLI_ASSOC);
    }
  }

  public function insert($query) {
    $result = $this->connection->query($query);
    if (!$result) {
      echo "Error: " . mysqli_error($this->connection);
      return false;
    } else {
      return $this->connection->insert_id;
    }
  }
  public function update($query)
  {
    $result = $this->connection->query($query);
    if (!$result) {
      echo "Error: " . mysqli_error($this->connection);
      return false;
    } else {
      return $this->connection->affected_rows;
    }
  }

  public function delete($query)
  {
    $result = $this->connection->query($query);
    if (!$result) {
      echo "Error: " . mysqli_error($this->connection);
      return false;
    } else {
      return $this->connection->affected_rows;
    }
  }

  public function escapeString($string)
  {
    return $this->connection->real_escape_string($string);
  }

  public function getLastError()
  {
    return $this->connection->error;
  }

}

?>