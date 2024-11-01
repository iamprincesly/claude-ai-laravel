<?php

namespace Iamprincesly\ClaudeAI\Resources\Contents;

use Iamprincesly\ClaudeAI\Interfaces\ContentInterface;

class Text implements ContentInterface
{
    public function __construct(private string $text)
    {
    }

    public function toArray(): array
    {
        return [
            'type' => 'text',
            'text' => $this->text,
        ];
    }

    public function text(): string
    {
        return $this->text;
    }
}
