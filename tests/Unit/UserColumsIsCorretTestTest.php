<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Model\User;

class UserColumsIsCorretTestTest extends TestCase
{
    /** @test **/
    public function check_if_user_columns_is_corret()
    {
        $user = new User();
        
        $expected = [
            'name',
            'id_user_category',
            'document',
            'email',
            'password'
        ];

        $arrayCompared = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($arrayCompared));
    }
    
    /* function ActionVerb_WhoOrWhatToDo_ExpectedBehavior */
}
