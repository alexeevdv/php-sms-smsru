<?php

namespace tests\unit;

use alexeevdv\Sms\SmsRu\PhoneNumber;
use Codeception\Test\Unit;

final class PhoneNumberTest extends Unit
{
    public function testAllNonNumericSymbolsAreRemoved()
    {
        $phoneNumber = new PhoneNumber('+1-234-567-89-10');
        $this->assertSame('12345678910', (string) $phoneNumber);
    }
}
