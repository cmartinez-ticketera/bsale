<?php

namespace ticketeradigital\bsale\Interfaces;

use ticketeradigital\bsale\Events\ResourceUpdated;

interface WebhookHandlerInterface
{
    public static function handleWebhook(ResourceUpdated $resource): void;
}
