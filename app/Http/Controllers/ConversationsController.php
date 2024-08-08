<?php

namespace App\Http\Controllers;

use App\Http\Resources\Conversations\ConversationCollection;
use App\Http\Resources\Conversations\ConversationResource;
use App\Models\Conversation;
use App\Models\User;
use App\Support\HandleExceptionSupport;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ConversationsController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $user = auth()->user();
        $conversations =  ConversationCollection::make($user?->conversations()->paginate());
        if (!$conversations)
        {
            return $this->getResponse(message: 'There are no conversations',data: $conversations);

        }
         return $this->getResponse(data: $conversations);
    }

    public function show(Conversation $conversation): JsonResponse
    {
        $conversation = ConversationResource::make( $conversation->load('participants'));
        return $this->getResponse(message: 'Conversation fetched successfully',data: $conversation);
    }

    public function addParticipant(Request $request,Conversation $conversation)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);
        $userId = $request->post('user_id');
        if ($conversation->participants()->where('user_id',$userId)->exists())
        {
            return HandleExceptionSupport::badRequest('This user already exists in conversation!');
        }
        $conversation->participants()->attach($userId,['joined_at' => Carbon::now()]);
        return $this->getResponse(201,"Participant added successfully");
    }

    public function removeParticipant(Request $request,Conversation $conversation)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $conversation->participants()->detach($request->post('user_id'));
        return $this->getResponse(message: "Participant removed successfully");

    }

}
