<?php

namespace Iamprincesly\ClaudeAI\Resources;

class ToolChoice
{
    public function __construct(
        private string $type, 
        private bool $disable_parallel_tool_use, 
        private ?string $name = null
    ){}

    public function toArray(): array
    {
        $data = [
            'type' => $this->type,
            'disable_parallel_tool_use' => $this->disable_parallel_tool_use,
        ];

        if (!is_null($this->name)) {
            $data['name'] = $this->name;
        }

        return $data;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
