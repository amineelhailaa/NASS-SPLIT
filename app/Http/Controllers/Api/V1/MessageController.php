<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\MessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use ApiResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MessageRequest $request,Conversation $conversation)
    {
        $user = $request->user();
        abort_unless(
            $user->conversations()->whereKey($conversation->id)->exists(), 404
        );

        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'message' => $request->input('message'),
        ]);

        return $this->createdResponse($message);
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
    public function destroy(Message $message, Request $request)
    {
        if($message->user_id !== $request->user()->id) abort(403);
        $user = $request->user();
        $message->delete();
        return $this->noContentResponse();
    }
}
