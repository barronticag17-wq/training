<?php

namespace App\Models;

use CodeIgniter\Model;

class ResearchModel extends Model
{
    protected $table            = 'researches';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['title', 'author', 'abstract', 'doc_type', 'status', 'deadline_date', 'submitter_id', 'pdf_path', 'is_archived'];
    protected $returnType       = 'array';
    protected $useTimestamps = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}