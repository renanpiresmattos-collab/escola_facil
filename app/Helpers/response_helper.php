<?php

use CodeIgniter\HTTP\ResponseInterface;

if (! function_exists('api_service_response')) {
    /**
     * Executa o serviço passado como callable e padroniza a resposta HTTP.
     *
     * @param ResponseInterface $response
     * @param callable $callable A chamada ao service: fn() => $service->method(...)
     */
    function api_service_response(ResponseInterface $response, callable $callable): ResponseInterface
    {
        try {
            $result = $callable();

            $code = ResponseInterface::HTTP_OK;
            $message = '';
            $data = null;
            $meta = null;

            if (is_array($result)) {
                // Extrair message se existir (payload estruturado do service)
                $message = $result['message'] ?? '';
                
                // Extrair data se existir (dados dinâmicos do banco)
                if (isset($result['data'])) {
                    $data = $result['data'];
                } else {
                    // Se não tem chave 'message' e não tem 'status', tratar todo array como data
                    if (!isset($result['message']) && !isset($result['status'])) {
                        $data = $result;
                    }
                }

                if (isset($result['meta'])) {
                    $meta = $result['meta'];
                }
            } else {
                // retorno escalar -> usar como mensagem
                $message = (string) $result;
            }

            $payload = [
                'status' => 'success',
                'message' => $message,
            ];

            if ($data !== null) {
                $payload['data'] = $data;
            }

            if ($meta !== null) {
                $payload['meta'] = $meta;
            }

            return $response->setStatusCode($code)->setJSON($payload);
        } catch (\InvalidArgumentException $e) {
            return $response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
        } catch (\DomainException $e) {
            return $response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
        } catch (\OutOfBoundsException $e) {
            return $response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            return $response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Erro interno.',
                ]);
        }
    }
}
