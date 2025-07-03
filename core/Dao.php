<?php

namespace Codez\DistrictLounge\Core;
use PDO;
use PDOException;

class DAO
{
    private $pdo = null;
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_password;

    public function __construct()
    {
        $this->db_host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->db_user = DB_USERNAME;
        $this->db_password = DB_PASSWORD;
    }

    private function connect()
    {
        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8mb4";
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            );
            try {
                $this->pdo = new PDO($dsn, $this->db_user, $this->db_password, $options);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    public function select($table, $where = '', $params = array(), $order_by = '', $limit = '', $class = '')
    {
        $sql = "SELECT * FROM `$table`";
        if ($where !== '') {
            $sql .= " WHERE $where";
        }
        if ($order_by !== '') {
            $sql .= " ORDER BY $order_by";
        }
        if ($limit !== '') {
            $sql .= " LIMIT $limit";
        }

        try {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute($params);

            if ($class !== '') {
                $results = $stmt->fetchAll(PDO::FETCH_CLASS, $class);
                $objects = array();
                foreach ($results as $result) {
                    $obj = new $class();
                    foreach ((array)$result as $key => $value) {
                        $method = 'set' . ucfirst($key);
                        if (method_exists($obj, $method)) {
                            $obj->$method($value);
                        }
                    }
                    $objects[] = $obj;
                }
                return $objects;
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            return array();
        }
    }

    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $placeholders = array_fill(0, count($keys), '?');
        $sql = "INSERT INTO `$table` (" . implode(',', $keys) . ") VALUES (" . implode(',', $placeholders) . ")";
        try {
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute(array_values($data));
        } catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage()); // Ajout temporaire pour debug
}

    }

    public function update($table, $data, $where, $params = array())
    {
        $set_parts = array();
        foreach ($data as $key => $value) {
            $set_parts[] = "`$key` = ?";
        }
        $sql = "UPDATE `$table` SET " . implode(',', $set_parts) . " WHERE $where";
        $values = array_merge(array_values($data), $params);

        try {
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($table, $where, $params = array())
    {
        $sql = "DELETE FROM `$table` WHERE $where";
        try {
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }
}
