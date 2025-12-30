<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class TransferService
{
    private $authService;

    public function __construct(AuthorizationService $authService)
    {
        $this->authService = $authService;
    }

    public function processTransfer(int $payerId, int $payeeId, float $value)
    {
        // 1. Iniciar Transação de Banco de Dados
        // Usamos DB::transaction para garantir que tudo seja revertido se der erro.
        return DB::transaction(function () use ($payerId, $payeeId, $value) {

            // 2. Buscar Pagador e Recebedor
            $payer = User::findOrFail($payerId);
            $payee = User::findOrFail($payeeId);

            // 3. Validações de Regra de Negócio

            // Regra: Lojistas só recebem, não enviam.
            if ($payer->type === 'SHOPKEEPER') {
                throw new Exception('Lojistas não podem enviar dinheiro.');
            }

            // Regra: Verificar Saldo (Usamos lockForUpdate para evitar Race Condition)
            // Isso "trava" a linha do banco até a transação terminar.
            $payerWallet = Wallet::where('user_id', $payer->id)->lockForUpdate()->first();

            if (!$payerWallet || $payerWallet->balance < $value) {
                throw new Exception('Saldo insuficiente.');
            }

            // 4. Consultar Autorizador Externo
            // Fazemos isso ANTES de debitar para evitar writes desnecessários se não for autorizado.
            if (!$this->authService->isAuthorized()) {
                throw new Exception('Transação não autorizada pelo serviço externo.');
            }

            // 5. Executar a Transferência
            $payeeWallet = Wallet::where('user_id', $payee->id)->lockForUpdate()->first();

            // Deduzir
            $payerWallet->balance -= $value;
            $payerWallet->save();

            // Acrescentar
            $payeeWallet->balance += $value;
            $payeeWallet->save();

            // 6. Registrar Transação
            $transaction = Transaction::create([
                'payer_wallet_id' => $payerWallet->id,
                'payee_wallet_id' => $payeeWallet->id,
                'value' => $value
            ]);

            return $transaction;
        });
    }
}
