<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsArchivedColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researches', [
            'is_archived' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0, // 0 = Active, 1 = Archived
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('researches', 'is_archived');
    }
}