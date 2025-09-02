<?php

namespace App\Repositories;

use App\Models\UserModel;

class UserRepository
{
    protected UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findAll()
    {
        return $this->model->findAll();
    }

    public function getActiveUsers()
    {
        return $this->model->where('status', 1)->findAll();
    }

    public function saveUser(array $data)
    {
        $validation = $this->model->getInsertValidation();
        $this->model->setValidationRules($validation['rules'], $validation['errors']);

        $id = $this->model->insert($data, true);

        if ($id === false) {
            $errors = $this->model->errors() ?: ['validation' => 'Erro de validação desconhecido'];
            throw new \RuntimeException(json_encode($errors), 422);
        }

        return $id;
    }

    public function update($id, array $data)
    {
        $validation = $this->model->getUpdateValidation((int)$id);
        $this->model->setValidationRules($validation['rules'], $validation['errors']);

        $dataWithId = $data;
        $dataWithId['id'] = $id;

        $result = $this->model->update($id, $dataWithId);

        if ($result === false) {
            $errors = $this->model->errors() ?: ['validation' => 'Erro de validação desconhecido'];
            throw new \RuntimeException(json_encode($errors), 422);
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }
}