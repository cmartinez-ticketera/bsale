<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;

class BsaleProductType extends Model
{
    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(BsaleProduct::class, 'product_type_id', 'internal_id');
    }

    public function fetch(): array
    {
        $id = $this->internal_id;
        $response = Bsale::makeRequest("/v1/product_types/$id.json");
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
        Bsale::fetchAllAndCallback('/v1/product_types.json', [self::class, 'upsertMany']);
    }
}
