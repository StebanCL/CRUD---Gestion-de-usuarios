<?php
class DatabaseCN {
    private static $host = "sql113.infinityfree.com"; 
    private static $db_name = "if0_41161293_gestion_clientes"; 
    private static $username = "if0_41161293";
    private static $password = "mimikyuu19";
    private static $conexion = null;

    public static function conectar() {
        if (self::$conexion === null) {
            try {
                // Configuramos el DSN con charset para soporte de caracteres especiales
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4";
                
                // Opciones adicionales para mayor seguridad y rendimiento
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                self::$conexion = new PDO($dsn, self::$username, self::$password, $options);
                
            } catch (PDOException $e) {
                // En producción podrías querer registrar esto en un log en lugar de mostrarlo
                die("Error crítico de conexión: " . $e->getMessage());
            }
        }
        return self::$conexion;
    }
}
// No cerramos la etiqueta PHP para evitar el envío accidental de espacios en blanco