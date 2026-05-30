<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'cpf' => [
                'type' => 'VARCHAR',
                'constraint' => 11,
                'null' => false,
            ],
            'apelido' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'senha' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'data_insert' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'data_update' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
                'on update' => 'CURRENT_TIMESTAMP',
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'default' => 'ATIVO',
            ],
            'nivel' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'default' => 'ALUNO',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('cpf');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('usuarios', true);

        $this->db->query("ALTER TABLE usuarios ADD CONSTRAINT usuarios_status_check CHECK (status IN ('ATIVO', 'INATIVO'))");
        $this->db->query("ALTER TABLE usuarios ADD CONSTRAINT usuarios_nivel_check CHECK (nivel IN ('ADMINISTRADOR', 'PROFESSOR', 'RESPONSAVEL', 'ALUNO'))");
    }

    public function down()
    {
        $this->forge->dropTable('usuarios', true);
    }
}
