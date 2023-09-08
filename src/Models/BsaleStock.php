<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;
use ticketeradigital\bsale\Events\StockUpdated;

class BsaleStock extends Model
{
    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $dispatchesEvents = [
        'saved' => StockUpdated::class,
        'updated' => StockUpdated::class,
    ];

    /**
     * @throws Throwable
     */
    public function refresh(): array
    {
        $id = $this->internal_id;
        $response = Bsale::makeRequest("/v1/stocks/$id.json");
        $this->data = $response;
        $this->save();

        return $response;
    }

    public static function upsertMany(array $items): void
    {
        foreach ($items as $item) {
            $product = self::firstOrCreate([
                'internal_id' => $item['id'],
            ], ['data' => $item]);
            Log::debug("Stock $product->id created.");
        }
    }

    /**
     * @throws BsaleException
     */
    public static function fetchAll(): void
    {
        Bsale::fetchAllAndCallback('/v1/stocks.json', [self::class, 'upsertMany']);
    }
}
