<?php

namespace App\Http\Controllers;

use App\Http\Resources\Transaction as TransactionResource;
use App\Invite;
use App\Transaction;
use App\User;
use DB;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store($request, $status)
    {
        if ($request->membership === 'basic') {
            $request->amount = 1000;
        } else {
            $request->amount = 10000;
        }
        $transaction = new Transaction;
        $transaction->user_id = $request->id;
        $transaction->status = $status;
        $transaction->type = 'online';
        $transaction->amount = $request->amount;
        $transaction->save();
        $invited_by = Invite::where('user_id', $request->id)->first()->invited_by;
        $admin = User::where('isAdmin', '1')->first();
        $admin_amount = 0;
        if ($status === 'success' && $request->membership === 'core') {
            app('App\Http\Controllers\ActivityController')->store($request, $transaction->user_id, "Transaction of Rs. " . $transaction->amount . " successful.", "payment");
            $users = User::whereNotIn('id', [$invited_by, $request->id])->where('membership', 'core')->whereNotIn('isAdmin', [2, 1])->get();
            if (count($users) > 0) {
                foreach ($users as $user) {
                    // All core users share
                    $transaction_earning = new Transaction;
                    $transaction_earning->user_id = $user->id;
                    $transaction_earning->status = 'success';
                    $transaction_earning->type = 'earning';
                    $transaction_earning->amount = ($request->amount * 25 / 100) / count($users);
                    $transaction_earning->save();
                    app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, $request->email . " joined as a " . $request->membership . " and you earned Rs. " . $transaction_earning->amount . ".", "earning");
                }
            } else {
                $admin_amount += $request->amount * 25 / 100;
            }
            $master_amount = 0;
            $user = User::where('id', $invited_by)->where('membership', 'core')->first();
            if ($user !== null && intval($user->id) === intval($admin->id)) {
                $admin_amount += $request->amount * 25 / 100;
            } elseif ($user !== null && intval($user->isAdmin) !== 2) {
                $transaction_earning = new Transaction;
                $transaction_earning->user_id = $invited_by;
                $transaction_earning->status = 'success';
                $transaction_earning->type = 'earning';
                $transaction_earning->amount = $request->amount * 25 / 100;
                $transaction_earning->save();
                app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, "Your referral " . $request->email . " joined as a " . $request->membership . " and you earned Rs. " . $transaction_earning->amount . ".", "earning");
            } elseif ($user !== null && intval($user->isAdmin) === 2) {
                $master_amount += $request->amount * 25 / 100;
            }
            $user = User::where('id', $invited_by)->first();
            while ($user->id !== $admin->id && intval($user->isAdmin) !== intval('2') && $user !== null) {
                $user = User::where('id', Invite::where('user_id', $user->id)->first()->invited_by)->first();
            }
            if ($user->id === $admin->id) {
                // Admin 50% share
                $transaction_earning = new Transaction;
                $transaction_earning->user_id = $admin->id;
                $transaction_earning->status = 'success';
                $transaction_earning->type = 'earning';
                $transaction_earning->amount = ($request->amount * 50 / 100) + $admin_amount;
                $transaction_earning->save();
                app('App\Http\Controllers\NotificationController')->store($request->email." user joined as a " . $request->membership . " member & your earned Rs. ". $transaction_earning->amount . '.', '1');
                app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, "Congrats! ". $request->email . " has Joined GPM Community as a " . $request->membership . " and You earn Rs. " . $transaction_earning->amount . ".", "earning");
            } elseif (intval($user->isAdmin) === intval('2')) {
                // Admin 40% share
                $transaction_earning = new Transaction;
                $transaction_earning->user_id = $admin->id;
                $transaction_earning->status = 'success';
                $transaction_earning->type = 'earning';
                $transaction_earning->amount = ($request->amount * 40 / 100) + $admin_amount;
                $transaction_earning->save();
                app('App\Http\Controllers\NotificationController')->store($request->email." user joined as a " . $request->membership . " member & your earned Rs. ". $transaction_earning->amount . '.', '1');
                app('App\Http\Controllers\ActivityController')->store($request, '1', $request->email." user joined as a " . $request->membership . " member & your earned Rs. ". $transaction_earning->amount . '.', "update");
                app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, $request->email . " joined GPM community and You earn Rs." . $transaction_earning->amount . ".", "earning");
                // master franchise 10% share
                $transaction_earning = new Transaction;
                $transaction_earning->user_id = $user->id;
                $transaction_earning->status = 'success';
                $transaction_earning->type = 'earning';
                $transaction_earning->amount = ($request->amount * 10 / 100) + $master_amount;
                $transaction_earning->save();
                app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, $request->email . " joined under your master franchise as core member and you earned Rs. " . $transaction_earning->amount . ".", "earning");
            }
        } elseif ($status === 'success' && $request->membership === 'basic') {
            $user = User::where('id', $invited_by)->first();
            while ($user->id !== $admin->id && intval($user->isAdmin) !== intval('2') && $user !== null) {
                $user = User::where('id', Invite::where('user_id', $user->id)->first()->invited_by)->first();
            }
            if (intval($user->isAdmin) === intval('2')) {
                $transaction_earning = new Transaction;
                $transaction_earning->user_id = $admin->id;
                $transaction_earning->status = 'success';
                $transaction_earning->type = 'earning';
                $transaction_earning->amount = ($request->amount * 90 / 100);
                $transaction_earning->save();
                app('App\Http\Controllers\NotificationController')->store($request->email." user joined as a " . $request->membership . " member & your earned Rs. ". $transaction_earning->amount . '.', 1);
                app('App\Http\Controllers\ActivityController')->store($request, '1', $request->email." user joined as a " . $request->membership . " member & your earned Rs. ". $transaction_earning->amount . '.', "update");
                app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, $request->email . " joined GPM community and You earn Rs." . $transaction_earning->amount . ".", "earning");
                // master franchise 10% share
                $transaction_earning = new Transaction;
                $transaction_earning->user_id = $user->id;
                $transaction_earning->status = 'success';
                $transaction_earning->type = 'earning';
                $transaction_earning->amount = ($request->amount * 10 / 100);
                $transaction_earning->save();
                app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, $request->email . " joined under your master franchise as basic member and you earned Rs. " . $transaction_earning->amount . ".", "earning");
            } else {
                $transaction_earning = new Transaction;
                $transaction_earning->user_id = $admin->id;
                $transaction_earning->status = 'success';
                $transaction_earning->type = 'earning';
                $transaction_earning->amount = 1000;
                $transaction_earning->save();
                app('App\Http\Controllers\ActivityController')->store($request, $transaction_earning->user_id, "Congrats! ". $request->email . " has Joined GPM Community as a " . $request->membership . " and You earn Rs. " . $transaction_earning->amount . ".", "earning");
                app('App\Http\Controllers\NotificationController')->store($request->email." user joined as a " . $request->membership . " member & your earned Rs. ". $transaction_earning->amount . '.', 1);
            }
        }
        return new TransactionResource($transaction);
    }

    public function getEarning(Request $request)
    {
        $result = array();
        $result['earning'] = number_format(Transaction::where(
            [
                'user_id' => $request->user_id,
                'type' => 'earning',
            ]
        )->sum('amount'), 2);
        $result['invites'] = Invite::where('invited_by', $request->user_id)->count();
        return json_encode($result);
    }

    public function getCurrentMonthEaring()
    {
        $result = array();
        $records = DB::table('transactions')
            ->select(DB::raw("date(created_at) as day, sum(amount) as amount"))
            ->whereRaw('month(created_at) = '. date('m', strtotime('0 month')))
            ->groupBy('day')
            ->get();

        $data = array();
        $lable = array();
        foreach ($records as $record) {
            array_push($data, $record->amount);
            array_push($lable, date('d', strtotime($record->day)));
        }
        $result['data'] = $data;
        $result['lable'] = $lable;
        return json_encode($result);
    }

    public function transactions(Request $request)
    {
        $transactions = DB::table('payu_payments')->get();
        return view('admin.transactions.index')->with([
             'transactions' => $transactions,
          ]);
    }
}
// 25 refferal
// 25 equaly distribute
// 10 master franchices
// 40 gpm
/*------
bacis ->cann't reffer
core refer -> basic plan -> nothing
core refer -> core plan -> 25% and all core 25% and 40% admin and 10% master
master refer -> core plan -> 10% and 25% referral by and 25% core and 40% admin
master refer -> basic plan -> 10% and 90% admin
------*/
