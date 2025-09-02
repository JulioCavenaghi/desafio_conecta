<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\UserService;

class Users extends BaseController
{
    protected UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function index()
    {
        $users = $this->service->getAllUsers();
        return $this->response->setJSON($users);
    }

    public function active()
    {
        $users = $this->service->getActiveUsers();
        return $this->response->setJSON($users);
    }

    public function show($id = null)
    {
        try {
            $user = $this->service->getUserById((int)$id);
            return $this->response->setJSON($user);
        } catch (\RuntimeException $e) {
            $code = $e->getCode() ?: 404;
            return $this->response->setStatusCode($code)->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        $data = $this->request->getJSON(true) ?? [];

        try {
            $user = $this->service->createUser($data);
            return $this->response->setStatusCode(201)->setJSON($user);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode($e->getCode() ?: 422)
                                  ->setJSON(['errors' => ['validation' => $e->getMessage()]]);
        } catch (\RuntimeException $e) {
            $code = $e->getCode() ?: 400;
            $msg  = $e->getMessage();

            $decoded = json_decode($msg, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $this->response->setStatusCode($code)->setJSON(['errors' => $decoded]);
            }

            return $this->response->setStatusCode($code)->setJSON(['error' => $msg]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Erro interno']);
        }
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON(true) ?? [];

        try {
            $user = $this->service->updateUser((int)$id, $data);
            return $this->response->setJSON($user);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode($e->getCode() ?: 422)
                                  ->setJSON(['errors' => ['validation' => $e->getMessage()]]);
        } catch (\RuntimeException $e) {
            $code = $e->getCode() ?: 400;
            $msg  = $e->getMessage();
            $decoded = json_decode($msg, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $this->response->setStatusCode($code)->setJSON(['errors' => $decoded]);
            }
            return $this->response->setStatusCode($code)->setJSON(['error' => $msg]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Erro interno']);
        }
    }

    public function delete($id = null)
    {
        try {
            $this->service->deleteUser((int)$id);
            return $this->response->setStatusCode(204);
        } catch (\RuntimeException $e) {
            $code = $e->getCode() ?: 400;
            return $this->response->setStatusCode($code)->setJSON(['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Erro interno']);
        }
    }
}