<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;
use ticketeradigital\bsale\Events\PriceUpdated;

class BsalePrice extends Model
{
    protected $fillable = [
        'data',
        'price_list_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $dispatchesEvents = [
        'saved' => PriceUpdated::class,
        'updated' => PriceUpdated::class,
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(BsaleVariant::class, 'variant_id', 'internal_id');
    }

    public function fetch(): array
    {
        $id = $this->internal_id;
        $list = $this->price_list_id;
        $response = Bsale::makeRequest("/v1/price_lists/$list/details/$id.json");
        $this->data = $response;
        $this->save();

        return $response;
    }

    public static function upsertMany(array $items, $priceListId): void
    {
        foreach ($items as $item) {
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
