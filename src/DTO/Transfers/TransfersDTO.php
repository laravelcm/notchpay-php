<?php

namespace NotchPay\DTO\Transfers;

final readonly class TransfersDTO
{
    public function __construct(
        private string $amount,
        private string $currency,
        private string $description,
        private string $recipientId,
        private string $channel,
        private array $beneficiary = [],
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        $validations = [
            'amount' => fn() => !empty($this->amount),
            'currency' => fn() => !empty($this->currency),
            'description' => fn() => !empty($this->description),
            'recipientId' => fn() => !empty($this->recipientId),
            'channel' => fn() => !empty($this->channel),
            'beneficiary.name' => fn() => !empty($this->beneficiary['name'] ?? ''),
            'beneficiary.number' => fn() => !empty($this->beneficiary['number'] ?? ''),
        ];

        foreach ($validations as $field => $validator) {
            if (!$validator()) {
                throw new \InvalidArgumentException("$field is required or invalid");
            }
        }
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'description' => $this->description,
            'recipientId' => $this->recipientId,
            'channel' => $this->channel,
            'beneficiary' => [
                'name' => $this->beneficiary['name'] ?? '',
                'number' => $this->beneficiary['number'] ?? '',
            ],
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'] ?? '',
            currency: $data['currency'] ?? '',
            description: $data['description'] ?? '',
            recipientId: $data['recipientId'] ?? '',
            channel: $data['channel'] ?? '',
            beneficiary: $data['beneficiary'] ?? [],
        );
    }
}