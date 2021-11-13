<?php

namespace App\Models;

use Exception;
use PDO;
use ReflectionObject;
use ReflectionProperty;

class Model
{
    public int $id;
    protected string $table;

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

    public function toArray(): array
    {
        $fields = $this->getFields();
        $values = [];

        foreach($fields as $field) {
            $fieldName = $field->getName();
            $values[$fieldName] = $this->$fieldName ?? null;
        }

        return $values;
    }

    private function getFields(): array
    {
        return (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);
    }

    protected function setValues(array $values): self
    {
        if (empty($values)) {
            return $this;
        }

        $fields = $this->getFields();

        foreach ($fields as $field) {
            // https://www.php.net/manual/en/class.reflectionproperty.php

            $fieldName = $field->getName();

            if (isset($values[$fieldName])) {
                $this->$fieldName = empty($values[$fieldName]) ? null : $values[$fieldName];
            }
        }

        return $this;
    }
}