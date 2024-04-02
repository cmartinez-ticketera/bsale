<?php

namespace ticketeradigital\bsale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ticketeradigital\bsale\Bsale;

class BsaleDocument extends Model
{
    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(BsaleDetail::class, 'document_id', 'document_id');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(BsaleReturn::class, 'reference_document_id', 'internal_id');
    }

    public function shippings(): HasMany
    {
        return $this->hasMany(BsaleShipping::class, 'guide_id', 'document_id');
    }

    public function fetchDetails(): void
    {
        BsaleDetail::fetchDocumentDetails($this->document_id);
        $this->load("details");
    }

    public static function fetchOne($id)
    {
        $data = Bsale::makeRequest('/v1/documents/' . $id . '.json');
        return self::updateOrCreate(
            ["document_id" => $id],
            ["data" => $data]
        );
    }
}
