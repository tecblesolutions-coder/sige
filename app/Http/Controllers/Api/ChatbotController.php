<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ChatbotService $chatbot)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $reply = $chatbot->getReply($request->input('message'));

        return response()->json(['reply' => $reply]);
    }
}
