<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;
use ticketeradigital\bsale\Events\PriceUpdated;

class BsaleReturn extends Model
{
    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(BsaleDocument::class, 'reference_document_id', 'document_id');
    }

    public function creditNote(): BelongsTo
    {
        return $this->belongsTo(BsaleDocument::class, 'credit_note_id', 'document_id');
    }

    public static function upsertMany(array $items): void
    {
        foreach ($items as $item) {
            self::updateOrCreate(
                ["internal_id" => $item['id']],
                ["data" => $item]
            );
        }
    }

    /**
     * @throws BsaleException
     */
    public static function fetchAll(): void
    {
        Bsale::fetchAllAndCallback('/v1/returns.json', [self::class, 'upsertMany']);
    }

    public static function fetchOne($id)
    {
        $data = Bsale::makeRequest('/v1/returns/' . $id . '.json');
        return self::updateOrCreate(
            ["internal_id" => $id],
            ["data" => $data]
        );
    }

    public static function generate(array $params)
    {
        $response = Bsale::makeRequest('/v1/returns.json', $params, 'POST');
        return self::create(["data" => $response]);
    }

}
