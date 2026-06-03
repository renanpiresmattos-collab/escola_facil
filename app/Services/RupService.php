<?php

namespace App\Services;

use App\Models\RupModel;

class RupService
{
    public function __construct(private ?RupModel $rupModel = null)
    {
        $this->rupModel ??= new RupModel();
    }

    public function listar(array $filters = []): array
    {
        $query = $this->rupModel
            ->select('id, cpf, nome, data_nascimento, email, telefone')
            ->orderBy('nome', 'ASC');

        $term = trim((string) ($filters['q'] ?? ''));
        $field = (string) ($filters['field'] ?? 'cpf');
        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = max(1, min(50, (int) ($filters['per_page'] ?? 10)));

        if ($term !== '') {
            $normalized = $this->normalizeCpf($term);
            if ($field === 'cpf') {
                $query->like('cpf', $normalized, 'both', null, true);
            } else {
                $query->like('nome', $term);
            }
        }

        $result = $query->paginate($perPage, 'rup', $page);

        return [
            'message' => 'Registros da RUP listados com sucesso.',
            'data' => $result,
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $this->rupModel->pager->getTotal('rup'),
                'last_page' => $this->rupModel->pager->getPageCount('rup'),
            ],
        ];
    }

    public function findById(int $id): array
    {
        $registro = $this->rupModel->find($id);

        if (! is_array($registro)) {
            throw new \OutOfBoundsException('Registro da RUP nao encontrado.');
        }

        return [
            'message' => 'Registro encontrado com sucesso.',
            'data' => $registro,
        ];
    }

    public function salvar(array $input, ?int $id = null): array
    {
        $data = $this->prepareData($input);
        $this->assertCpfAvailable($data['cpf'], $id);

        if ($id === null) {
            $insertId = $this->rupModel->insert($data, true);

            return [
                'message' => 'Registro incluído com sucesso.',
                'data' => ['id' => $insertId],
            ];
        }

        if (! $this->rupModel->find($id)) {
            throw new \OutOfBoundsException('Registro da RUP nao encontrado.');
        }

        $this->rupModel->update($id, $data);

        return [
            'message' => 'Registro atualizado com sucesso.',
            'data' => ['id' => $id],
        ];
    }

    private function prepareData(array $input): array
    {
        $cpf = $this->normalizeCpf((string) ($input['cpf'] ?? ''));
        $nome = trim((string) ($input['nome'] ?? ''));

        if ($cpf === '' || strlen($cpf) !== 11) {
            throw new \InvalidArgumentException('Informe um CPF valido.');
        }

        if (! $this->isValidCpf($cpf)) {
            throw new \InvalidArgumentException('Informe um CPF valido.');
        }

        if ($nome === '') {
            throw new \InvalidArgumentException('Informe o nome.');
        }

        return [
            'cpf' => $cpf,
            'nome' => $nome,
            'data_nascimento' => $this->normalizeDate((string) ($input['data_nascimento'] ?? '')),
            'pai' => trim((string) ($input['pai'] ?? '')),
            'mae' => trim((string) ($input['mae'] ?? '')),
            'email' => trim((string) ($input['email'] ?? '')),
            'telefone' => $this->normalizePhone((string) ($input['telefone'] ?? '')),
            'endereco' => trim((string) ($input['endereco'] ?? '')),
            'bairro' => trim((string) ($input['bairro'] ?? '')),
            'cidade' => trim((string) ($input['cidade'] ?? '')),
            'uf' => strtoupper(trim((string) ($input['uf'] ?? ''))),
            'cep' => $this->normalizeCep((string) ($input['cep'] ?? '')),
            'observacoes' => trim((string) ($input['observacoes'] ?? '')),
        ];
    }

    private function assertCpfAvailable(string $cpf, ?int $ignoreId = null): void
    {
        $query = $this->rupModel->where('cpf', $cpf);

        if ($ignoreId !== null) {
            $query->where('id !=', $ignoreId);
        }

        $existing = $query->first();

        if (is_array($existing)) {
            throw new \InvalidArgumentException('Este CPF já foi cadastrado.');
        }
    }

    private function normalizeCpf(string $cpf): string
    {
        return preg_replace('/\D+/', '', $cpf) ?? '';
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }

    private function normalizeCep(string $cep): string
    {
        return preg_replace('/\D+/', '', $cep) ?? '';
    }

    private function normalizeDate(string $date): ?string
    {
        $date = trim($date);

        if ($date === '') {
            return null;
        }

        return $date;
    }

    private function isValidCpf(string $cpf): bool
    {
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += (int) $cpf[$i] * (($t + 1) - $i);
            }

            $check = (($sum * 10) % 11) % 10;
            if ($check !== (int) $cpf[$t]) {
                return false;
            }
        }

        return true;
    }
}
