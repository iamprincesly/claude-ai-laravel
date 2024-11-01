<?php

namespace Iamprincesly\ClaudeAI\Resources;

use Iamprincesly\ClaudeAI\Enums\Role;
use Iamprincesly\ClaudeAI\Traits\ArrayTypeValidator;
use Iamprincesly\ClaudeAI\Interfaces\ContentInterface;

class Message 
{
    use ArrayTypeValidator;
    
    public function __construct(private Role $role, private array $content)
    {
        $this->ensureArrayOfType($content, ContentInterface::class);
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function addContent(ContentInterface $content): self
    {
        $this->content[] = $content;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'content' => array_map(fn (ContentInterface $content) => $content->toArray(), $this->content),
        ];
    }
}