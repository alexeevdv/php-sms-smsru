<?php

namespace tests\unit;

use alexeevdv\Sms\SmsRu\MessageId;
use Codeception\Test\Unit;

final class MessageIdTest extends Unit
{
    public function testMessageIdConvertedToString()
    {
        $messageId = new MessageId('123-456');
        $this->assertSame('123-456', (string) $messageId);
    }
}
