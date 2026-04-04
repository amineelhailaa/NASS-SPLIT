<?php

namespace App\Http\Controllers;

use App\ApiResponses;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    use ApiResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $conversations = $user->conversations()
            ->with('lastMessage.user')
        ->withMax('messages','created_at')->orderByDesc('messages_max_created_at')
        ->get();
        return $this->successResponse($conversations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation, Request $request)
    {
        $user = $request->user();
        $conversation = $user->conversations()
            ->where('conversations.id', $conversation->id)
            ->with([
                'group',
                'messages'=>function   ($query) {
                    $query->latest('created_at');
                }
            ]);

        return $this->successResponse($conversation);
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
