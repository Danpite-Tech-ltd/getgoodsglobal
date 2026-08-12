<?php

namespace App\Http\Controllers;

use App\Mail\MailSender;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;
use Karim007\LaravelBkashTokenize\Facade\BkashRefundTokenize;
use Illuminate\Support\Facades\Mail;

class BkashTokenizePaymentController extends Controller
{
    public function index()
    {
        return view('bkashT::bkash-payment');
    }
    public function createPayment(Request $request)
    {
        $order_id = $request->order_id;
        $amount = $request->amount;
        $inv = $request->invoice_id;
        $request['intent'] = 'sale';
        $request['mode'] = '0011'; //0011 for checkout
        $request['payerReference'] = $inv;
        $request['currency'] = 'BDT';
        $request['amount'] = $amount;
        $request['merchantInvoiceNumber'] = $inv;
        $request['callbackURL'] = route('bkash-callBack');

        $request_data_json = json_encode($request->all());

        $response = BkashPaymentTokenize::cPayment($request_data_json);
        // return $response;
        if (isset($response['statusMessage']) && $response['statusMessage'] == 'Successful') {

            if ($response['statusMessage'] == 'Successful') {
                $order = Order::find($order_id);
                $order->bkash_paymentId = $response['paymentID'];
                $order->order_placed_using_payment = 'true';
                $order->order_type = "bKash";
                $order->save();

            }
        }


        // return $response;
        //$response =  BkashPaymentTokenize::cPayment($request_data_json,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..

        //store paymentID and your account number for matching in callback request
        // dd($response) //if you are using sandbox and not submit info to bkash use it for 1 response

        if (isset($response['bkashURL']))
            return redirect()->away($response['bkashURL']);
        else
            return redirect()->back()->with('error-alert2', $response['statusMessage']);
    }

    public function callBack(Request $request)
    {
        //callback request params
        // paymentID=your_payment_id&status=success&apiVersion=1.2.0-beta
        //using paymentID find the account number for sending params

        if ($request->status == 'success') {
            $response = BkashPaymentTokenize::executePayment($request->paymentID);
            //$response = BkashPaymentTokenize::executePayment($request->paymentID, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
            if (!$response) { //if executePayment payment not found call queryPayment
                $response = BkashPaymentTokenize::queryPayment($request->paymentID);
                //$response = BkashPaymentTokenize::queryPayment($request->paymentID,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
            }

            // dd(json_encode($response));

            $orderId = null;
            if ($response['statusMessage'] == 'Successful') {
                $order = Order::where("bkash_paymentId", $response['paymentID'])->latest()->first();
                $order->bkash_tranxId = $response['trxID'];
                $orderId = $order->id;
                $order->save();











                $customer = Customer::find($order->customer_id);

                if ($customer && $customer->email != null) {
                    $to = $customer;
                    $mgsd = "Order Placed";
                    $subject = "Your Order Placed Successfully";
                    $username = $customer->name;
                    $ordersOnly = $order;
                    $orderDetails = OrderDetails::where('order_id', $orderId)->get();

                    Mail::to($to)->send(new MailSender($mgsd, $subject, $username, $ordersOnly, $orderDetails));
                }
            }

            if (isset($response['statusCode']) && $response['statusCode'] == "0000" && $response['transactionStatus'] == "Completed") {
                /*
                 * for refund need to store
                 * paymentID and trxID
                 * */
                // return BkashPaymentTokenize::success('Thank you for your payment', $response['trxID']);
                return redirect()->route('customer.order_success', ['id' => $orderId]);
            }
            return BkashPaymentTokenize::failure($response['statusMessage']);
        } else if ($request->status == 'cancel') {
            return BkashPaymentTokenize::cancel('Your payment is canceled');
        } else {
            return BkashPaymentTokenize::failure('Your transaction is failed');
        }
    }

    public function searchTnx($trxID)
    {
        //response
        return BkashPaymentTokenize::searchTransaction($trxID);
        //return BkashPaymentTokenize::searchTransaction($trxID,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }

    public function refund(Request $request)
    {
        $paymentID = 'Your payment id';
        $trxID = 'your transaction no';
        $amount = 5;
        $reason = 'this is test reason';
        $sku = 'abc';
        //response
        return BkashRefundTokenize::refund($paymentID, $trxID, $amount, $reason, $sku);
        //return BkashRefundTokenize::refund($paymentID,$trxID,$amount,$reason,$sku, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }
    public function refundStatus(Request $request)
    {
        $paymentID = 'Your payment id';
        $trxID = 'your transaction no';
        return BkashRefundTokenize::refundStatus($paymentID, $trxID);
        //return BkashRefundTokenize::refundStatus($paymentID,$trxID, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }
}
