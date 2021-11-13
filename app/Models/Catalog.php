<?php

namespace App\Models;

class Catalog extends Model
{
    const STATUSES = [
        'PENDING',
        'IN_PROGRESS',
        'DONE'
    ];

    protected string $table = 'catalog';

    public ?string $name; // obrigatorio
    public ?string $status; // obrigatorio
    public ?string $plataform; // obrigatorio
    public ?string $url = null; // opcional

    public function selectAll(): array
    {
        // Fazer primeiro sem, depois adicionar o filtro via query string
        $filters = [];
        $filterValues = [];

        // O isset vai ignorar valores null
        // Como nenhum dos valores a ser usado no where pode ser NULL, não precisa preocupar com isso no momento,
        // mas se algum for opcional então vai precisar alterar

        if (isset($this->name)) {
            $filters[] = "name LIKE ?";
            $filterValues[] = "%$this->name%";
        }

        if (isset($this->id)) {
            $filters[] = "id = ?";
            $filterValues[] = $this->id;
        }

        if (isset($this->status)) {
            $filters[] = "status = ?";
            $filterValues[] = $this->status;
        }

        if (isset($this->plataform)) {
            $filters[] = "plataform = ?";
            $filterValues[] = $this->plataform;
        }

        $query = "SELECT * FROM {$this->table}";

        if (!empty($filters)) {
            $query .= " WHERE " . implode(' AND ', $filters);
        }

        // var_dump($query, $filterValues, $this->toArray());

        return $this->fetchAll($query, $filterValues);
    }

    public function find(): self
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $row = $this->fetchOne($query, [$this->id]);

        if ($row) {
            $this->setValues((array) $row);
        }

        return $this;
    }

    public function exists(): bool
    {
        $query = "SELECT id FROM {$this->table} WHERE id = ?";
        $value = $this->fetchOne($query, [$this->id]);
        return !is_null($value);
    }

    public function save(): void
    {
        $query = "INSERT INTO {$this->table} (name, status, plataform, url) VALUES (?, ?, ?, ?)";
        $body = [$this->name, $this->status, $this->plataform, $this->url];

        $id = $this->insert($query, $body);
        $this->id = $id;
    }

    public function update(): int
    {
        $query = "UPDATE {$this->table} SET name = :name, status = :status, plataform = :plataform, url = :url WHERE id = :id";
        $body = $this->toArray();

        return $this->executeReturnAffected($query, $body);
    }

    public function updateStatus(): int
    {
        $query = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        $body = [$this->status, $this->id];

        return $this->executeReturnAffected($query, $body);
    }

    public function delete(): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";

        $affectedRows = $this->executeReturnAffected($query, [$this->id]);
        return $affectedRows === 1;
    }

}