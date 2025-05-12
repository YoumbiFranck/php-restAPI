<?php
class Database
{
    private $host;
    private $user;
    private $password;
    private $dbname;

    public function __construct(string $host, string $user, string $password, string $dbname)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
    }
    
    public function getConnection(): PDO
    {
        
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            #$pdo = new PDO($dsn, $this->user, $this->password);
            $pdo = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        
    }
}