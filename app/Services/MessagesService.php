<?php

namespace App\Services;

use App\Enums\ConversationType;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessagesService
{

    private Request $request;

    public function getAuthenticatedUser(): ?Authenticatable
    {
        return auth()->user();
    }
    public function setRequest($request): static
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @throws \Throwable
     */
    public function storeMessages()
    {
        $conversation_id = $this->request->post('conversation_id');
        $user_id = $this->request->post('user_id');
        $user = $this->getAuthenticatedUser();
        DB::beginTransaction();
        try {
            if ($conversation_id)
            {
                $conversation = $user->conversations()->findOrFail($conversation_id);
            }
            else
            {
                $conversation = Conversation::query()->where('type','peer')
                    ->whereHas('participants',function ($query) use ($user_id,$user){
                   $query->join('participants as participants2','participants2.conversation_id','=','participants.conversation_id')
                   ->where('participants.user_id',$user->id)
                   ->where('participants2.user_id',$user_id);
                    })->first();
                if (!$conversation)
                {
                    $conversation =Conversation::query()->create([
                        'type' => ConversationType::Peer,
                        'user_id' => $user->id,
                    ]);
                    $conversation->participants()
                        ->attach([
                            $user_id=>['joined_at'=>now()],
                            $user->id=>['joined_at'=>now()]
                        ]);

                }


            }

            $message=  $conversation->messages()->create([
                'user_id' => $user->id,
                'body' => $this->request->post('body')
            ]);

            DB::statement('INSERT INTO recipients (user_id,message_id)
            SELECT user_id,? FROM participants
                 WHERE conversation_id=? AND user_id <>?',
                [$message->id, $conversation_id, $user->id]);
            $conversation->update([
                'last_message_id'=>$message->id,
            ]);

            DB::commit();
            return $message;
        }catch (\Throwable $throwable)
        {
            DB::rollBack();
            throw $throwable;
        }


    }
}
