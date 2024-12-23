<?php

namespace NotchPay\DTO\Transfers;

final readonly class RecipientDTO
{
    public function __construct(
        private string $name,
        private string $channel,
        private string $number,
        private string $phone,
        private string $email,
        private string $country,
        private string $description,
        private string $reference
    ) {
        $this->validate();
    }

        private function validate(): void
        {
            $validations = [
                'name' => fn() => !empty($this->name),
                'channel' => fn() => !empty($this->channel),
                'number' => fn() => !empty($this->number),
                'phone' => fn() => !empty($this->phone),
                'email' => fn() => filter_var($this->email, FILTER_VALIDATE_EMAIL),
                'country' => fn() => !empty($this->country),
                'description' => fn() => !empty($this->description),
                'reference' => fn() => !empty($this->reference)
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
            'name' => $this->name,
            'channel' => $this->channel,
            'number' => $this->number,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'description' => $this->description,
            'reference' => $this->reference
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            channel: $data['channel'] ?? '',
            number: $data['number'] ?? '',
            phone: $data['phone'] ?? '',
            email: $data['email'] ?? '',
            country: $data['country'] ?? '',
            description: $data['description'] ?? '',
            reference: $data['reference'] ?? ''
        );
    }
}