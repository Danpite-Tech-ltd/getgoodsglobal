<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Productsize;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\Visitor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use Toastr;
use Auth;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth')->except(['locked','unlocked']);
    }
    public function dashboard(Request $request)
    {
        $total_order = Order::count();
        $total_visitor = Visitor::count();
        $today_order = Order::where('created_at', '>=', Carbon::today())->count();
        $total_product = Product::count();
        $total_sku = Product::withCount('sizes')->get()->sum('sizes_count');
        $available_sku = Product::withCount(['sizes' => function ($query) {
            $query->where('stock', '>', 0);
        }])->get()->sum('sizes_count');

        $total_inventory = Product::whereHas('sizes', function ($q) {
            $q->where('stock', '>', 0);
        })
        ->join('productsizes', 'products.id', '=', 'productsizes.product_id')
        ->sum(\DB::raw('productsizes.SalePrice * productsizes.stock'));


        $stock_out_sku = Product::withCount(['sizes' => function ($query) {
            $query->where('stock', '<', 1);
        }])->get()->sum('sizes_count');
        $low_sku = Product::withCount(['sizes' => function ($query) {
            $query->where('stock', '>=', 1)->where('stock', '<=', 5);
        }])->get()->sum('sizes_count');

        $total_customer = Customer::count();
        $total_sale = Order::where(['order_status' => '9'])->count();
        $total_revenue = Order::where(['order_status' => '9'])->get()->sum('amount');
        $aov = $total_sale > 0 ? $total_revenue / $total_sale : 0;

        $latest_order = Order::latest()->limit(5)->with('customer', 'product', 'product.image')->get();
        $latest_customer = Customer::latest()->limit(5)->get();
        $today_delivery = Order::where(['order_status' => '5'])->where('created_at', '>=', Carbon::today())->count();
        $total_delivery = Order::where(['order_status' => '5'])->count();
        $last_week = Order::where(['order_status' => '5'])->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $last_month = Order::where(['order_status' => '5'])->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $monthly_sale = Order::select(DB::raw('DATE(created_at) as date', 'created_at'))->selectRaw("SUM(amount) as amount")->where(['order_status' => '5'])->groupBy('date')->limit(30)->get();

        // order
        $pending_order = Order::where(['order_status' => '1'])->count();
        $confirm_order = Order::where(['order_status' => '2'])->count();
        $prossecing_order = Order::where(['order_status' => '3'])->count();
        $partial_paid_order = Order::where(['order_status' => '22'])->count();
        $refund_order = Order::where(['order_status' => '17'])->count();
        $shipped_courier = Order::where(['order_status' => '5'])->count();
        $return_order = Order::where(['order_status' => '8'])->count();
        $Delivered_order = Order::where(['order_status' => '9'])->count();
        $rts = Order::where(['order_status' => '27'])->count();
        $refund_initiated = Order::where(['order_status' => '23'])->count();
        $total_cancel_order = Order::where(['order_status' => '26'])->count();
        $cancel_rate = ($total_cancel_order / $total_order) * 100;
        $return_rate = ($return_order / $total_order) * 100;

        // return $return_rate;
        // inventory
        $categories = Category::where('status', 1)->with('homeproductcount')->get();

        // accounts
        // deposit
        $courier_payment = Deposit::where(['status' => 1, 'deposit_type' => 1])->sum('amount');
        $officesale_payment = Deposit::where(['status' => 1, 'deposit_type' => 2])->sum('amount');
        $expense_others = Deposit::where(['status' => 1, 'deposit_type' => 3])->sum('amount');
        $total_payment = Deposit::where(['status' => 1])->sum('amount');
        // expense
        $boost_cost = Expense::where(['status' => 1, 'expense_type' => 1])->sum('amount');
        $office_cost = Expense::where(['status' => 1, 'expense_type' => 2])->sum('amount');
        $bank_deposit = Expense::where(['status' => 1, 'expense_type' => 3])->sum('amount');
        $packaging_cost = Expense::where(['status' => 1, 'expense_type' => 4])->sum('amount');
        $transport_cost = Expense::where(['status' => 1, 'expense_type' => 5])->sum('amount');
        $others_expense_cost = Expense::where(['status' => 1, 'expense_type' => 6])->sum('amount');
        $total_cost = Expense::where(['status' => 1])->sum('amount');

        $account_balance = $total_payment - $total_cost;


        // account filtering
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if (!$startDate || !$endDate) {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        }

        // accounts
        // deposit
        $courier_payment = Deposit::where(['status' => 1, 'deposit_type' => 1])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $officesale_payment = Deposit::where(['status' => 1, 'deposit_type' => 2])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expense_others = Deposit::where(['status' => 1, 'deposit_type' => 3])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $total_payment = Deposit::where(['status' => 1])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        // expense
        $boost_cost = Expense::where(['status' => 1, 'expense_type' => 1])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $office_cost = Expense::where(['status' => 1, 'expense_type' => 2])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $bank_deposit = Expense::where(['status' => 1, 'expense_type' => 3])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $packaging_cost = Expense::where(['status' => 1, 'expense_type' => 4])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $transport_cost = Expense::where(['status' => 1, 'expense_type' => 5])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $others_expense_cost = Expense::where(['status' => 1, 'expense_type' => 6])->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $total_cost = Expense::where(['status' => 1])->whereBetween('date', [$startDate, $endDate])->sum('amount');

        // orders filtering
        
        $startDate = Carbon::parse($request->orderstart_date)->startOfDay();
        $endDate   = Carbon::parse($request->orderend_date)->endOfDay();

        if (!$startDate || !$endDate) {
            $startDate = now()->startOfMonth()->startOfDay();
            $endDate = now()->endOfMonth()->endOfDay();
        }

        if($request->orderstart_date && $request->orderend_date){

            // order
            $total_order = Order::whereBetween('created_at', [$startDate, $endDate])->count();
            $pending_order = Order::where(['order_status' => '1'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $confirm_order = Order::where(['order_status' => '2'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $prossecing_order = Order::where(['order_status' => '3'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $partial_paid_order = Order::where(['order_status' => '22'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $refund_order = Order::where(['order_status' => '17'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $shipped_courier = Order::where(['order_status' => '5'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $return_order = Order::where(['order_status' => '8'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $Delivered_order = Order::where(['order_status' => '9'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $rts = Order::where(['order_status' => '27'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $refund_initiated = Order::where(['order_status' => '23'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $total_cancel_order = Order::where(['order_status' => '26'])->whereBetween('created_at', [$startDate, $endDate])->count();
            $cancel_rate = ($total_cancel_order / $total_order) * 100;
            $return_rate = ($return_order / $total_order) * 100;
        }

        // Customer filtering
        
        $customerstartDate = Carbon::parse($request->customer_start_date)->startOfDay();
        $customerendDate   = Carbon::parse($request->customer_end_date)->endOfDay();

        if (!$customerstartDate || !$customerendDate) {
            $customerstartDate = now()->startOfMonth()->startOfDay();
            $customerendDate = now()->endOfMonth()->endOfDay();
        }

        if($request->customer_start_date && $request->customer_end_date){
            $total_customer = Customer::whereBetween('created_at', [$customerstartDate, $customerendDate])->count();
            $total_visitor = Visitor::whereBetween('created_at', [$customerstartDate, $customerendDate])->count();

        }

        // sales filtering        
        $salesstartDate = Carbon::parse($request->sales_start_date)->startOfDay();
        $salesendDate   = Carbon::parse($request->sales_end_date)->endOfDay();

        if (!$salesstartDate || !$salesendDate) {
            $salesstartDate = now()->startOfMonth()->startOfDay();
            $salesendDate = now()->endOfMonth()->endOfDay();
        }

        if($request->sales_start_date && $request->sales_end_date){
            $total_sale = Order::where(['order_status' => '9'])->whereBetween('created_at', [$salesstartDate, $salesendDate])->count();

            $total_revenue = Order::where(['order_status' => '9'])->whereBetween('created_at', [$salesstartDate, $salesendDate])->get()->sum('amount');
            $aov = $total_sale > 0 ? $total_revenue / $total_sale : 0;

        }

        return view('backEnd.admin.dashboard', compact('total_order', 'today_order', 'total_product', 'total_customer', 'total_visitor', 'latest_order', 'latest_customer', 'today_delivery', 'total_delivery', 'last_week', 'last_month', 'monthly_sale', 'pending_order', 'prossecing_order', 'Delivered_order', 'partial_paid_order', 'refund_order', 'confirm_order', 'shipped_courier', 'return_order', 'cancel_rate', 'return_rate', 'rts', 'refund_initiated', 'total_cancel_order', 'categories', 'courier_payment', 'officesale_payment', 'expense_others', 'total_payment', 'boost_cost', 'office_cost', 'bank_deposit', 'packaging_cost', 'transport_cost', 'others_expense_cost', 'total_cost', 'account_balance', 'total_sku', 'available_sku', 'stock_out_sku', 'low_sku', 'total_inventory', 'total_sale','aov'));
    }

     public function salesChart(Request $request)
    {
        $type = $request->type ?? 'daily';

        // =========================
        // DAILY
        // =========================
        if ($type == 'daily') {

            $start = now()->subDays(6)->startOfDay();
            $end   = now()->endOfDay();

            // amount data
            $amountData = Order::where('order_status', 9)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw("DATE(created_at) as label, SUM(amount) as total_amount")
                ->groupBy(DB::raw("DATE(created_at)"))
                ->pluck('total_amount', 'label');

            // qty data
            $qtyData = DB::table('order_details')
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                ->where('orders.order_status', 9)
                ->whereBetween('orders.created_at', [$start, $end])
                ->selectRaw("DATE(orders.created_at) as label, SUM(order_details.qty) as total_qty")
                ->groupBy(DB::raw("DATE(orders.created_at)"))
                ->pluck('total_qty', 'label');

            $period = CarbonPeriod::create($start, $end);

            $data = [];

            foreach ($period as $date) {

                $key = $date->format('Y-m-d');

                $data[] = [
                    'label'        => $date->format('d M'),
                    'total_amount' => (float) ($amountData[$key] ?? 0),
                    'total_qty'    => (int) ($qtyData[$key] ?? 0),
                ];
            }

            return response()->json($data);
        }

        // =========================
        // WEEKLY
        // =========================
        elseif ($type == 'weekly') {

            $data = [];

            for ($i = 3; $i >= 0; $i--) {

                $start = now()->subWeeks($i)->startOfWeek();
                $end   = (clone $start)->endOfWeek();

                $amount = Order::where('order_status', 9)
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('amount');

                $qty = DB::table('order_details')
                    ->join('orders', 'orders.id', '=', 'order_details.order_id')
                    ->where('orders.order_status', 9)
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->sum('order_details.qty');

                $data[] = [
                    'label'        => $start->format('d M') . ' - ' . $end->format('d M'),
                    'total_amount' => (float) $amount,
                    'total_qty'    => (int) $qty,
                ];
            }

            return response()->json($data);
        }

        // =========================
        // MONTHLY
        // =========================
        elseif ($type == 'monthly') {

            $data = [];

            for ($i = 3; $i >= 0; $i--) {

                $start = now()->subMonths($i)->startOfMonth();
                $end   = (clone $start)->endOfMonth();

                $amount = Order::where('order_status', 9)
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('amount');

                $qty = DB::table('order_details')
                    ->join('orders', 'orders.id', '=', 'order_details.order_id')
                    ->where('orders.order_status', 9)
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->sum('order_details.qty');

                $data[] = [
                    'label'        => $start->format('M Y'),
                    'total_amount' => (float) $amount,
                    'total_qty'    => (int) $qty,
                ];
            }

            return response()->json($data);
        }

        // =========================
        // YEARLY
        // =========================
        else {

            $data = [];

            for ($i = 2; $i >= 0; $i--) {

                $start = now()->subYears($i)->startOfYear();
                $end   = (clone $start)->endOfYear();

                $amount = Order::where('order_status', 9)
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('amount');

                $qty = DB::table('order_details')
                    ->join('orders', 'orders.id', '=', 'order_details.order_id')
                    ->where('orders.order_status', 9)
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->sum('order_details.qty');

                $data[] = [
                    'label'        => $start->format('Y'),
                    'total_amount' => (float) $amount,
                    'total_qty'    => (int) $qty,
                ];
            }

            return response()->json($data);
        }
    }


    public function changepassword()
    {
        return view('backEnd.admin.changepassword');
    }
    public function newpassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required_with:new_password|same:new_password|'
        ]);

        $user = User::find(Auth::id());
        $hashPass = $user->password;

        if (Hash::check($request->old_password, $hashPass)) {

            $user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            Toastr::success('Success', 'Password changed successfully!');
            return redirect()->route('dashboard');
        } else {
            Toastr::error('Failed', 'Old password not match!');
            return back();
        }
    }
    public function locked()
    {
        // only if user is logged in

        Session::put('locked', true);
        return view('backEnd.auth.locked');


        return redirect()->route('login');
    }

    public function unlocked(Request $request)
    {
        if (!Auth::check())
            return redirect()->route('login');
        $password = $request->password;
        if (Hash::check($password, Auth::user()->password)) {
            Session::forget('locked');
            Toastr::success('Success', 'You are logged in successfully!');
            return redirect()->route('dashboard');
        }
        Toastr::error('Failed', 'Your password not match!');
        return back();
    }
}
