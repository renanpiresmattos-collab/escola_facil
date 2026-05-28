<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class ListUsuariosApiTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        helper('password');

        $table = $this->db->prefixTable('usuarios');

        $this->db->query('DROP TABLE IF EXISTS ' . $table);
        $this->db->query(
            'CREATE TABLE ' . $table . ' (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email VARCHAR(255) NOT NULL,
                senha VARCHAR(255) NOT NULL
            )',
        );

        $this->db->table('usuarios')->insertBatch([
            [
                'email' => 'admin@example.com',
                'senha' => make_password_hash('secret'),
            ],
            [
                'email' => 'user@example.com',
                'senha' => make_password_hash('secret'),
            ],
        ]);
    }

    public function testListUsuariosRequiresBearerToken(): void
    {
        $result = $this->get('/api/usuarios');

        $result->assertStatus(401);
        $result->assertJSONFragment([
            'status' => 'error',
            'message' => 'Token invalido ou ausente.',
        ]);
    }

    public function testListUsuariosReturnsUsersForValidBearerToken(): void
    {
        $login = $this
            ->withBodyFormat('json')
            ->post('/api/login', [
                'email' => 'admin@example.com',
                'password' => 'secret',
            ]);

        $body = json_decode($login->getJSON(), true);

        $this->assertIsArray($body);

        $result = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . $body['access_token'],
            ])
            ->get('/api/usuarios');

        $result->assertStatus(200);
        $result->assertJSONFragment([
            'status' => 'success',
        ]);

        $responseBody = json_decode($result->getJSON(), true);

        $this->assertIsArray($responseBody);
        $this->assertSame([
            [
                'id' => 1,
                'email' => 'admin@example.com',
            ],
            [
                'id' => 2,
                'email' => 'user@example.com',
            ],
        ], $responseBody['usuarios']);
        $this->assertArrayNotHasKey('senha', $responseBody['usuarios'][0]);
    }
}
