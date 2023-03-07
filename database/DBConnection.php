<?php
namespace Database;
use \PDO;
class DBConnection
{
    
    private static $pdo;
    public static function make(){
        
        // Set database credentials
        $db_connection = env('DB_CONNECTION');
        $host = env('DB_HOST');
        $dbname = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        
        // Create a new PDO instance
        try {
            if (!self::$pdo) {
                self::$pdo = new PDO("{$db_connection}:host={$host};dbname={$dbname}", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            
            return self::$pdo;
            // echo "Connected successfully";
        } catch (PDOException $e) {
            die(print_r($e->getMessage()));
        }
    }
}