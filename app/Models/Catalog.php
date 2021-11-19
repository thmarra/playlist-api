<?php

namespace App\Models;

class Catalog extends Model
{
    const STATUSES = [
        'PENDING',
        'IN_PROGRESS',
        'DONE'
    ];

    protected array $fieldNames = ['id', 'name', 'status', 'plataform', 'url'];

    public function selectAll(): array
    {
        // Fazer primeiro sem, depois adicionar o filtro via query string
        $filters = [];
        $filterValues = [];

        // O isset vai ignorar valores null
        // Como nenhum dos valores a ser usado no where pode ser NULL, não precisa preocupar com isso no momento,
        // mas se algum for opcional então vai precisar alterar

        if ($this->get('name')) {
            $filters[] = "name LIKE ?";
            $filterValues[] = "%" . $this->fields['name'] . "%";
        }

        if ($this->get('id')) {
            $filters[] = "id = ?";
            $filterValues[] = $this->fields['id'];
        }

        if ($this->get('status')) {
            $filters[] = "status = ?";
            $filterValues[] = $this->fields['status'];
        }

        if ($this->get('plataform')) {
            $filters[] = "plataform = ?";
            $filterValues[] = $this->fields['plataform'];
        }

        $query = "SELECT * FROM catalog";

        if (!empty($filters)) {
            $query .= " WHERE " . implode(' AND ', $filters);
        }

        // var_dump($query, $filterValues, $this->fields);

        return $this->fetchAll($query, $filterValues);
    }

    public function find(): void
    {
        $query = "SELECT * FROM catalog WHERE id = ?";
        $this->fetchOne($query, [$this->get('id')]);
    }

    public function exists(): bool
    {
        $query = "SELECT id FROM catalog WHERE id = ?";
        $value = $this->fetchOne($query, [$this->get('id')]);
        return !is_null($value);
    }

    public function save(): void
    {
        $query = "INSERT INTO catalog (name, status, plataform, url) VALUES (?, ?, ?, ?)";
        $body = [$this->get('name'), $this->get('status'), $this->get('plataform'), $this->get('url')];

        $this->fields['id'] = $this->insert($query, $body);
    }

    public function update(): int
    {
        $query = "UPDATE catalog SET name = :name, status = :status, plataform = :plataform, url = :url WHERE id = :id";
        return $this->executeReturnAffected($query, $this->fields);
    }

    public function updateStatus(): int
    {
        $query = "UPDATE catalog SET status = ? WHERE id = ?";
        $body = [$this->get('status'), $this->get('id')];

        return $this->executeReturnAffected($query, $body);
    }

    public function delete(): bool
    {
        $query = "DELETE FROM catalog WHERE id = ?";

        $affectedRows = $this->executeReturnAffected($query, [$this->get('id')]);
        return $affectedRows === 1;
    }

}