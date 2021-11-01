<?php

namespace Tests\Unit\UserTest;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function testChecarSeColunasEstaoCorretas() {
        $user = new User();

        $expected = [
            'name', 'email', 'password'
        ];

        $arrayCompared = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($arrayCompared));
    }

}
