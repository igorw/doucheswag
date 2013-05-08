<?php

namespace Douche\Service;

class UppercasePasswordEncoderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function shouldValidateRawPasswordAgainstEncodedPassword()
    {
        $encoder = new UppercasePasswordEncoder();
        $this->assertTrue($encoder->isPasswordValid('DAVE', 'dave'));
        $this->assertFalse($encoder->isPasswordValid('DAVasE', 'dave'));
    }
    
}
