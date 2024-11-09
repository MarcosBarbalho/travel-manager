<?php

namespace App\Models;

use App\Enums\TripOrder\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $attributes = [
        'status' => Status::REQUESTED,
    ];

    protected $fillable = [
        'departure_at',
        'destination',
        'requester_name',
        'return_at',
        'status',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'timestamp',
            'deleted_at' => 'timestamp',
            'departure_at' => 'timestamp',
            'return_at' => 'timestamp',
            'status' => Status::class,
            'updated_at' => 'timestamp',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
