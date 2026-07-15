<?php

declare(strict_types=1);

namespace AiSdk\TogetherAI;

use AiSdk\Contracts\BaseProvider;
use AiSdk\Contracts\TextModelInterface;
use AiSdk\Contracts\TextProviderInterface;
use AiSdk\TogetherAI\Models\TogetherAITextModel;

final class TogetherAIProvider extends BaseProvider implements TextProviderInterface
{
    public function __construct(public readonly TogetherAIOptions $options) {}

    public function name(): string
    {
        return TogetherAIOptions::PROVIDER_NAME;
    }

    protected function textModel(string $modelId): TextModelInterface
    {
        return new TogetherAITextModel($modelId, $this->options);
    }

}
