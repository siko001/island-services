<?php

namespace App\Models\Post;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionNoteProduct extends Model
{
    protected $table = 'collection_note_products';
    protected $fillable = [
        "collection_note_id",
        "product_id",
        "quantity",
        'timestamps',
    ];
    protected $casts = [
        'timestamps' => 'date',
    ];

    public function collectionNote(): BelongsTo
    {
        return $this->belongsTo(CollectionNote::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
