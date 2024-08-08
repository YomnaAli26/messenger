<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\Participant;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = auth()->user();
        $conversation = $user->conversations()->findOrFail($id);
        return $conversation->messages()->latest()->paginate();

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $user = auth()->user();
        $conversation_id = $request->post('conversation_id');
        $user_id = $request->post('user_id');
        if ($conversation_id)
        {
            $conversation = $user->conversations()->findOrFail($conversation_id);
        }
        $messageId=  $conversation->messages()->create([
            'user_id' => $user_id,
            'message' => $request->post('body')
        ]);
        //N+1 query
        $participants = Participant::query()
            ->where('conversation_id', $conversation_id)
            ->where('user_id', '<>',$user_id)
            ->get();
        foreach ($participants as $participant)
        {
            Recipient::query()->create([
                'user_id' => $participant->user_id,
                'message_id' => $messageId->id,
            ]);
        }




        //two queries
        $userIds = Participant::query()
            ->where('conversation_id', $conversation_id)
            ->where('user_id', '<>', $user_id)
            ->pluck('user_id');
        $recipients = $userIds->map(function ($userId) use ($messageId) {
            return [
              'user_id' => $userId,
              'message_id'=>$messageId,
            ];
        });
        DB::table('recipients')->insert($recipients->toArray());

    //one query
   /*     DB::statement('INSERT INTO recipients (user_id,message_id)
            SELECT user_id,? FROM participants
                 WHERE conversation_id=? AND user_id <>?',
            [$messageId, $conversation_id, $user->id]);*/
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
