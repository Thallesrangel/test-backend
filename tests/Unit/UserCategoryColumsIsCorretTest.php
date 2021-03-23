<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Model\UserCategory;

class UserCategoryColumsIsCorretTest extends TestCase
{
    /** @test **/
    public function check_if_user_category_columns_is_corret()
    {
        $user = new UserCategory();
        
        $expected = [
            'id_user_category',
            'category',
        ];

        $arrayCompared = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($arrayCompared));

    }
    
}
