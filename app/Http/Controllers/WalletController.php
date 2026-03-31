<?php

namespace App\Http\Controllers;

use App\Services\WalletService;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function wallet()
    {
        return response()->json(
            $this->walletService->getWallet(auth()->id())
        );
    }

    public function transactions()
    {
        return response()->json(
            $this->walletService->getTransactions(auth()->id())
        );
    }
}