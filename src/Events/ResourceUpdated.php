<?php

namespace ticketeradigital\bsale\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResourceUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $action,
        public string $topic,
        public string $resourceId,
        public string $link,
        public array $others
    ) {
    }
}
