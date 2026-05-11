<?php
require_once __DIR__ . '/../core/database.php';

class User
{
  private $db;
  public $name;
  public $email;
  public $password;
  public $role;
  public $headline;
  public $bio;
  public $hourly_rate;

  public function __construct()
  {
    $this->db = new Database();
    $this->db->openConnection();
  }

  public function getUserProfile($user) {
    $profile = [
      'id' => $user['id'],
      'name' => $user['name'],
      'role' => $user['role'],
      'location' => $user['country_code'] ?? '',
      'rating' => $user['reputation_score'] ?? 0,
      'joined' => $user['created_at'] ?? '',
      'headline' => $user['headline'] ?? '',
      'hourly_rate' => $user['hourly_rate'] ?? '',
      'bio' => $user['bio'] ?? ''
    ];
    return $profile;
  }

  public function getAllUsers($limit = 10)
  {
    $query = "SELECT id, name, email, role, is_verified, created_at FROM Users ORDER BY id DESC LIMIT " . (int)$limit;
    $result = $this->db->select($query);

    if ($result && is_array($result) && count($result) > 0) {
      return $result;
    }
    return [];
  }

  public function getTotalFreelancers()
  {
    $query = "SELECT COUNT(*) as total FROM Users WHERE role = 'Freelancer'";
    $result = $this->db->select($query);
    if ($result && is_array($result) && count($result) > 0) {
      return $result[0]['total'];
    }
    return 0;
  }

  public function getTotalClients()
  {
    $query = "SELECT COUNT(*) as total FROM Users WHERE role = 'Client'";
    $result = $this->db->select($query);
    if ($result && is_array($result) && count($result) > 0) {
      return $result[0]['total'];
    }
    return 0;
  }
}
