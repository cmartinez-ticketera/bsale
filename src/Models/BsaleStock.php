<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Throwable;
use ticketeradigital\bsale\Bsale;
use ticketeradigital\bsale\BsaleException;
use ticketeradigital\bsale\Events\ResourceUpdated;
use ticketeradigital\bsale\Events\StockUpdated;
use ticketeradigital\bsale\Interfaces\WebhookHandlerInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BsaleStock extends Model implements WebhookHandlerInterface
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

    public function variant(): BelongsTo
    {
        return $this->belongsTo(BsaleVariant::class, 'variant_id', 'internal_id');
    }

    /**
     * @throws Throwable
     */
    public function fetch(): array
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
            $stock = self::firstOrNew([
                'internal_id' => $item['id'],
            ]);
            $stock->data = $item;
            $stock->save();
        }
    }

    /**
     * @throws BsaleException
     */
    public static function fetchAll(array $params = []): void
    {
        $query = Arr::query($params);
        $url = Str::of("/v1/stocks.json")->when($query, fn ($url) => $url->append("?".$query));
        Bsale::fetchAllAndCallback($url, [self::class, 'upsertMany']);
    }

    public static function fetchForVariant(BsaleVariant|string|int $variant): void
    {
        $variantId = $variant instanceof BsaleVariant ? $variant->internal_id : $variant;
        self::fetchAll(["variantId" => $variantId]);
    }

    public static function fetchOne($id): self
    {
        $data = Bsale::makeRequest("/v1/stocks/$id.json");

        return self::updateOrCreate(
            ['internal_id' => $id],
            ['data' => $data]
        );
    }

    public static function handleWebhook(ResourceUpdated $resource): void
    {
        self::fetchOne($resource->resourceId);
    }
}
