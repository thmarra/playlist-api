<?php

namespace App\Controllers;

use App\Models\Catalog;

class CatalogController
{
    public function create(array $params): void
    {
        $item = new Catalog($params);
        $errors = $this->validate($item);

        if (!empty($errors)) {
            json_response($errors, 400);
            return;
        }

        $item->save();
        json_response($item->toArray(), 201);
    }

    public function read(array $params): void
    {
        // Fazer primeiro sem, depois adicionar o filtro via query string
        $model = new Catalog($params);
        $values = $model->selectAll();

        json_response($values);
    }

    public function update(array $params): void
    {
        $item = new Catalog($params);
        $errors = $this->validate($item);

        if (!empty($errors)) {
            json_response($errors, 400);
            return;
        }

        if (!$item->exists()) {
            json_response(['message' => 'Item not found'], 404);
            return;
        }

        if ($item->update()) {
            json_response($item->toArray());
            return;
        }

        json_response(['message' => 'Item not updated'], 500);
    }

    public function delete(array $params): void
    {
        $item = new Catalog($params);

        if (!$item->exists()) {
            json_response(['message' => 'Item not found'], 404);
            return;
        }

        if ($item->delete()) {
            json_response([], 204);
            return;
        }

        json_response(['message' => 'Item not updated'], 500);
    }

    public function changeStatus(array $params): void
    {
        $item = new Catalog($params);

        if (!in_array($item->status, Catalog::STATUSES)) {
            json_response(['message' => 'Invalid status'], 400);
            return;
        }

        if (!$item->exists()) {
            json_response(['message' => 'Item not found'], 404);
            return;
        }

        $item->updateStatus();
        $item->find();

        json_response($item->toArray());
    }

    private function validate(Catalog $item): array
    {
        $errors = [];

        if (empty($item->name)) {
            $errors['name'] = 'Field is required';
        }

        if (strlen($item->name) > 255) {
            $errors['name'] = 'Value too long';
        }

        if (!in_array($item->status, Catalog::STATUSES)) {
            $errors['status'] = 'Invalid value';
        }

        if (empty($item->plataform)) {
            $errors['plataform'] = 'Field is required';
        }

        if (strlen($item->plataform) > 255) {
            $errors['plataform'] = 'Value too long';
        }

        if (!is_string($item->url) && !is_null($item->url)) {
            $errors['url'] = 'Invalid value';
        }

        return $errors;
    }
}