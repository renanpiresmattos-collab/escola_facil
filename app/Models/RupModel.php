<?php

namespace App\Models;

use CodeIgniter\Model;

class RupModel extends Model
{
    protected $table = 'rup';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'cpf',
        'nome',
        'data_nascimento',
        'pai',
        'mae',
        'email',
        'telefone',
        'endereco',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'observacoes',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
