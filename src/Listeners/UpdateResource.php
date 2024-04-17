<?php

namespace ticketeradigital\bsale\Listeners;

use ticketeradigital\bsale\Events\ResourceUpdated;
use ticketeradigital\bsale\Models\BsaleDocument;
use ticketeradigital\bsale\Models\BsalePrice;
use ticketeradigital\bsale\Models\BsaleProduct;
use ticketeradigital\bsale\Models\BsaleStock;
use ticketeradigital\bsale\Models\BsaleVariant;

class UpdateResource
{
    private const HANDLERS = [
        'document' => BsaleDocument::class,
        'product' => BsaleProduct::class,
        'variant' => BsaleVariant::class,
        'price' => BsalePrice::class,
        'stock' => BsaleStock::class,
    ];

    public function handle(ResourceUpdated $resource)
    {
        logger('Hola desde el handler.');
        $handler = array_key_exists($resource->topic, self::HANDLERS);
        if (! $handler) {
            return;
        }

    }
}
