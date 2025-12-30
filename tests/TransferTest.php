<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
use App\Services\AuthorizationService;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendNotificationJob;
use Mockery;

class TransferTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Teste do Cenário Feliz: Transferência com sucesso
     */
    public function test_user_can_transfer_money_to_another_user()
    {
        // Arrange
        Queue::fake();

        $payer = User::factory()->create(['type' => 'COMMON']);
        $payee = User::factory()->create(['type' => 'SHOPKEEPER']);

        $payer->wallet()->update(['balance' => 500.00]);
        $payee->wallet()->update(['balance' => 0.00]);

        $mockAuth = Mockery::mock(AuthorizationService::class);

        $mockAuth->shouldReceive('isAuthorized')
                 ->once()
                 ->andReturn(true);

        $this->app->instance(AuthorizationService::class, $mockAuth);
        // Act
        $response = $this->post('/api/transfer', [
            'value' => 100.00,
            'payer' => $payer->id,
            'payee' => $payee->id
        ]);

        // Assert
        $response->seeStatusCode(201);
        $response->seeJson(['message' => 'Transferência realizada com sucesso!']);

        $this->seeInDatabase('wallets', [
            'user_id' => $payer->id,
            'balance' => 400.00
        ]);

        $this->seeInDatabase('wallets', [
            'user_id' => $payee->id,
            'balance' => 100.00
        ]);

        Queue::assertPushed(SendNotificationJob::class);
    }

    // Teste de saldo insuficiente (não precisa de mock pois falha antes de chamar o serviço externo)
    public function test_user_cannot_transfer_without_balance()
    {
        $payer = User::factory()->create(['type' => 'COMMON']);
        $payee = User::factory()->create(['type' => 'COMMON']);

        $payer->wallet()->update(['balance' => 50.00]);

        $response = $this->post('/api/transfer', [
            'value' => 100.00,
            'payer' => $payer->id,
            'payee' => $payee->id
        ]);

        $response->seeStatusCode(422);
    }

    // Teste de lojista (não precisa de mock pois falha antes)
    public function test_shopkeeper_cannot_send_money()
    {
        $shopkeeper = User::factory()->create(['type' => 'SHOPKEEPER']);
        $user = User::factory()->create(['type' => 'COMMON']);

        $shopkeeper->wallet()->update(['balance' => 1000.00]);

        $response = $this->post('/api/transfer', [
            'value' => 100.00,
            'payer' => $shopkeeper->id,
            'payee' => $user->id
        ]);

        $response->seeStatusCode(422);
    }
}
