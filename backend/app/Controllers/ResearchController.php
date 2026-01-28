<?php

namespace App\Controllers;

use App\Models\ResearchModel;
use CodeIgniter\RESTful\ResourceController;

class ResearchController extends ResourceController
{
    public function getMySubmissions($userId = null)
    {
        $model = new ResearchModel();

        // Filter by submitter_id and exclude archived items
        $data = $model->where('submitter_id', $userId)
                      ->where('is_archived', 0)
                      ->orderBy('created_at', 'DESC')
                      ->findAll();

        if (!$data) {
            return $this->respond([]); // Return empty array if no results
        }

        return $this->respond($data);
    }
}