<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\TransactionRequest;
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
    
    /**
     * @OA\Get(
     *     tags={"transaction"},
     *     path="/api/transaction",
     *     security={{"bearer_token":{}}},
     *     description="Retorna todas as transações do usuários",
     *     @OA\Response(
     *         response=200,
     *         description="Transações retornados com sucesso.",
     *     ),
     *     @OA\Response(
     *      response=401,
     *      description="Não autorizado"
     *     ),
     * ),
    */

    public function index()
    {   
        $idUser = Auth::user()->id_user;
        $allTransactions = $this->transaction->where('payer', '=', $idUser)
                                            ->orWhere('payee', $idUser)
                                            ->get();
                                            
        return TransactionResource::collection($allTransactions);
    }

    /**
     * @OA\Post(
     *     tags={"transaction"},
     *     path="/api/transaction",
     *     security={{"bearer_token":{}}},
     *     description="Criando nova transação",
     *     @OA\Parameter(
     *         name="amount",
     *         in="query",
     *         description="Valor da transação",
     *         required=true,
     *          @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="document",
     *         in="query",
     *         description="Documento do beneficiário",
     *         required=true,
     *          @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registrado com sucesso.",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados ausentes ou inconsistentes."
     *     )
     * )
    */

    public function store(TransactionRequest $request)
    {   
        $request->validated();

        if (Auth::user()->id_user_category == 2) {
            throw ValidationException::withMessages(['forbidden_transaction' => 'Lojistas apenas realizam pagamentos.']);
        }
        
        $value = $request->value;
        $document_payee = $request->document;

        $user_wallet_payer = $this->wallet->where('id_user', '=', Auth::user()->id_user)->first();

        if ($user_wallet_payer->amount < $value) {
            throw ValidationException::withMessages(['insufficient_funds' => 'Não há saldo suficiente para realizar o pagamento.']);
        }
        
        $user_payee = $this->user->with('wallet')->where('document', '=', $document_payee)->first();

        if (!$user_payee) {
            throw ValidationException::withMessages(['user_not_funds' => 'Beneficiário não encontrado.']);
        }
        
        if ($user_payee->document == Auth::user()->document) {
            throw ValidationException::withMessages(['document_equals' => 'Não é permitido realizar a transação para o mesmo usuário.']);
        }

        $url = json_decode(file_get_contents('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6'), true);
        
        if (isset($url['message']) && $url['message'] == 'Autorizado') {
            
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

            $this->transaction->amount = $value;
            $this->transaction->payer = Auth::user()->id_user;
            $this->transaction->payee = $user_payee->id_user;
            $this->transaction->save();
        } else {
            throw ValidationException::withMessages(['error_validate' => 'Não autorizado.']);
        }

        if ($transaction_payer == false OR $transaction_payee == false) {
            throw ValidationException::withMessages(['error' => 'Algo de errado não está certo.']);
        }
    
        return response()->json(['success' => 'Transação realizada com sucesso', 200]);
    }

    public function show($idTransaction)
    {
        $data = $this->transaction::find($idTransaction);

        if (empty($data)) {
            throw ValidationException::withMessages(['transaction_not_exists' => 'A Transação não existe.']);
        }
        
        return new TransactionResource($data);
    }
}
