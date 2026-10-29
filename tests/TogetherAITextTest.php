<?php

declare(strict_types=1);

use AiSdk\Generate;
use AiSdk\Schema;
use AiSdk\Support\Sdk;
use AiSdk\TogetherAI;
use AiSdk\TogetherAI\Tests\Fakes\FakeHttpClient;

afterEach(function () {
    Generate::reset();
    TogetherAI::reset();
});

function configureTogetherAIWith(FakeHttpClient $client): void
{
    $factory = new \Nyholm\Psr7\Factory\Psr17Factory();
    Generate::configure(new Sdk(
        httpClient: $client,
        requestFactory: $factory,
        streamFactory: $factory,
    ));
}

it('generates text end to end through the TogetherAI vertical', function () {
    $client = new FakeHttpClient(200, json_encode([
        'id' => 'chatcmpl_togetherai',
        'object' => 'chat.completion',
        'created' => 1710000000,
        'model' => 'meta-llama/Llama-3.3-70B-Instruct-Turbo',
        'system_fingerprint' => 'fp_togetherai',
        'choices' => [['index' => 0, 'message' => ['content' => 'Hello from TogetherAI'], 'finish_reason' => 'stop']],
        'usage' => ['prompt_tokens' => 7, 'completion_tokens' => 3],
    ]));
    configureTogetherAIWith($client);

    TogetherAI::create(['apiKey' => 'togetherai-test']);

    $result = Generate::text('Hi')->model(TogetherAI::model('meta-llama/Llama-3.3-70B-Instruct-Turbo'))->run();

    expect($result->text)->toBe('Hello from TogetherAI')
        ->and($result->usage->inputTokens)->toBe(7)
        ->and($result->providerMetadata['togetherai']['id'])->toBe('chatcmpl_togetherai')
        ->and($result->providerMetadata['togetherai']['model'])->toBe('meta-llama/Llama-3.3-70B-Instruct-Turbo')
        ->and($result->providerMetadata['togetherai']['choice_finish_reason'])->toBe('stop');

    $body = $client->sentBody();
    expect($body['model'])->toBe('meta-llama/Llama-3.3-70B-Instruct-Turbo')
        ->and($body['messages'][0]['role'])->toBe('user')
        ->and($body['stream'])->toBeFalse();

    expect($client->lastRequest->getHeaderLine('Authorization'))->toBe('Bearer togetherai-test');
});

it('normalizes provider-neutral text usage fields', function () {
    $client = new FakeHttpClient(200, json_encode([
        'choices' => [['index' => 0, 'message' => ['content' => 'Hello from TogetherAI'], 'finish_reason' => 'stop']],
        'usage' => ['input_tokens' => 13, 'output_tokens' => 6, 'total_tokens' => 19],
    ]));
    configureTogetherAIWith($client);

    TogetherAI::create(['apiKey' => 'togetherai-test']);

    $result = Generate::text('Hi')->model(TogetherAI::model('meta-llama/Llama-3.3-70B-Instruct-Turbo'))->run();

    expect($result->usage->inputTokens)->toBe(13)
        ->and($result->usage->outputTokens)->toBe(6)
        ->and($result->usage->totalTokens)->toBe(19);
});

it('maps a 429 to a rate limit exception', function () {
    $client = new FakeHttpClient(429, json_encode(['error' => ['message' => 'slow down']]));
    configureTogetherAIWith($client);
    TogetherAI::create(['apiKey' => 'togetherai-test']);

    Generate::text('Hi')->model(TogetherAI::model('meta-llama/Llama-3.3-70B-Instruct-Turbo'))->run();
})->throws(\AiSdk\Exceptions\RateLimitException::class);

it('falls back to json_object structured output for models without json_schema support', function () {
    $client = new FakeHttpClient(200, json_encode([
        'choices' => [['message' => ['content' => '{"city":"Lahore","country":"Pakistan"}'], 'finish_reason' => 'stop']],
        'usage' => ['prompt_tokens' => 10, 'completion_tokens' => 7],
    ]));
    configureTogetherAIWith($client);
    TogetherAI::create(['apiKey' => 'togetherai-test']);

    $result = Generate::text('Extract the city and country from: Lahore, Pakistan.')
        ->model(TogetherAI::model('togetherai-small-latest'))
        ->output(Schema::object(
            name: 'address',
            properties: [
                Schema::string(name: 'city')->required(),
                Schema::string(name: 'country')->required(),
            ],
        ))
        ->run();

    $body = $client->sentBody();

    expect($body['response_format'])->toBe(['type' => 'json_object'])
        ->and($body['messages'][0]['role'])->toBe('system')
        ->and($body['messages'][0]['content'])->toContain('valid JSON object')
        ->and($result->output)->toBe(['city' => 'Lahore', 'country' => 'Pakistan']);
});

it('accepts opaque text model ids without a model inventory', function () {
    TogetherAI::create(['apiKey' => 'togetherai-test']);

    expect(TogetherAI::model('future-private-model')->modelId())->toBe('future-private-model');
});

it('sends requests to the documented Together AI endpoint', function () {
    $client = new FakeHttpClient(200, json_encode(['choices' => [['index' => 0, 'message' => ['content' => 'ok'], 'finish_reason' => 'stop']], 'usage' => []]));
    configureTogetherAIWith($client);
    TogetherAI::create(['apiKey' => 'together-test']);

    Generate::text('Hi')->model(TogetherAI::model('meta-llama/Llama-3.3-70B-Instruct-Turbo'))->run();

    expect((string) $client->lastRequest->getUri())->toBe('https://api.together.ai/v1/chat/completions');
});
