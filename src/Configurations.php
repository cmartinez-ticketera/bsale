<?php

namespace ticketeradigital\bsale;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Configurations
{
    public function __construct(public ConfigurationEntities $entity)
    {

    }

    public function fetchAll()
    {
        $this->forget();
        Bsale::fetchAllAndCallback($this->entity->getEndpoint(), fn (array $items) => $this->saveEntity($items));
    }

    public function getCacheKey():string
    {
        return Str::of($this->entity->name)->snake()->prepend("bsale.");
    }

    public function saveEntity(array $items){
        $currentValue = cache($this->getCacheKey(), []);
        foreach ($items as $item) {
            $currentValue[$item["id"]] = $item;
        }
        cache()->forever($this->getCacheKey(), $currentValue);
    }

    /**
     * @return void
     */
    public function forget(): void
    {
        Cache::forget($this->getCacheKey());
    }

    public function getValues(): array
    {
        return Cache::get($this->getCacheKey(), function () {
            $this->fetchAll();
            return cache($this->getCacheKey(), []);
        });
    }



}
