<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        // Configuração da conexão (DSN)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            // Em produção, não mostre o erro detalhado, grave em log
            // Por enquanto, vamos formatar bonitinho se der erro
            die('<div style="color: red; padding: 20px; border: 1px solid red; background: #ffeeee;">
                <strong>Erro Crítico de Conexão:</strong> Não foi possível conectar ao banco de dados. 
                Verifique o arquivo config.php.
            </div>');
        }
    }

    // Prepara a query
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Vincula os valores
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Executa a query
    public function execute() {
        return $this->stmt->execute();
    }

    // Retorna vários resultados (Array)
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Retorna um único resultado (Linha)
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Retorna o número de linhas afetadas
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Retorna o ID do último registro inserido
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}