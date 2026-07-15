# aisdk/togetherai

Official TogetherAI provider for the PHP AI SDK. Uses TogetherAI's Chat Completions API.

## Installation

```bash
composer require aisdk/togetherai
```

## Basic Usage

```php
use AiSdk\Generate;
use AiSdk\TogetherAI;

$result = Generate::text('What happened in technology today?')
    ->model(TogetherAI::model('meta-llama/Llama-3.3-70B-Instruct-Turbo'))
    ->run();

echo $result->text;
```

## Configuration

| Variable | Description | Default |
|---|---|---|
| `TOGETHERAI_API_KEY` | API key for authentication | Required |
| `TOGETHERAI_BASE_URL` | Base URL for API requests | `https://api.togetherai.ai` |

## Supported Capabilities

| Capability | Support |
|---|---|
| Text generation | Native |
| Streaming | Native |
| Tool calling | Native |
| Structured output | Adapted (`json_object` with an explicit JSON instruction) |
| Text input | Native |

Provider-specific fields can be passed through `providerOptions('togetherai', [...])`. Model IDs are opaque strings and TogetherAI remains the authority on model availability.

## Testing

```bash
composer test:all
```

## Links

- [TogetherAI API documentation](https://docs.togetherai.ai/)
- [Core Package](https://github.com/phpaisdk/core)
- [OpenAI-Compatible Package](https://github.com/phpaisdk/openai-compatible)
