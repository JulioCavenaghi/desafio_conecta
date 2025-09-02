<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPasswordWhenPresent'];

    // Validation
    protected array $validationRulesInsert = [
        'name'             => 'required|min_length[3]|max_length[255]',
        'email'            => 'required|valid_email|max_length[255]|is_unique[users.email]',
        'password'         => 'required|min_length[8]',
        'password_confirm' => 'required|matches[password]',
        'status'           => 'permit_empty|in_list[1,0]',
    ];

    protected array $validationRulesUpdate = [
        'id'               => 'required|is_natural_no_zero',
        'name'             => 'required|min_length[3]|max_length[255]',
        'email'            => 'required|valid_email|max_length[255]|is_unique[users.email,id,{id}]',
        'password'         => 'permit_empty|min_length[8]',
        'password_confirm' => 'permit_empty|matches[password]',
        'status'           => 'permit_empty|in_list[1,0]',
    ];

    public function getInsertValidation(): array
    {
        return [
            'rules'  => $this->validationRulesInsert,
            'errors' => $this->validationMessagesInsert,
        ];
    }

    public function getUpdateValidation(?int $id = null): array
    {
        $rules  = $this->validationRulesUpdate;
        $errors = $this->validationMessagesUpdate;

        if ($id !== null) {
            array_walk($rules, function (&$rule) use ($id) {
                if (is_string($rule)) {
                    $rule = str_replace('{id}', (string) $id, $rule);
                }
            });
        }

        return [
            'rules'  => $rules,
            'errors' => $errors,
        ];
    }

    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        if (isset($data['data']['password_confirm'])) {
            unset($data['data']['password_confirm']);
        }
        return $data;
    }

    protected function hashPasswordWhenPresent(array $data): array
    {
        if (!empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }

        if (isset($data['data']['password_confirm'])) {
            unset($data['data']['password_confirm']);
        }

        return $data;
    }
}