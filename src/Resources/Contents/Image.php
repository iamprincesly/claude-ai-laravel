<?php

namespace Iamprincesly\ClaudeAI\Resources\Contents;

use Iamprincesly\ClaudeAI\Enums\MimeType;
use Iamprincesly\ClaudeAI\Interfaces\ContentInterface;

class Image implements ContentInterface
{
    public function __construct(private string $data, private MimeType $mimeType)
    {
    }

    public function toArray(): array
    {
        return [
            'type' => 'image',
            'source' => [
                'type' => 'base64',
                'media_type' => $this->mimeType,
                'data' => $this->data,
            ],
        ];
    }
}
