<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ResearchApi extends ResourceController
{
    protected $modelName = 'App\Models\ResearchModel';
    protected $format    = 'json';

    public function create()
    {
        // 1. Get the uploaded file
        $file = $this->request->getFile('pdf_file');
        $filePath = null;

        // 2. Validate and Move File
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            // Move to 'writable/uploads/researches'
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/researches', $newName);
            $filePath = $newName; // Save this filename to DB
        }

        // 3. Prepare Data for DB
        $data = [
            'title'         => $this->request->getPost('title'),
            'author'        => $this->request->getPost('author'),
            'abstract'      => $this->request->getPost('abstract'),
            'crop_type'     => $this->request->getPost('crop_type'),
            'status'        => $this->request->getPost('status'),
            'deadline_date' => $this->request->getPost('deadline_date'),
            'submitter_id'  => $this->request->getPost('submitter_id'),
            'pdf_path'      => $filePath,
        ];

        // 4. Save to Database
        if ($this->model->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'id' => $this->model->getInsertID()]);
        }

        return $this->fail('Failed to save research');
    }

    public function index()
    {
        // 1. Get parameters from the URL (sent by Vue)
        $search   = $this->request->getGet('search');
        $cropType = $this->request->getGet('crop_type');

        // 2. Add Search Conditions (if user typed something)
        if ($search) {
            $this->model->groupStart() // Start a bracket ( for OR logic
                        ->like('title', $search)
                        ->orLike('abstract', $search)
                        ->orLike('author', $search) // Assuming you have an author column
                        ->groupEnd(); // End bracket )
        }

        // 3. Add Filter Condition (if user selected a specific crop)
        if ($cropType && $cropType !== 'All') {
            $this->model->where('crop_type', $cropType);
        }

        // 4. Finally, Execute the query
        // We keep 'orderBy' to ensure newest items show first
        $data = $this->model->orderBy('id', 'DESC')->findAll();

        return $this->respond($data);
    }

    public function serveFile($filename)
    {
        $path = WRITEPATH . 'uploads/researches/' . $filename;

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        readfile($path);
        exit;
    }
    
    public function archive($id = null)
    {
        // Check if ID exists
        if (!$this->model->find($id)) {
            return $this->failNotFound('Research not found');
        }

        // Get the JSON body (to allow restoring later too, e.g. { "is_archived": 0 })
        // But for this specific button, we default to 1 (True)
        $data = ['is_archived' => 1];

        if ($this->model->update($id, $data)) {
            return $this->respond(['message' => 'Research archived successfully']);
        }

        return $this->fail('Failed to archive research');
    }
}