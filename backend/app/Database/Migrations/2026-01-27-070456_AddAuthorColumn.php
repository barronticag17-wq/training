<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuthorColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researches', [
            'author' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'after'      => 'title', // This puts it right after the title column
                'null'       => true,    // Allows nulls for existing records
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('researches', 'author');
    }
}