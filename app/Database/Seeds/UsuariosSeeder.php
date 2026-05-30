<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        helper('password');

        $this->db->table('usuarios')->insert([
            'cpf' => '00000000001',
            'apelido' => 'TESTE',
            'email' => 'email@gmail.com',
            // Senha em texto puro para este seed: teste123
            'senha' => make_password_hash('teste123'),
            'status' => 'ATIVO',
            'nivel' => 'ADMINISTRADOR',
            'data_insert' => date('Y-m-d H:i:s'),
            'data_update' => date('Y-m-d H:i:s'),
        ]);
    }
}
