<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\Messages\MessageCollection;
use App\Models\User;
use App\Services\MessagesService;
use App\Traits\ResponseTrait;


class MessagesController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = auth()->user();
        $conversation = $user?->conversations()->findOrFail($id);
        $messages = $conversation?->messages()->latest()->paginate();
        $messages = MessageCollection::make($messages);

        return $this->getResponse(200,"success", $messages);

    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreMessageRequest $request,MessagesService $messagesService)
    {
        return $messagesService->setRequest($request)->storeMessages();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
