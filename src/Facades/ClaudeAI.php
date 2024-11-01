<?php

namespace Iamprincesly\ClaudeAI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self addTool()
 * @method static self setToolChoice()
 * @method static self withHistory()
 * @method static self setModel()
 * @method static self setMaxTokens()
 * @method static self setSystemInstruction()
 * @method static self setTemperature()
 * @method static self setStopSequences()
 * @method static self addStopSequence()
 * @method static self setMetadata()
 * @method static self setTopK()
 * @method static self setTopP()
 * @method static \Iamprincesly\ClaudeAI\Resources\MessageResponse sendMessage()
 * @method static self addText()
 * @method static self addImage()
 * @method static null|string chat()
 * @method static array send()
 *
 * @see \Iamprincesly\ClaudeAI\ClaudeAI
 */
class ClaudeAI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'claude-ai';
    }
}
