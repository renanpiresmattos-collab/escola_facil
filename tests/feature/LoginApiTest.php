<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class LoginApiTest extends CIUnitTestCase
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

        $this->db->table('usuarios')->insert([
            'email' => 'user@example.com',
            'senha' => make_password_hash('secret'),
        ]);
    }

    public function testLoginRequiresCredentials(): void
    {
        $result = $this
            ->withBodyFormat('json')
            ->post('/api/login', ['email' => 'user@example.com']);

        $result->assertStatus(400);
        $result->assertJSONFragment([
            'status' => 'error',
            'message' => 'Informe email e password.',
        ]);
    }

    public function testLoginRejectsInvalidCredentials(): void
    {
        $result = $this
            ->withBodyFormat('json')
            ->post('/api/login', [
                'email' => 'user@example.com',
                'password' => 'wrong-password',
            ]);

        $result->assertStatus(401);
        $result->assertJSONFragment([
            'status' => 'error',
            'message' => 'Credenciais invalidas.',
        ]);
    }

    public function testLoginReturnsJwtForValidCredentials(): void
    {
        $result = $this
            ->withBodyFormat('json')
            ->post('/api/login', [
                'email' => 'user@example.com',
                'password' => 'secret',
            ]);

        $result->assertStatus(200);
        $result->assertJSONFragment([
            'status' => 'success',
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ]);

        $body = json_decode($result->getJSON(), true);

        $this->assertIsArray($body);
        $this->assertMatchesRegularExpression(
            '/^[A-Za-z0-9_-]+\\.[A-Za-z0-9_-]+\\.[A-Za-z0-9_-]+$/',
            $body['access_token'] ?? '',
        );
    }
}
