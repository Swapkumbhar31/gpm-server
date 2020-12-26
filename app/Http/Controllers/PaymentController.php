<?php

namespace App\Http\Controllers;
use Validator;
use App\User;
use App\Module;
use App\Chapter;
use App\Transaction;
use DB;
use Illuminate\Http\Request;
use Tzsk\Payu\Facade\Payment;
use App\Mail\RegistrationMail;
use App\Mail\RefundMail;
use Uuid;
use Mail;

class PaymentController extends Controller
{
    //
    public function payment_basic(Request $request, $referral_code)
    {
        $user = DB::table('users')->where('referral_code', $referral_code)->first();
        $attributes = [
            'txnid' => strtoupper(str_random(15)), # Transaction ID.
            'amount' => 1000, # Amount to be charged.
            'productinfo' => "Basic membership",
            'firstname' => $user->name,
            'lastname' => $user->name,
            'email' => $user->email,
            'phone' => $user->contact,
            'udf1' => $user->id,
        ];
        return Payment::make($attributes, function ($then) {
            #$then->redirectTo('https://fliteracy.gainpassivemoney.com/payment/status');
            # OR...
            # $then->redirectRoute('payment.status');
            # OR...
            $then->redirectAction('PaymentController@status');
        });
    }

    public function upgrade(Request $request)
    {
        $amount = 10000;
        $user = User::where('email', $request->email)->first();
        $user->membership = 'core';
        $user->save();
        $users = User::whereNotIn('id', [ $user->id])->where('membership', 'core')->whereNotIn('isAdmin', [2, 1])->get();
        if (count($users) > 0) {
            foreach ($users as $u) {
                // All core users share
                $transaction_earning = new Transaction;
                $transaction_earning->user_id = $u->id;
                $transaction_earning->status = 'success';
                $transaction_earning->type = 'earning';
                $transaction_earning->amount = ($amount * 25 / 100) / count($users);
                $transaction_earning->save();
                app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, $user->name . " upgraded as a core member and you earned Rs. " . $transaction_earning->amount . ".", "earning");
            }
        }
        $admin_amount = $amount * 75 / 100;
        $transaction_earning = new Transaction;
        $transaction_earning->user_id = User::where('isAdmin', '1')->first()->id;
        $transaction_earning->status = 'success';
        $transaction_earning->type = 'earning';
        $transaction_earning->amount = $admin_amount;
        $transaction_earning->save();
        app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, $user->name . " upgraded as a core member and You earn Rs. " . $transaction_earning->amount . ".", "earning");
        return view('user.upgraded');
    }

    public function payment_core(Request $request, $referral_code)
    {
        $user = DB::table('users')->where('referral_code', $referral_code)->first();
        $attributes = [
            'txnid' => strtoupper(str_random(15)), # Transaction ID.
            'amount' => 10000, # Amount to be charged.
            'productinfo' => "Core membership",
            'firstname' => $user->name,
            'lastname' => $user->name,
            'email' => $user->email,
            'phone' => $user->contact,
            'udf1' => $user->id,
        ];

        return Payment::make($attributes, function ($then) {
            # $then->redirectTo('payment/status');
            # OR...
            # $then->redirectRoute('payment.status');
            # OR...
            $then->redirectAction('PaymentController@status');
        });
    }

    public function status()
    {
        $payment = Payment::capture();
        if ($payment->status === 'Failed') {
            DB::table('payu_payments')->insert([
                'account' => $payment->account,
                'payable_id' => $payment->payable_id,
                'payable_type' => $payment->get('payable_type'),
                'txnid' => $payment->txnid,
                'mihpayid' => $payment->mihpayid,
                'userid' => $payment->get('udf1'),
                'firstname' => $payment->get('firstname'),
                'lastname' => $payment->get('lastname'),
                'phone' => $payment->get('phone'),
                'email' => $payment->email,
                'amount' => $payment->amount,
                'data' => $payment->data,
                'status' => $payment->status,
                'unmappedstatus' => $payment->unmappedstatus,
                'mode' => $payment->mode,
                'bank_ref_num' => $payment->bank_ref_num,
                'cardnum' => $payment->cardnum,
                'name_on_card' => $payment->name_on_card,
                'created_at' => Now(),
                'updated_at' => Now(),
            ]);
            return redirect('https://gainpassivemoney.com/payment/failed');
        } else {
            dd($payment->status);
        }
        // print_r(json_encode($payment->get('zipcode')));
    }

    public function redirect(Request $request)
    {
        return response("UNATHORISED", 401);
    }

    public function register(Request $request)
    {
        DB::table('payu_payments')->insert([
            'account' => "payubiz",
            'payable_id' => $request->payable_id,
            'payable_type' => $request->get('payable_type'),
            'txnid' => $request->txnid,
            'mihpayid' => $request->mihpayid,
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'phone' => $request->get('phone'),
            'email' => $request->email,
            'amount' => $request->amount,
            'discount' => $request->discount,
            'net_amount_debit' => $request->net_amount_debit,
            'data' => $request->data,
            'status' => $request->status,
            'unmappedstatus' => $request->unmappedstatus,
            'mode' => $request->mode,
            'bank_ref_num' => $request->bank_ref_num,
            // 'bankcode' => $request->bank_code,
            'cardnum' => $request->card_number,
            'name_on_card' => $request->name_on_card,
            'created_at' => Now(),
            'updated_at' => Now(),
        ]);
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:255',
        ]);
        if ($validator->fails()) {
            Mail::to("payment@yoovaventures.com")->send(new RefundMail($request->email));
            return view('user.user-alredy-exist');
        }
        if (intval($request->amount) === 1180 || intval($request->amount) === 1) {
            $p = str_random(10);
            $user = new User;
            $user->email = $request->email;
            $user->password = bcrypt($p);
            $module_id = Module::where('mod_index', Module::min('mod_index'))->first() ? Module::where('mod_index', Module::min('mod_index'))->first()->id : -1;
            $user->chapter_id = Chapter::where('module_id', $module_id)->where('chap_index', Chapter::where('module_id', $module_id)->min('chap_index'))->first() ? Chapter::where('module_id', $module_id)->where('chap_index', Chapter::where('module_id', $module_id)->min('chap_index'))->first()->id : -1;
            $user->referral_code = str_random(10);
            $user->api_key = Uuid::generate()->string;
            $user->name = $request->firstname;
            $user->membership = 'basic';
            $user->save();
            $user->mailpassword = $p;
            Mail::to($request->email)->send(new RegistrationMail($user));
            return redirect('https://gainpassivemoney.com/register/information');
      } elseif (intval($request->amount) === 11800 || intval($request->amount) === 2) {
            $user = new User;
            $p = str_random(10);
            $user->email = $request->email;
            $user->password = bcrypt($p);
            $module_id = Module::where('mod_index', Module::min('mod_index'))->first() ? Module::where('mod_index', Module::min('mod_index'))->first()->id : -1;
            $user->chapter_id = Chapter::where('module_id', $module_id)->where('chap_index', Chapter::where('module_id', $module_id)->min('chap_index'))->first() ? Chapter::where('module_id', $module_id)->where('chap_index', Chapter::where('module_id', $module_id)->min('chap_index'))->first()->id : -1;
            $user->referral_code = str_random(10);
            $user->api_key = Uuid::generate()->string;
            $user->name = $request->firstname;
            $user->membership = 'core';
            $user->save();
            $user->mailpassword = $p;
            Mail::to($request->email)->send(new RegistrationMail($user));
            return redirect('https://gainpassivemoney.com/register/information');
        } else {

        }
    }

    public function userList(Request $request)
    {
        $users = User::join('transactions', 'users.id', '=', 'transactions.user_id')->where('isAdmin', '0')->where('type','earning')->select('email', 'contact', 'users.id', 'name', DB::raw('sum(`amount`) as total'))->groupBy('email')->get();
        return view('admin.refund.index')->with([
            'users' => $users,
        ]);
    }

    public function payForReffreal(Request $request, $id)
    {
        Transaction::where('user_id', $id)->where('type','earning')->delete();
        return redirect()->route('pay');
    }
}
