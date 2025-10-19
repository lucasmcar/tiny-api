<?php
namespace TinyApi\core\connection;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;
    private string $driver;

    private function __construct()
    {
        $this->driver = $_ENV['DB_DRIVER'] ?? getenv('DB_DRIVER') ?? 'mysql';
        $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? '127.0.0.1';
        $db = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? '';
        $user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';
        $charset = $_ENV['DB_CHARSET'] ?? getenv('DB_CHARSET') ?? 'utf8';

        try {
            if ($this->driver === 'mysql') {
                $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
            } elseif ($this->driver === 'pgsql') {
                $dsn = "pgsql:host={$host};dbname={$db}";
            } else {
                throw new \Exception("Driver {$this->driver} não suportado");
            }

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT         => true,
            ];

            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new Exception("Erro de conexão: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(isset($params) ? $params :  []);
        return $stmt->fetchAll();
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    public function getPDO(): \PDO
    {
        return $this->pdo;
    }
}
