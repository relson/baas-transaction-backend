<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TransferService;
use Exception;

class TransferController extends Controller
{
    private $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function makeTransfer(Request $request): JsonResponse
    {
        $this->validate($request, [
            'value' => 'required|numeric|min:0.01',
            'payer' => 'required|integer|exists:users,id',
            'payee' => 'required|integer|exists:users,id|different:payer'
        ]);

        try {
            $transaction = $this->transferService->processTransfer(
                $request->input('payer'),
                $request->input('payee'),
                $request->input('value')
            );

            return new JsonResponse([
                'message' => 'Transferência realizada com sucesso!',
                'transaction' => $transaction
            ], 201);
        } catch (Exception $e) {
            // Retorna erro 400 (Bad Request) ou 422 (Unprocessable Entity)
            return new JsonResponse([
                'message' => 'Erro na transação.',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
