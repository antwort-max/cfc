<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricDocument extends Model
{
    use HasFactory;

    protected $table = 'historic_documents';

    protected $fillable = [
        'document_id',
        'document_date',
        'document_time',
        'document_type',
        'document_number',
        'client',
        'place',
        'seller',
        'total_sales_amount',
        'total_sales_amount_with_tax',
    ];

    protected $casts = [
        'document_date' => 'date',
        'document_time' => 'string',
        'total_sales_amount' => 'integer',
        'total_sales_amount_with_tax' => 'integer',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(
            HistoricProduct::class,
            'document_number',   // key en historic_products
            'document_number'    // key en historic_documents
        );
    }
}

