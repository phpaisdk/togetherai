<?php

declare(strict_types=1);

use AiSdk\Generate;
use AiSdk\TogetherAI;

afterEach(function () {
    Generate::reset();
    TogetherAI::reset();
});

it('uses adapter capabilities for opaque TogetherAI model ids', function () {
    TogetherAI::create(['apiKey' => 'togetherai-test']);
    $model = TogetherAI::model('vendor/private-model');

    expect($model->modelId())->toBe('vendor/private-model');
});
