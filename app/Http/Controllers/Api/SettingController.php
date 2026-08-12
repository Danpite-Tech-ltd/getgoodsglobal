<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Contact;

use App\Models\GeneralSetting;
use App\Models\Bank;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function settings()
    {
        $settings = GeneralSetting::first();

        return response()->json([
            'status' => 'success',
            'message' => 'Settings',
            'data' => $settings,
        ], 200);
    }

    public function contact()
    {
        $contact = Contact::first();

        return response()->json([
            'status' => 'success',
            'message' => 'Contact',
            'data' => $contact,
        ], 200);
    }

    public function bank_lists()
    {
        $banks = Bank::where('status',1)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Bank Lists',
            'data' => $banks,
        ], 200);
    }

}
