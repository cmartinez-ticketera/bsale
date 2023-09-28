<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;
use ticketeradigital\bsale\Events\VariantUpdated;

class BsaleVariant extends Model
{
    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $dispatchesEvents = [
        'saved' => VariantUpdated::class,
        'updated' => VariantUpdated::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(BsaleProduct::class, 'product_id', 'internal_id');
    }

    public function stock(): HasOne
    {
        return $this->hasOne(BsaleStock::class, 'variant_id', 'internal_id');
    }

    public function price(): HasOne
    {
        return $this->hasOne(BsalePrice::class, 'variant_id', 'internal_id');
    }

    /**
     * @throws \Throwable
     */
    public function consume(string $note, int $officeId = null, int $quantity = 1): array
    {
        $_officeId = $officeId ?? config('bsale.default_office_id');
        throw_if(! $_officeId, \Exception::class, 'Office id not set');
        $params = [
            'note' => $note,
            'officeId' => 2,
            'details' => [[
                'quantity' => $quantity,
                'variantId' => $this->internal_id,
            ]],
        ];

        return Bsale::makeRequest('/v1/stocks/consumptions.json', $params, 'POST');
    }

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
