<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class AuthApi extends ResourceController
{
    protected $format = 'json';

    public function register()
    {
        $model = new UserModel();

        // 1. Get Data
        $data = [
            'username'      => $this->request->getVar('username'),
            'email'         => $this->request->getVar('email'),
            'password_hash' => $this->request->getVar('password'), // Model handles hashing
            'full_name'     => $this->request->getVar('full_name'),
            'role'          => $this->request->getVar('role'),
            'department'    => $this->request->getVar('department'),
            'bio'           => 'New user.',
        ];

        // 2. Save
        if ($model->save($data)) {
            return $this->respondCreated(['message' => 'User created successfully']);
        }

        return $this->fail($model->errors());
    }

    public function login()
    {
        $model = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // 1. Find User
        $user = $model->where('username', $username)->first();

        // 2. Verify Password
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return $this->failUnauthorized('Invalid username or password');
        }

        // 3. Return User Info (exclude password)
        unset($user['password_hash']);
        return $this->respond([
            'status' => 'success',
            'user' => $user
        ]);
    }
}