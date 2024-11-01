<?php

namespace Iamprincesly\ClaudeAI\Resources;

class Tool
{
    public function __construct(private string $name, private string $description, private array $inputSchema)
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'input_schema' => $this->inputSchema,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getInputSchema(): array
    {
        return $this->inputSchema;
    }
}
