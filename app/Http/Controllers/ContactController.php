<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        Mail::to('afamka2007@gmail.com')->send(new ContactMail($validated));

        Mail::to($validated['email'])->send(new ContactMail($validated));

        return response()->json([
            'message' => 'Сообщение успешно отправлено!'
        ]);
    }
}