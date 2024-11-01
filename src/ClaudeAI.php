<?php

namespace Iamprincesly\ClaudeAI;

use Iamprincesly\ClaudeAI\Enums\Role;
use Iamprincesly\ClaudeAI\Enums\Model;
use Iamprincesly\ClaudeAI\Enums\MimeType;
use Iamprincesly\ClaudeAI\Resources\Tool;
use Iamprincesly\ClaudeAI\Resources\Message;
use Iamprincesly\ClaudeAI\Resources\ToolChoice;
use Iamprincesly\ClaudeAI\Resources\Contents\Text;
use Iamprincesly\ClaudeAI\Resources\Contents\Image;
use Iamprincesly\ClaudeAI\Resources\MessageResponse;
use Iamprincesly\ClaudeAI\Exceptions\InvalidArgumentException;

class ClaudeAI
{
    private ModelRequest $request;

    private array $content;

    public function __construct(private ClaudeAIClient $client)
    {
        $this->request = new ModelRequest($this->client->config);
    }

    public function addTool(Tool $tool): self
    {
        $this->request->addTool($tool);
        return $this;
    }

    public function setToolChoice(ToolChoice $toolChoice): self
    {
        $this->request->setToolChoice($toolChoice);
        return $this;
    }

    public function withHistory(array $messages): self
    {
        foreach ($messages as $key => $message) {

            if (!$message instanceof Message) {
                throw new InvalidArgumentException(
                    sprintf('Expected type %s but found %s', Message::class, is_object($message) ? $message::class : gettype($message))
                );
            }

            $this->request->addMessage($message);
        }
        
        return $this;
    }

    public function setModel(Model $model): self
    {
        $this->request->setModel($model);
        return $this;
    }

    public function setMaxTokens(int $max_tokens): self
    {
        $this->request->setMaxTokens($max_tokens);
        return $this;
    }

    public function setSystemInstruction(string $text): self
    {
        $this->request->setSystemInstruction($text);
        return $this;
    }

    public function setTemperature(float $temperature): self
    {
        $this->request->setTemperature($temperature);
        return $this;
    }

    public function setStopSequences(array $stopSequences): self
    {
        $this->request->setStopSequences($stopSequences);
        return $this;
    }

    public function addStopSequence(string $stopSequence): self
    {
        $this->setStopSequences([$stopSequence]);
        return $this;
    }

    public function setMetadata(array $metadata): self
    {
        $this->request->setMetadata($metadata);
        return $this;
    }

    public function setTopK(int $topK): self
    {
        $this->request->setTopK($topK);
        return $this;
    }

    public function setTopP(float $topP): self
    {
        $this->request->setTopP($topP);
        return $this;
    }

    private function sendMessage(bool $stream = false, ?callable $callback = null): MessageResponse
    {
        $message = new Message(Role::User, $this->content);

        $this->request->addMessage($message);

        $this->request->setStream($stream);

        if ($stream === true && is_null($callback)) {
            throw new InvalidArgumentException('Please specify callback function to stream message.');
        }

        return match($stream) {
            true => $this->client->sendStreamMessage($this->request, $callback),
            default => $this->client->sendMessage($this->request),
        };
    }

    public function addText(string $text): self
    {
        $this->content[] = new Text($text);
        return $this;
    }

    public function addImage(string $path): self
    {
        $base64Image = base64_encode(file_get_contents($path));

        $this->content[] = new Image($base64Image, MimeType::from(mime_content_type($path)));
        
        return $this;
    }

    public function chat(string $text, bool $stream = false, ?callable $callback = null): ?string
    {
        $this->addText($text);

        $message = $this->sendMessage($stream, $callback);

        return $message->getAnswer();
    }

    public function send(bool $stream = false, ?callable $callback = null): array
    {
        $message = $this->sendMessage($stream, $callback);
        return $message->getContent();
    }
}