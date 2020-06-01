<?php

namespace alexeevdv\Sms\SmsRu;

use alexeevdv\Sms\Contract;
use alexeevdv\Sms\SmsRu\Exception\BadResponseException;
use alexeevdv\Sms\SmsRu\Exception\TransportException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final class Provider implements Contract\Provider
{
    /**
     * @var string
     */
    private $apiId;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    public function __construct(string $apiId, ClientInterface $httpClient, RequestFactoryInterface $requestFactory)
    {
        $this->apiId = $apiId;
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    public function sendMessage(Contract\PhoneNumber $phoneNumber, string $text): Contract\MessageId
    {
        $uri = 'https://sms.ru/sms/send?api_id=' . $this->apiId;
        $uri .= '&to=' . $phoneNumber;
        $uri .= '&msg=' . urlencode($text);
        $uri .= '&json=1';

        $request = $this->requestFactory->createRequest('POST', $uri);

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new TransportException($e->getMessage(), $e);
        }

        $responseData = json_decode((string) $response->getBody(), true);

        $status = $responseData['status'] ?? null;
        if ($status !== 'OK') {
            throw new BadResponseException($response);
        }

        $messageId = $responseData['sms'][(string) $phoneNumber]['sms_id'] ?? null;
        if ($messageId === null) {
            // TODO: check status_code and throw exception with status_text
            throw new BadResponseException($response);
        }

        return new MessageId($messageId);
    }
}
