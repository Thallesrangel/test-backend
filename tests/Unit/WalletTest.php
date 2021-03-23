<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Model\Wallet;

class WalletTest extends TestCase
{
    /** @test **/
    public function check_if_wallet_columns_is_corret()
    {
        $user = new Wallet();
        
        $expected = [
            'id_wallet',
            'id_user',
            'amount'
        ];

        $arrayCompared = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($arrayCompared));
    }
    
    /* function ActionVerb_WhoOrWhatToDo_ExpectedBehavior */
}
