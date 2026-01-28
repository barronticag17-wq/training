<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

class UserApi extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    public function index() {
        return $this->respond($this->model->findAll());
    }

    public function create() {
        $json = $this->request->getJSON();
        if ($this->model->insert($json)) {
            return $this->respondCreated(['status' => 'success']);
        }
        return $this->fail('Failed to save');
    }

    public function delete($id = null)
    {
        $model = new \App\Models\UserModel();
        
        // Check if the user exists
        if ($model->find($id)) {
            $model->delete($id);
            return $this->respondDeleted(['status' => 'User deleted']);
        }

        return $this->failNotFound('User not found');
    }

    public function update($id = null)
    {
        $model = new \App\Models\UserModel();
        $json = $this->request->getJSON();

        // Check if the user exists first
        if (!$model->find($id)) {
            return $this->failNotFound('User not found');
        }

        $data = [
            'username' => $json->username,
            'email'    => $json->email,
        ];

        if ($model->update($id, $data)) {
            return $this->respond(['status' => 'User updated successfully']);
        }

        return $this->fail('Update failed');
    }
}