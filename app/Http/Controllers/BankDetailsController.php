<?php

namespace App\Http\Controllers;

use App\BankDetails;
use Illuminate\Http\Request;
use App\Http\Resources\Chapter as ChapterResource;

class BankDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request,$user)
    {
        $bankDetails = new BankDetails;
        $bankDetails->user_id = $user->id;
        $bankDetails->bank_name = $request->bankname;
        $bankDetails->account_number = $request->accountnumber;
        $bankDetails->ifsc = $request->ifsc;
        $bankDetails->name = $request->nameonpassbook;
        $bankDetails->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankDetails  $bankDetails
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $bankDetails = BankDetails::where('user_id', $request->user_id)->first();
        if($request->wantsJson()){
            return json_encode($bankDetails);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BankDetails  $bankDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(BankDetails $bankDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankDetails  $bankDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $bankDetails = BankDetails::where('user_id', $request->user_id)->first();
        if(!$bankDetails){
            $bankDetails = new BankDetails;
            $bankDetails->user_id = $request->user_id;
        }
        $bankDetails->bank_name = $request->bank_name;
        $bankDetails->account_number = $request->account_number;
        $bankDetails->ifsc = $request->ifsc;
        $bankDetails->name = $request->name;
        $bankDetails->save();
        app('App\Http\Controllers\ActivityController')->store($request, $request->user_id, "Bank details updated successfully.", "update");
        if($request->wantsJson()){
            return new ChapterResource($bankDetails);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankDetails  $bankDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankDetails $bankDetails)
    {
        //
    }
}
