<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:50',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'required|numeric|digits:11',
            'subject'   => 'nullable|string|max:255',
            'message'   => 'required|string|max:1000'
        ]);

        try {
            Contact::create($request->only('name', 'email', 'phone', 'subject', 'message'));
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'msg' => $e
            ]);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'msg' => 'Message sent successfully'
        ]);
    }
}
