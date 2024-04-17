<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    /* Not to be used: a variant can have stocks as offices */
    public function stock(): HasOne
    {
        return $this->hasOne(BsaleStock::class, 'variant_id', 'internal_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(BsaleStock::class, 'variant_id', 'internal_id');
    }

    public function price(): HasOne
    {
        return $this->hasOne(BsalePrice::class, 'variant_id', 'internal_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BsaleDetail::class, 'variant_id', 'internal_id');
    }

    /**
     * @throws \Throwable
     */
    public function consume(string $note, ?int $officeId = null, int $quantity = 1): array
    {
        $_officeId = $officeId ?? config('bsale.default_office_id');
        throw_if(! $_officeId, \Exception::class, 'Office id not set');
        $params = [
            'note' => $note,
            'officeId' => $_officeId,
            'details' => [[
                'quantity' => $quantity,
                'variantId' => $this->internal_id,
            ]],
        ];

        return Bsale::makeRequest('/v1/stocks/consumptions.json', $params, 'POST');
    }

    public function fetch(): array
    {
        $id = $this->internal_id;
        $response = Bsale::makeRequest("/v1/variants/$id.json");
        $this->data = $response;
        $this->save();

        return $response;
    }

    public static function upsertMany(array $items): void
    {
        foreach ($items as $item) {
            $variant = self::firstOrNew([
                'internal_id' => $item['id'],
            ]);
            $variant->data = $item;
            $variant->save();
        }
    }

    /**
     * @throws BsaleException
     */
    public static function fetchAll(): void
    {
        Bsale::fetchAllAndCallback('/v1/variants.json', [self::class, 'upsertMany']);
    }

    public static function fetchOne(int $id): self
    {
        $data = Bsale::makeRequest('/v1/variants/'.$id.'.json');

        return self::updateOrCreate(
            ['internal_id' => $id],
            ['data' => $data]
        );
    }

    public function getunlimitedStockAttribute()
    {
        return (bool) $this->data['unlimitedStock'];
    }

    public function getEnabledAttribute()
    {
        return ! $this->data['state'];
    }
}
