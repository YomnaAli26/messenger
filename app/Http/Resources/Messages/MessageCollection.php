<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\Conversations\ConversationResource;
use App\Http\Resources\Conversations\ParticipantResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->collection,
            $this->collection->map(function ($message) {
              return  [
                  'id' => $message->id,
                  'message'=> $message->body,
                  'created_at' => $message->created_at->toDateString(),
                  'deleted_at' => $message->deleted_at?->toDateString(),
                  'conversation' => ConversationResource::make(Conversation::query()->find($message->conversation_id)),
                  ];
            }),
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
            ],
            ];
    }
}
