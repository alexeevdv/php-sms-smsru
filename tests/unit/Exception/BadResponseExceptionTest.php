<?php

namespace tests\unit\Exception;

use alexeevdv\Sms\SmsRu\Exception\BadResponseException;
use Codeception\Test\Unit;
use GuzzleHttp\Psr7\Response;

final class BadResponseExceptionTest extends Unit
{
    public function testErrorMessageFormat()
    {
        $response = new Response(500, [], 'Unknown error');
        $exception = new BadResponseException($response);
        $this->assertSame('{"statusCode":500,"body":"Unknown error"}', $exception->getMessage());
    }


    public function testResponseCanBeRetreivedFromException()
    {
        $response = new Response(500, [], 'Unknown error');
        $exception = new BadResponseException($response);
        $this->assertSame($response, $exception->getResponse());
    }
}
