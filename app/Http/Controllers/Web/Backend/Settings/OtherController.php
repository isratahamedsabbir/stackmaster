<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Http\Controllers\Controller;

class OtherController extends Controller
{
    public function index(){
        $settings = [
            'mail'       => env('MAIL', ''),
            'sms'        => env('SMS', ''),
            'recaptcha'  => env('RECAPTCHA_ENABLE', ''),
        ];
        return view('backend.layouts.settings.other_settings', compact('settings'));
    }
    
    public function mail(){
        if(env('MAIL') === 'off'){
            $envContent = str_replace('MAIL=off', 'MAIL=on', file_get_contents(base_path('.env')));
            file_put_contents(base_path('.env'), $envContent);
        }else{
            $envContent = str_replace('MAIL=on', 'MAIL=off', file_get_contents(base_path('.env')));
            file_put_contents(base_path('.env'), $envContent);
        }

        return response()->json([
            'status' => 't-success',
            'message' => 'Your action was successful!'
        ]);
    }

    public function sms(){
        if(env('SMS') === 'off'){
            $envContent = str_replace('SMS=off', 'SMS=on', file_get_contents(base_path('.env')));
            file_put_contents(base_path('.env'), $envContent);
        }else{
            $envContent = str_replace('SMS=on', 'SMS=off', file_get_contents(base_path('.env')));
            file_put_contents(base_path('.env'), $envContent);
        }

        return response()->json([
            'status' => 't-success',
            'message' => 'Your action was successful!'
        ]);
    }

    public function recaptcha(){
        if(env('RECAPTCHA_ENABLE') === 'no'){
            $envContent = str_replace('RECAPTCHA_ENABLE=no', 'RECAPTCHA_ENABLE=yes', file_get_contents(base_path('.env')));
            file_put_contents(base_path('.env'), $envContent);
        }else{
            $envContent = str_replace('RECAPTCHA_ENABLE=yes', 'RECAPTCHA_ENABLE=no', file_get_contents(base_path('.env')));
            file_put_contents(base_path('.env'), $envContent);
        }

        return response()->json([
            'status' => 't-success',
            'message' => 'Your action was successful!'
        ]);
    }

}
