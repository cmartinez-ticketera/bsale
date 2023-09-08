<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;

class BsaleVariant extends Model
{
    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public static function upsertMany(array $items): void
    {
        foreach ($items as $item) {
            $product = self::firstOrCreate([
                'internal_id' => $item['id'],
            ], ['data' => $item]);
            Log::debug("Variant $product->id created.");
        }
    }

    /**
     * @throws BsaleException
     */
    public static function fetchAll(): void
    {
        Bsale::fetchAllAndCallback('/v1/variants.json', [self::class, 'upsertMany']);
    }
}
