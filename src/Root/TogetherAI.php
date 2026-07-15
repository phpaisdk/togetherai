<?php

declare(strict_types=1);

namespace AiSdk;

use AiSdk\Contracts\Model;
use AiSdk\TogetherAI\TogetherAIOptions;
use AiSdk\TogetherAI\TogetherAIProvider;

/**
 * Friendly facade for the TogetherAI provider.
 *
 *   $model = TogetherAI::model('meta-llama/Llama-3.3-70B-Instruct-Turbo');
 */
final class TogetherAI
{
    private static ?TogetherAIProvider $default = null;

    /**
     * @param  array<string, mixed>  $config
     */
    public static function create(array $config = []): TogetherAIProvider
    {
        return self::$default = new TogetherAIProvider(TogetherAIOptions::fromArray($config));
    }

    public static function default(): TogetherAIProvider
    {
        return self::$default ??= self::create();
    }

    public static function reset(): void
    {
        self::$default = null;
    }

    public static function model(string $modelId): Model
    {
        return self::default()->model($modelId);
    }
}
