<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TransactionResource;
use App\Model\Transaction;
use App\Model\User;
use App\Model\Wallet;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiJwt');
        $this->transaction = new Transaction();
        $this->wallet = new Wallet();
        $this->user = new User();
    }
    
    public function index()
    {   
        $idUser = Auth::user()->id_user;
        $allTransactions = $this->transaction->where('payer', '=', $idUser)
                                            ->orWhere('payee', $idUser)
                                            ->get();
                                            
        return TransactionResource::collection($allTransactions);
    
    }

    public function store(Request $request)
    {   

        // Inserir Validação

        if ( Auth::user()->id_user_category == 2) {
            throw ValidationException::withMessages(['forbidden_transaction' => 'Lojistas apenas realizam pagamentos.']);
        }
        
        $value = $request->value;
        $document_payee = $request->document;

        $user_wallet_payer = $this->wallet->where('id_user', '=', Auth::user()->id_user)->first();

        if ($user_wallet_payer->amount < $value) {
            throw ValidationException::withMessages(['insufficient_funds' => 'Não há saldo suficiente para realizar o pagamento.']);
        }
        
        $user_payee = $this->user->with('wallet')->where('document', '=', $document_payee)->first();

        if ($user_payee->document == Auth::user()->document) {
            throw ValidationException::withMessages(['document_equals' => 'Não é permitido realizar a transação para o mesmo usuário.']);
        }

        $data_payer = [
            'amount' => $user_wallet_payer->amount - $value,
            'updated_at' => now()
        ];

        $transaction_payer = $this->wallet::where('id_user', '=', Auth::user()->id_user)->update($data_payer);

        $data_payee = [
            'amount' => $user_payee->wallet->amount + $value ,
            'updated_at' => now()
        ];

        $transaction_payee = $this->wallet::where('id_user', '=', $user_payee->id_user)->update($data_payee);

        /* Registrando a transação */
        $this->transaction->amount = $value;
        $this->transaction->payer = Auth::user()->id_user;
        $this->transaction->payee = $user_payee->id_user;
        $this->transaction->save();

        if ($transaction_payer == false OR $transaction_payee == false) {
            throw ValidationException::withMessages(['error' => 'Algo de errado não está certo.']);
        }

        return response()->json(['success' => 'Transação realizada com sucesso', 200]);
    }

    public function show($idTransaction)
    {
        $data = $this->transaction::find($idTransaction);

        if ( empty($data) ) {
            throw ValidationException::withMessages(['transaction_not_exists' => 'A Transação não existe.']);
        }
        
        return new TransactionResource($data);
    }

    private function debitAmount() {

    }

    private function creditAmount()
    {

    }
}
