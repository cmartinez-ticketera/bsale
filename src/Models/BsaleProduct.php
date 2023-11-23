<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;
use ticketeradigital\bsale\Events\ProductUpdated;

class BsaleProduct extends Model
{
    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $dispatchesEvents = [
        'saved' => ProductUpdated::class,
        'updated' => ProductUpdated::class,
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(BsaleVariant::class, 'product_id', 'internal_id');
    }

    public function fetch(): array
    {
        $id = $this->internal_id;
        $response = Bsale::makeRequest("/v1/products/$id.json");
        $this->data = $response;
        $this->save();

        return $response;
    }

    public static function upsertMany(array $items): void
    {
        foreach ($items as $item) {
            $product = self::firstOrNew([
                'internal_id' => $item['id'],
            ]);
            $product->data = $item;
            $product->save();
        }
    }

    /**
     * @throws BsaleException
     */
    public static function fetchAll(): void
    {
        Bsale::fetchAllAndCallback('/v1/products.json', [self::class, 'upsertMany']);
    }

    public function getControlsStockAttribute()
    {
        return (bool) $this->data['stockControl'];
    }

    public function getEnabledAttribute()
    {
        return ! $this->data['state'];
    }
}
