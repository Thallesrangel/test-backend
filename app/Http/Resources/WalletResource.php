<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_wallet' => $this->id_wallet,
            'id_user'   => $this->id_user,
            'amount'   => $this->amount,
        ];
    }
}
