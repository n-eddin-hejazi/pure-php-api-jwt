<?php

namespace App\Core\Support;
use \PDO;
class QueryBuilder
{
    private static $pdo;
    public static function make(PDO $pdo)
    {
        self::$pdo = $pdo;
    }

    public static function all(string $table)
    {
        $query = "SELECT * FROM {$table}";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $rows;
    }

    public static function get(string $table, string $column, string $operator, string $value)
    {
        $query = "SELECT * FROM {$table} WHERE {$column} {$operator} ?";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute([$value]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $row;
    }

    public static function insert(string $table, array $data)
    {   
        $fields = array_keys($data);
        $values = array_values($data);
        $fields_as_string = implode(',', $fields);
        $secured_fields = str_repeat('?,', count($fields) - 1) . '?';
        
        $query = "INSERT INTO {$table} ({$fields_as_string}) VALUES ({$secured_fields})";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($values);
    }

    public static function update(string $table, array $data, string $column, string $operator, string $value)
    {
        $fields = array_keys($data);
        $values = array_values($data);
        $fields_as_string = implode(' = ?, ', $fields) . ' = ?';
        
        $query = "UPDATE {$table} SET {$fields_as_string} WHERE {$column} {$operator} '{$value}'";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($values);
    }

    public static function delete(string $table, string $column, string $operator, string $id)
    {
        $query = "DELETE FROM {$table} WHERE {$column} {$operator} '{$id}'";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();
    }
}