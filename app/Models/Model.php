<?php

namespace App\Models;

use Exception;
use PDO;

class Model
{
    public array $fields = [];
    protected array $fieldNames = [];

    private PDO $pdo;

    /**
     * O PHP costumava vir com a extensão original do MySQL built-in que suporta as versões mais antigas do MySQL.
     * No entanto, essa extensão foi reprovada em favor do MySQLi (i de improved/melhorado).
     * Ao mesmo tempo, o PHP continuou a evoluir e a extensão PDO (PHP Data Objects) foi introduzida para se tornar uma
     * interface comum para acessar muitos tipos de banco de dados.
     *
     * A principal vantagem do PDO sobre o MySQLi está no suporte ao banco de dados.
     * O PDO suporta 12 diferentes tipos de banco de dados, em oposição ao MySQLi, que suporta apenas MySQL.
     *
     * https://www.php.net/manual/pt_BR/book.pdo.php
     * https://www.devmedia.com.br/introducao-ao-php-pdo/24973
     */

    public function __construct(array $values = [])
    {
        $this->connect();
        $this->setValues($values);
    }

    private function connect(): void
    {
        $driver = $_ENV['DB_DRIVER'] ?? null;
        $host = $_ENV['DB_HOST'] ?? null;
        $user = $_ENV['DB_USER'] ?? null;
        $pass = $_ENV['DB_PASS'] ?? null;
        $name = $_ENV['DB_NAME'] ?? null;

        if (empty($driver) || empty($host) || empty($user) || empty($name)) {
            throw new Exception('Database misconfiguration');
        }

        $this->pdo = new PDO("$driver:host=$host;dbname=$name", $user, $pass);
    }

    public function get(string $fieldName)
    {
        return $this->fields[$fieldName] ?? null;
    }

    protected function fetchAll(string $query, array $filters): array
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($filters);

        // https://www.php.net/manual/pt_BR/pdostatement.fetch.php
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result ?? [];
    }

    protected function fetchOne(string $query, array $filters): ?Model
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($filters);

        // https://www.php.net/manual/pt_BR/pdostatement.fetch.php
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->setValues($row) : null;
    }

    protected function insert(string $query, array $body): int
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($body);

        return $this->pdo->lastInsertId();
    }

    protected function executeReturnAffected(string $query, array $body): int
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($body);

        return $statement->rowCount();
    }

    protected function setValues(array $values): self
    {
        if (empty($values)) {
            return $this;
        }

        foreach ($this->fieldNames as $fieldName) {
            if (array_key_exists($fieldName, $values)) {
                $this->fields[$fieldName] = empty($values[$fieldName]) ? null : $values[$fieldName];
            }
        }

        return $this;
    }
}