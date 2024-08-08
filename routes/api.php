<?php

use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\MessagesController;
use Illuminate\Support\Facades\Route;


//Route::middleware('auth:sanctum')->group(function (){
   Route::get('/conversations', [ConversationsController::class,'index']);
   Route::get('/conversations/{conversation}', [ConversationsController::class,'show']);
   Route::post('/conversations/{conversation}/participants', [ConversationsController::class,'addParticipant']);
   Route::delete('/conversations/{conversation}/participants', [ConversationsController::class,'removeParticipant']);

   Route::get('/conversations/{conversation}/messages', [MessagesController::class,'index']);
   Route::post('/messages', [MessagesController::class,'store']);
   Route::delete('/messages/{message}', [MessagesController::class,'destroy']);

//});
