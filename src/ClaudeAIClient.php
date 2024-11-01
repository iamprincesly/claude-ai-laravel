<?php

namespace Iamprincesly\ClaudeAI;

use GuzzleHttp\Client;
use Iamprincesly\ClaudeAI\Resources\MessageResponse;
use Iamprincesly\ClaudeAI\Exceptions\ClaudeApiException;

class ClaudeAIClient
{
    private Client $client;

    public function __construct(public Config $config)
    {
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key' => $this->config->getApiKey(),
                'anthropic-version' => $this->config->getApiVersion(),
            ],
            // 'timeout'  => 9.0,
        ]);
    }

    private function buildEndpoint(string $url): string
    {
        return rtrim("https://api.anthropic.com/v1/{$url}");
    }

    public function get(string $url, array $options = []): array
    {
        try {
            $response = $this->client->get($this->buildEndpoint($url), $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            throw new ClaudeApiException("GET request failed: " . $e->getMessage());
        }
    }

    public function post(string $url, array $options = []): array
    {
        try {
            $response = $this->client->post($this->buildEndpoint($url), $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            throw new ClaudeApiException("POST request failed: " . $e->getMessage());
        }
    }

    public function sendMessage(ModelRequest $request): MessageResponse
    {
        $data = $this->post('messages', ['json' => $request->toArray()]);
        return new MessageResponse($data);
    }

    public function sendStreamMessage(ModelRequest $request, callable $callback)
    {
        try {
            $response = $this->client->post($this->buildEndpoint('messages'), [
                'json' => $request->toArray(),
                'stream' => true,
            ]);

            $body = $response->getBody();

            $buffer = '';

            while (!$body->eof()) {
                $chunk = $body->read(1024);
                $buffer .= $chunk;

                $events = $this->parseSSE($buffer);

                foreach ($events as $event) {
                    $data = json_decode($event['data'], true);
                    switch ($event['event']) {
                        case 'message_start':
                            if (isset($data['message'])) {
                                $callback(new MessageResponse($data['message']));
                            }
                            break;
                        case 'message_stop':
                            if (isset($data['message'])) {
                                $callback(new MessageResponse($data['message']));
                            } else {
                                $callback($data); // Pass the raw data if 'message' is not present
                            }
                            return;
                        case 'content_block_start':
                        case 'content_block_delta':
                        case 'message_delta':
                            $callback($data);
                            break;
                    }
                }

                $buffer = $this->trimBuffer($buffer);
            }
        } catch (\Exception $e) {
            throw new ClaudeApiException('Error streaming message: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function parseSSE($buffer)
    {
        $events = [];
        $lines = explode("\n\n", $buffer);

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            $event = [
                'event' => '',
                'data' => '',
            ];

            foreach (explode("\n", $line) as $part) {
                if (strpos($part, 'event:') === 0) {
                    $event['event'] = trim(substr($part, 6));
                } elseif (strpos($part, 'data:') === 0) {
                    $event['data'] = trim(substr($part, 5));
                }
            }

            if (!empty($event['event']) && !empty($event['data'])) {
                $events[] = $event;
            }
        }

        return $events;
    }

    private function trimBuffer($buffer)
    {
        $lastNewLine = strrpos($buffer, "\n\n");
        if ($lastNewLine !== false) {
            return substr($buffer, $lastNewLine + 2);
        }
        return $buffer;
    }
}