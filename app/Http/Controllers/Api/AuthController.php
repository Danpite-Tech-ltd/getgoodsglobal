<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SmsGateway;
use App\Models\GeneralSetting;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function userRegister(Request $request)
    {
        
        try {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => ['required', 'regex:/^01[0-9]{9}$/', 'unique:customers,phone'],
                'email' => 'nullable|email|unique:customers,email',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all(),
                ], 422);
            }

            DB::beginTransaction();

            $customer = new Customer();
            $customer->name = $request->name;
            $customer->slug = Str::slug($request->name . '-' . uniqid());
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->password = bcrypt($request->password);
            $customer->verify = 1;
            $customer->status = 'active';
            $customer->save();

            $token = $customer->createToken('authToken')->plainTextToken;
            
            $generalsetting = GeneralSetting::where('status',1)->first();
            $sms_gateway = SmsGateway::where('status',1)->first();
            $url = env('APP_URL');
            
            if( $request->email){
                if (config('mail.default') !== 'sendmail') {
                    Mail::to($request->email)->send(new \App\Mail\RegisterMail($generalsetting->name, $request->name, $url));
                }
            }
            
            $response = Http::get('http://bulksmsbd.net/api/smsapi', [
                    'api_key'  => $sms_gateway->api_key,
                    'type'     => 'text',
                    'number'   => $request->phone,
                    'senderid' => $sms_gateway->serderid,
                    'message'  => "Dear {$request->name}, welcome to {$generalsetting->name}! Your registration is successful. Get ready for an amazing journey filled with trust, quality, and convenience.\r\n{$generalsetting->name}\r\n" . $url
                ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User Registered Successfully!',
                'token' => $token,
                'user' => $customer,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

 public function userLogin(Request $request)
    {

        try {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'login' => 'required|string',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,

                    'message' => $validator->errors()->all(),

                ], 422);
            }

            $login = $request->login;
            $user = Customer::where('phone', $login)
                ->orWhere('email', $login)
                ->first();


            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry! No account found',
                ], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credential.',
                ], 401);
            }
            // dd($login);
            // $user->tokens()->delete();

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful!',
                'token' => $token,
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function userLogout(Request $request)
    {
        // dd('hello');
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout successful!',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
            ], 500);
        }
    }
    
    public function getCsrfToken(Request $request)
    {
        $token = csrf_token();

        return response()->json([
            'status' => 'success',
            'csrf_token' => $token,
        ]);
    }
    
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'login' => 'required'
        ]);

        $login = $request->login;

        // Email check
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {

            $user = Customer::where('email', $login)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not found'
                ], 404);
            }

            $otp = rand(1000, 9999);

            $user->update([
                'forgot' => $otp
            ]);

            // Send OTP mail
            if (config('mail.default') !== 'sendmail') {
                Mail::to($user->email)->send(new \App\Mail\ForgotPasswordOtpMail($otp));
            }
            return response()->json([
                'status' => true,
                'message' => 'OTP sent to your email'
            ]);
        }

        // Phone number (future)
        $user = Customer::where('phone', $login)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Phone number not found'
            ], 404);
        }else{
            $generalsetting = GeneralSetting::where('status',1)->first();
            $sms_gateway = SmsGateway::where('status',1)->first();
            $otp = rand(1000, 9999);

            $user->update([
                'forgot' => $otp
            ]);

            // $url = $sms_gateway->url;
            // $api_key = $sms_gateway->api_key;
            // $senderid = $sms_gateway->serderid;
            // $number = $request->login;
            // $message = "Thank you for choosing $generalsetting->name. Use the following OTP to reset your password. Your OTP code is $otp.";

         
            // $data = [
            //     "api_key" => $api_key,
            //     "senderid" => $senderid,
            //     "number" => $number,
            //     "message" => $message
            // ];
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // $response = curl_exec($ch);
            // curl_close($ch);
            
            $response = Http::get('http://bulksmsbd.net/api/smsapi', [
                    'api_key'  => $sms_gateway->api_key,
                    'type'     => 'text',
                    'number'   => $request->login,
                    'senderid' => $sms_gateway->serderid,
                    'message'  => "Thank you for choosing $generalsetting->name. Use the following OTP to reset your password. Your OTP code is $otp."
                ]);
            
            return response()->json([
            'status' => true,
            'message' => 'OTP sent to your mobile'
        ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong'
        ], 500);
    }
    
    public function verifyOTP(Request $request){
         $request->validate([
            'otp' => 'required|numeric'
        ]);
    
        try {
            $customer = Customer::where('forgot', $request->otp)->firstOrFail();
    
            // OTP invalidate
            $customer->forgot = null;
            // $customer->password_reset_allowed = true;
            $customer->save();
    
            return response()->json([
                'status'  => true,
                'message' => 'OTP verified successfully',
                'customer_id' => $customer->id
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid OTP'
            ], 422);
        }
    }
    
    public function resetPassword(Request $request){
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);
    
        $customer = Customer::where('id', $request->customer_id)->first();
    
        if (!$customer) {
            return response()->json([
                'status'  => false,
                'message' => 'User Not Found'
            ], 403);
        }
    
        $customer->password = Hash::make($request->password);
        // $customer->password_reset_allowed = false;
        $customer->save();
    
        return response()->json([
            'status'  => true,
            'message' => 'Password reset successful'
        ], 200);
    }
}


