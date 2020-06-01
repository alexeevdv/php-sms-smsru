<?php

namespace tests\unit;

use alexeevdv\Sms\SmsRu\Exception\BadResponseException;
use alexeevdv\Sms\SmsRu\Exception\TransportException;
use alexeevdv\Sms\SmsRu\PhoneNumber;
use alexeevdv\Sms\SmsRu\Provider;
use Codeception\Test\Unit;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ProviderTest extends Unit
{
    public function testMessageSendFailedDueToNetworkProblems()
    {
        /** @var ClientInterface $client */
        $client = $this->makeEmpty(ClientInterface::class,  [
            'sendRequest' => function(RequestInterface $request): ResponseInterface {
                throw new class extends \Exception implements ClientExceptionInterface {};
            },
        ]);

        $this->expectException(TransportException::class);
        $provider = new Provider('123', $client, $this->createRequestFactory());
        $provider->sendMessage(new PhoneNumber('1234567890'), 'test');
    }

    public function testMessageSendFailedDueToWrongResponseFormat()
    {
        /** @var ClientInterface $client */
        $client = $this->makeEmpty(ClientInterface::class,  [
            'sendRequest' => function(RequestInterface $request): ResponseInterface {
                return new Response(200, [], 'not a json');
            },
        ]);

        $this->expectException(BadResponseException::class);
        $provider = new Provider('123', $client, $this->createRequestFactory());
        $provider->sendMessage(new PhoneNumber('1234567890'), 'test');
    }

    public function testMessageSendFailedDueToMessageIdNotFoundInResponse()
    {
        /** @var ClientInterface $client */
        $client = $this->makeEmpty(ClientInterface::class,  [
            'sendRequest' => function(RequestInterface $request): ResponseInterface {
                return new Response(200, [], json_encode([
                    'status' => 'OK',
                    'sms' => [
                        '1234567890' => [
                            'status' => 'ERROR',
                            'status_code' => 207,
                            'status_text' => 'Error message',
                        ],
                    ],
                ]));
            },
        ]);

        $this->expectException(BadResponseException::class);
        $provider = new Provider('123', $client, $this->createRequestFactory());
        $provider->sendMessage(new PhoneNumber('1234567890'), 'test');
    }

    public function testMessageSendSuccessfuly()
    {
        /** @var ClientInterface $client */
        $client = $this->makeEmpty(ClientInterface::class,  [
            'sendRequest' => function(RequestInterface $request): ResponseInterface {
                return new Response(200, [], json_encode([
                    'status' => 'OK',
                    'sms' => [
                        '1234567890' => [
                            'status' => 'OK',
                            'status_code' => 100,
                            'sms_id' => '000000-10000000',
                        ],
                    ],
                ]));
            },
        ]);

        $provider = new Provider('123', $client, $this->createRequestFactory());
        $messageId = $provider->sendMessage(new PhoneNumber('1234567890'), 'test');
        $this->assertSame('000000-10000000', (string) $messageId);
    }

    private function createRequestFactory(): RequestFactoryInterface
    {
        return new class implements RequestFactoryInterface {
            public function createRequest(string $method, $uri): RequestInterface
            {
                return new Request($method, $uri);
            }
        };
    }
}
