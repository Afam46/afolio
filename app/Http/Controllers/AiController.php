<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function generate(Request $request)
    {
        $topic = $request->topic;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
        ])->post('https://api.deepseek.com/chat/completions', [

            'model' => 'deepseek-chat',

            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Ты помогаешь писать людям комментарии, 
                    но не предлагай варианты, пиши сразу готовое, без никакого контекста.'
                ],
                [
                    'role' => 'user',
                    'content' => "Напиши комментарий на тему: {$topic}"
                ]
            ]
        ]);

        return response()->json([
            'message' => $response['choices'][0]['message']['content'],
            'success' => 'Комментарий успешно сгенерирован!'
        ]);
    }
}