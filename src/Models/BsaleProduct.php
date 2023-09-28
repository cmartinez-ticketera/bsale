<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
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

    public static function upsertMany(array $items): void
    {
        foreach ($items as $item) {
            $product = self::firstOrCreate([
                'internal_id' => $item['id'],
            ], ['data' => $item]);
            Log::debug("Product $product->id created.");
        }
    }

    /**
     * @throws BsaleException
     */
    public static function fetchAll(): void
    {
        Bsale::fetchAllAndCallback('/v1/products.json', [self::class, 'upsertMany']);
    }
}
