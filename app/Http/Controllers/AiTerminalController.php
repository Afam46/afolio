<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiTerminalController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'question' => 'required|max:500'
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
        ])->post('https://api.deepseek.com/chat/completions', [

            'model' => 'deepseek-chat',

            'messages' => [

                [
                    'role' => 'system',
                    'content' => config('ai.afam_info')
                ],

                [
                    'role' => 'user',
                    'content' => $request->question
                ]
            ]
        ]);

        return response()->json([
            'message' => $response['choices'][0]['message']['content']
        ]);
    }
}