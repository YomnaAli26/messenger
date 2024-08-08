<?php

namespace App\Models;

use App\Enums\ParticipantType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Participant extends Pivot
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'participants';
    protected $casts = [
        'joined_at' => 'datetime',
        'role' => ParticipantType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
