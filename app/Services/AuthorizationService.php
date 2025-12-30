<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AuthorizationService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function isAuthorized(): bool
    {
        try {
            $response = $this->client->request('GET', 'https://util.devi.tools/api/v2/authorize');
            $body = json_decode($response->getBody(), true);

            // A API retorna algo como { "status": "success", "data": { "authorization": true } }
            // Ajuste conforme o retorno real da API se necessário, mas o padrão costuma ser verificar o código 200 ou um campo específico.
            // Para o teste, vamos confiar no status code 200 ou campo 'authorization' se existir.

            // Verificando payload típico do mock fornecido:
            return isset($body['data']['authorization']) && $body['data']['authorization'] === true;

        } catch (\Exception $e) {
            // Se o serviço estiver fora do ar, por segurança negamos a transação?
            // Ou lançamos erro? No contexto bancário, falha de comunicação = nega transação.
            Log::error("Erro ao conectar com autorizador externo: " . $e->getMessage());
            return false;
        }
    }
}
