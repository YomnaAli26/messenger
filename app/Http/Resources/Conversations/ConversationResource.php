<?php

namespace App\Http\Resources\Conversations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'label' => $this->label,
            'created_at'=>$this->created_at->toDateString(),
            'last_message_id'=>$this->last_message_id,
            'participants'=>ParticipantResource::collection(
                $this->whenLoaded('participants')
            ),
        ];
    }
}
