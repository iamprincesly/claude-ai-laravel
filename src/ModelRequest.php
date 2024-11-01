<?php

namespace Iamprincesly\ClaudeAI;

use Iamprincesly\ClaudeAI\Enums\Model;
use Iamprincesly\ClaudeAI\Resources\Tool;
use Iamprincesly\ClaudeAI\Resources\Message;
use Iamprincesly\ClaudeAI\Resources\ToolChoice;
use Iamprincesly\ClaudeAI\Traits\ArrayTypeValidator;
use Iamprincesly\ClaudeAI\Exceptions\InvalidArgumentException;

class ModelRequest
{
    use ArrayTypeValidator;

    public function __construct(
        private Config $config, 
        private array $messages = [], 
        private bool $stream = false,
        private ?string $systemInstruction = null,
        private ?float $temperature = null,
        private array $stopSequences = [],
        private ?int $topK = null,
        private ?float $topP = null,
        private array $tools = [],
        private ?ToolChoice $toolChoice = null,
        private array $metadata = [],
    )
    {
        $this->ensureArrayOfType($messages, Message::class);
        $this->ensureArrayOfType($tools, Tool::class);
    }

    public function addTool(Tool $tool): self
    {
        $this->tools[] = $tool->toArray();
        return $this;
    }

    public function setToolChoice(ToolChoice $toolChoice): self
    {
        $this->toolChoice = $toolChoice;
        return $this;
    }

    public function addMessage(Message $message): self
    {
        $this->messages[] = $message->toArray();
        return $this;
    }

    public function setModel(Model $model): self
    {
        $this->config->setModel($model);
        return $this;
    }

    public function setMaxTokens(int $max_tokens): self
    {
        $this->config->setMaxTokens($max_tokens);
        return $this;
    }

    public function setSystemInstruction(string $text): self
    {
        $this->systemInstruction = $text;
        return $this;
    }

    public function setStream(bool $stream): self
    {
        $this->stream = $stream;
        return $this;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;
        return $this;
    }

    public function setStopSequences(array $stopSequences): self
    {
        foreach ($stopSequences as $stopSequence) {
            $this->stopSequences[] = $stopSequence;
        }
        return $this;
    }

    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function setTopK(int $topK): self
    {
        $this->topK = $topK;
        return $this;
    }

    public function setTopP(float $topP): self
    {
        $this->topP = $topP;
        return $this;
    }

    public function toArray(): array
    {
        if (empty($this->messages)) {
            throw new InvalidArgumentException('At least one message is required');
        }

        $data = [
            'model' => $this->config->getModel()->value,
            'max_tokens' => $this->config->getMaxTokens(),
            'messages' => $this->messages,
        ];

        if (!empty($this->tools)) {
            $data['tools'] = $this->tools;
        }

        if (!is_null($this->toolChoice)) {
            $data['tool_choice'] = $this->toolChoice->toArray();
        }

        if (!is_null($this->systemInstruction)) {
            $data['system'] = $this->systemInstruction;
        }

        if (!is_null($this->temperature)) {
            $data['temperature'] = $this->temperature;
        }

        if (!empty($this->stopSequences)) {
            $this->ensureArrayOfString($this->stopSequences);
            $data['stop_sequences'] = $this->stopSequences;
        }

        if (true === $this->stream) {
            $data['stream'] = $this->stream;
        }

        if (!empty($this->metadata)) {
            $data['metadata'] = $this->metadata;
        }

        if (!is_null($this->topK)) {
            $data['top_k'] = $this->topK;
        }

        if (!is_null($this->topP)) {
            $data['top_p'] = $this->topP;
        }

        return $data;
    }
}