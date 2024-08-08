<?php

namespace App\Models;

use App\Enums\ConversationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;
    protected $casts = [
        'type'=>ConversationType::class,
    ];

    protected $fillable = [
        'user_id',
        'label',
        'last_message_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'participants')
            ->withPivot(['role','joined_at']);
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class,'last_message_id')
            ->withDefault([]);
    }
}
