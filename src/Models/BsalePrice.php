<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;

class BsalePrice extends Model
{
    protected $fillable = [
        'data',
        'price_list_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public static function upsertMany(array $items, $priceListId): void
    {
        foreach ($items as $item) {
            Log::debug("Price list: $priceListId");
            $product = self::firstOrCreate([
                'internal_id' => $item['id'],
            ], ['data' => $item, 'price_list_id' => $priceListId]);
            Log::debug("Price $product->id created.");
        }
    }

    /**
     * @throws BsaleException
     */
    public static function fetchPriceList(int $priceListId): void
    {
        Bsale::fetchAllAndCallback("/v1/price_lists/$priceListId/details.json", [self::class, 'upsertMany'], $priceListId);
    }
}
