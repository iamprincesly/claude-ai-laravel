<?php

namespace Iamprincesly\ClaudeAI;

use Iamprincesly\ClaudeAI\Enums\Model;

class Config
{
    public function __construct(
        private string $apiKey,
        private string $apiVersion,
        private Model $model,
        private int $max_tokens,
    ) {
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function setMaxTokens(int $max_tokens): self
    {
        $this->max_tokens = $max_tokens;
        return $this;
    }
    
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getMaxTokens(): int
    {
        return $this->max_tokens;
    }

    public function getBaseUrl(): string
    {
        return 'https://api.anthropic.com/v1';
    }
}
