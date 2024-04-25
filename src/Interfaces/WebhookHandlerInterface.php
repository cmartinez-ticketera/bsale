<?php

namespace ticketeradigital\bsale\Interfaces;

use ticketeradigital\bsale\Events\ResourceUpdated;

interface WebhookHandlerInterface
{
    public static function handleWebhook(array $data, ResourceUpdated $resource): void;
}
