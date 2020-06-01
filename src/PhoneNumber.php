<?php

namespace alexeevdv\Sms\SmsRu;

final class PhoneNumber implements \alexeevdv\Sms\Contract\PhoneNumber
{
    /**
     * @var string
     */
    private $phoneNumber;

    public function __construct(string $phoneNumber)
    {
        // Phone number for sms.ru should consist only of digits
        $phoneNumber = (string) preg_replace('/[^0-9.]+/', '', $phoneNumber);
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->phoneNumber;
    }
}
