<?php

namespace App\Controllers;

use App\Models\Catalog;

class CatalogController
{
    public function create(array $params): void
    {
        unset($params['id']);

        $model = new Catalog($params);
        $errors = $this->validate($model);

        if (!empty($errors)) {
            json_response($errors, 400);
            return;
        }

        $model->save();
        json_response($model->fields, 201);
    }

    public function read(array $params): void
    {
        // Fazer primeiro sem, depois adicionar o filtro via query string
        $model = new Catalog($params);
        $result = $model->selectAll();

        json_response($result);
    }

    public function update(array $params): void
    {
        $model = new Catalog($params);
        $errors = $this->validate($model);

        if (!empty($errors)) {
            json_response($errors, 400);
            return;
        }

        if (!$model->exists()) {
            json_response(['message' => 'Item not found'], 404);
            return;
        }

        if (!$model->update()) {
            json_response(['message' => 'Item not updated'], 500);
            return;
        }

        json_response($model->fields);
    }

    public function delete(array $params): void
    {
        $model = new Catalog($params);

        if (!$model->exists()) {
            json_response(['message' => 'Item not found'], 404);
            return;
        }

        $model->delete();
        json_response([], 204);
    }

    public function changeStatus(array $params): void
    {
        $model = new Catalog($params);

        if (!in_array($model->get('status'), Catalog::STATUSES)) {
            json_response(['message' => 'Invalid status'], 400);
            return;
        }

        if (!$model->exists()) {
            json_response(['message' => 'Item not found'], 404);
            return;
        }

        $model->updateStatus();
        $model->find();

        json_response($model->fields);
    }

    private function validate(Catalog $model): array
    {
        $errors = [];

        if (empty($model->get('name'))) {
            $errors['name'] = 'Field is required';
        }

        if (strlen($model->get('name')) > 255) {
            $errors['name'] = 'Value too long';
        }

        if (!in_array($model->get('status'), Catalog::STATUSES)) {
            $errors['status'] = 'Invalid value';
        }

        if (empty($model->get('plataform'))) {
            $errors['plataform'] = 'Field is required';
        }

        if (strlen($model->get('plataform')) > 255) {
            $errors['plataform'] = 'Value too long';
        }

        if (!is_string($model->get('url')) && !is_null($model->get('url'))) {
            $errors['url'] = 'Invalid value';
        }

        return $errors;
    }
}