<?php

namespace App\Jobs;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SendNotificationJob extends Job
{
    public $userId;
    public $message;

    // Configurações de retentativa automática (se falhar, tenta 3 vezes)
    public $tries = 3;

    public function __construct(int $userId, string $message)
    {
        $this->userId = $userId;
        $this->message = $message;
    }

    public function handle()
    {
        $user = User::find($this->userId);

        if (!$user) {
            return;
        }

        // Simulação do envio externo
        $client = new Client();

        try {
            // O mock espera um POST
            $response = $client->post('https://util.devi.tools/api/v1/notify', [
                'json' => [
                    'email' => $user->email,
                    'message' => $this->message
                ]
            ]);

            Log::info("Notificação enviada para {$user->email}");

        } catch (\Exception $e) {
            Log::error("Falha ao notificar {$user->email}: " . $e->getMessage());
            // Ao lançar a exceção, o Lumen entende que falhou e vai tentar de novo (retry)
            throw $e;
        }
    }
}
