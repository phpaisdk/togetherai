# PHP AI SDK — Together AI

Text generation, streaming, and tool calling through Together AI. Part of the framework-agnostic [PHP AI SDK](https://phpaisdk.com).

## Installation

```bash
composer require aisdk/togetherai
```

Set `TOGETHERAI_API_KEY` in your environment.

## Usage

```php
use AiSdk\Generate;
use AiSdk\TogetherAI;

$result = Generate::text('Explain closures in PHP in one sentence.')
    ->model(TogetherAI::model('deepseek-ai/DeepSeek-V4.1-Flash-1017'))
    ->run();

echo $result->text;
```

## Documentation

[Read the documentation](https://phpaisdk.com/docs/0.x/providers/togetherai) for configuration, supported features, and more examples.

[Contributing](https://github.com/phpaisdk/.github/blob/main/CONTRIBUTING.md) · [Support](https://github.com/phpaisdk/.github/blob/main/SUPPORT.md)

## License

The MIT License (MIT). See [LICENSE](LICENSE) for details.
