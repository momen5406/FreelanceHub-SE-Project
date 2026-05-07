<?php

class RoleHelper
{

    public static function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
    }

    public static function isClient()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Client';
    }

    public static function isFreelancer()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Freelancer';
    }

    public static function isFinancial()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Financial';
    }

    public static function isTechSupport()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Tech Support';
    }

    public static function isDisputeMediator()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Dispute Mediator';
    }

    public static function requireAdmin()
    {
        if (!self::isAdmin()) {
            header("Location: ../../views/auth/login.php");
            exit();
        }
    }

    public static function requireClient()
    {
        if (!self::isClient()) {
            header("Location: ../../views/auth/login.php");
            exit();
        }
    }

    public static function requireFreelancer()
    {
        if (!self::isFreelancer()) {
            header("Location: ../../views/auth/login.php");
            exit();
        }
    }

    public static function requireFinancial()
    {
        if (!self::isFinancial()) {
            header("Location: ../../views/auth/login.php");
            exit();
        }
    }

    public static function requireTechSupport()
    {
        if (!self::isTechSupport()) {
            header("Location: ../../views/auth/login.php");
            exit();
        }
    }

    public static function requireDisputeMediator()
    {
        if (!self::isDisputeMediator()) {
            header("Location: ../../views/auth/login.php");
            exit();
        }
    }

    public static function getUserRole($userId, $db)
    {
        $query = "SELECT role FROM Users WHERE id = $userId";
        $result = $db->select($query);
        return $result ? $result[0]['role'] : 'Client';
    }

    public static function updateUserRole($userId, $newRole, $db)
    {
        $allowedRoles = ['Admin', 'Client', 'Freelancer', 'Financial', 'Tech Support', 'Dispute Mediator'];
        if (!in_array($newRole, $allowedRoles)) {
            return false;
        }
        $query = "UPDATE Users SET role = '$newRole' WHERE id = $userId";
        return $db->update($query);
    }
}