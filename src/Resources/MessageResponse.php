<?php

namespace Iamprincesly\ClaudeAI\Resources;

use Iamprincesly\ClaudeAI\Enums\Role;
use Iamprincesly\ClaudeAI\Enums\Model;
use Iamprincesly\ClaudeAI\Resources\Contents\Text;

class MessageResponse
{
    private ?string $id;
    private ?string $type;
    private ?string $role;
    private array $content;
    private ?string $model;
    private ?string $stopReason;
    private ?string $stopSequence;
    private object $usage;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->role = Role::from($data['role']) ?? null;
        $this->model = Model::from($data['model']) ?? null;
        $this->stopReason = $data['stop_reason'] ?? null;
        $this->stopSequence = $data['stop_sequence'] ?? null;
        $this->usage = (object) $data['usage'] ?? [];

        $this->content = array_map(function (array $item) {

            if ($item['type'] === 'text') {
                return new Text($item['text']);
            }

        }, $data['content'] ?? []);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function getStopReason(): ?string
    {
        return $this->stopReason;
    }

    public function getStopSequence(): ?string
    {
        return $this->stopSequence;
    }

    public function getUsage(): object
    {
        return $this->usage;
    }

    public function getAnswer(): ?string
    {
        return $this->content[0]?->text();
    }
}
