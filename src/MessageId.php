<?php

namespace alexeevdv\Sms\SmsRu;

final class MessageId implements \alexeevdv\Sms\Contract\MessageId
{
    /**
     * @var string
     */
    private $messageId;

    public function __construct(string $messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->messageId;
    }
}
