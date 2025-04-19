<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'text',
        'file',
        'room_id'
    ];

   /*  protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ]; */

    protected function casts(): array {
        return [
            'sender_id'   => 'integer',
            'receiver_id' => 'integer',
            'text'        => 'string',
        ];
    }

    protected $appends = [
        'humanize_date',
        'short_text'
    ];

    public function getFileAttribute($value): ?string
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return $value ? url($value) : null;
    }

    public function getShortTextAttribute(): string | null
    {
        return strlen($this->text) > 20 ? substr($this->text, 0, 20) . '...' : $this->text;
    }

    public function getHumanizeDateAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function sender(): BelongsTo {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
