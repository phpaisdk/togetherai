# aisdk/togetherai

<a href="https://github.com/phpaisdk/togetherai/actions"><img alt="GitHub Workflow Status" src="https://img.shields.io/github/actions/workflow/status/phpaisdk/togetherai/tests.yml?branch=main&label=Tests"></a>
<a href="https://packagist.org/packages/aisdk/togetherai"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/aisdk/togetherai"></a>
<a href="https://packagist.org/packages/aisdk/togetherai"><img alt="Latest Version" src="https://img.shields.io/packagist/v/aisdk/togetherai"></a>
<a href="https://packagist.org/packages/aisdk/togetherai"><img alt="License" src="https://img.shields.io/packagist/l/aisdk/togetherai"></a>
<a href="https://whyphp.dev"><img src="https://img.shields.io/badge/Why_PHP-in_2026-7A86E8?style=flat-square&labelColor=18181b" alt="Why PHP in 2026"></a>

------

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
