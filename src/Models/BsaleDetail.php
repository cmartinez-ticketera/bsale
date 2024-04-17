<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;

class BsaleDetail extends Model
{
    protected $fillable = [
        'data',
        'document_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(BsaleVariant::class, 'variant_id', 'internal_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(BsaleDocument::class, 'document_id', 'document_id');
    }

    public function fetch(): array
    {
        $id = $this->internal_id;
        $documentId = $this->document_id;
        $response = Bsale::makeRequest("/v1/documents/$documentId/details/$id.json");
        $this->data = $response;
        $this->save();

        return $response;
    }

    public static function upsertMany(array $items, $documentId): void
    {
        foreach ($items as $item) {
            self::updateOrCreate(
                ['internal_id' => $item['id']],
                [
                    'internal_id' => $item['id'],
                    'data' => $item,
                    'document_id' => $documentId,
                ]
            );
        }
    }

    /**
     * @throws BsaleException
     */
    public static function fetchDocumentDetails(int $documentId): void
    {
        Bsale::fetchAllAndCallback("/v1/documents/$documentId/details.json", [self::class, 'upsertMany'], $documentId);
    }
}
