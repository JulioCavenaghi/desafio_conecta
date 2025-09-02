<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected UserRepository $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function createUser(array $data)
    {
        if (isset($data['email'])) {
            $data['email'] = strtolower($data['email']);
        } else {
            throw new \InvalidArgumentException('Email é obrigatório', 422);
        }

        if (empty($data['password'])) {
            throw new \InvalidArgumentException('Senha é obrigatória', 422);
        }
        if (!array_key_exists('password_confirm', $data)) {
            throw new \InvalidArgumentException('Confirmação de senha é obrigatória', 422);
        }
        if ($data['password'] !== $data['password_confirm']) {
            throw new \InvalidArgumentException('A confirmação de senha não confere', 422);
        }

        $existing = $this->repo->findByEmail($data['email']);
        if ($existing) {
            throw new \RuntimeException('Email já cadastrado', 409);
        }

        $id = $this->repo->saveUser($data);

        return $this->repo->find($id);
    }

    public function getAllUsers()
    {
        return $this->repo->findAll();
    }

    public function getActiveUsers()
    {
        return $this->repo->getActiveUsers();
    }

    public function getUserById(int $id)
    {
        $user = $this->repo->find($id);
        if (!$user) {
            throw new \RuntimeException('Usuário não encontrado', 404);
        }
        return $user;
    }

    public function updateUser(int $id, array $data)
    {
        if (isset($data['email'])) {
            $data['email'] = strtolower($data['email']);
            $other = $this->repo->findByEmail($data['email']);
            if ($other && (int)$other['id'] !== $id) {
                throw new \RuntimeException('Email já utilizado por outro usuário', 409);
            }
        }

        if (array_key_exists('password', $data) && $data['password'] !== null && $data['password'] !== '') {
            if (!array_key_exists('password_confirm', $data)) {
                throw new \InvalidArgumentException('Confirmação de senha é obrigatória quando trocar a senha', 422);
            }
            if ($data['password'] !== $data['password_confirm']) {
                throw new \InvalidArgumentException('A confirmação de senha não confere', 422);
            }
        }

        $this->getUserById($id);

        $ok = $this->repo->update($id, $data);
        if ($ok === false) {
            throw new \RuntimeException('Erro ao atualizar usuário', 500);
        }

        return $this->repo->find($id);
    }

    public function deleteUser(int $id)
    {
        $this->getUserById($id);

        $ok = $this->repo->delete($id);
        if ($ok === false) {
            throw new \RuntimeException('Erro ao deletar usuário', 500);
        }

        return true;
    }
}