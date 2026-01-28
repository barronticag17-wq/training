<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResearchesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'abstract' => [
                'type' => 'TEXT',
            ],
            'crop_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Under Review',
            ],
            'deadline_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'submitter_id' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'pdf_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('researches');
    }

    public function down()
    {
        $this->forge->dropTable('researches');
    }
}