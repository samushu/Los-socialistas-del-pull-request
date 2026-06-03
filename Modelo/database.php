<?php
// ============================================================
// MODELO: database.php
// Gestiona la conexión a la base de datos MySQL usando PDO
// ============================================================
class Database {
    private static $instance = null;

    // ── Configuración de conexión ──────────────────────────
    private $host   = 'localhost';
    private $db     = 'los_socialistas';   // Cambia al nombre de tu BD
    private $user   = 'root';
    private $pass   = 'Choripansucio12';
    private $charset = 'utf8mb4';

    private $pdo;

    private function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]));
        }
    }

    // Singleton: una sola instancia de conexión
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}
?>