<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Http\Resources\User as UserResource;
use App\Invite;
use App\Mail\ContactUs;
use App\Mail\ForgotPassword;
use App\Mail\NewMasterMail;
use App\Mail\OTPMail;
use App\Mail\UserPasswordReset;
use App\Mail\UserPasswordUpdate;
use App\Module;
use App\User;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Uuid;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(15);
        $users->load('bankdetails');
        return UserResource::collection($users);
    }
    public function syllabus(Request $request)
    {
        if ($request->wantsJson()) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = Auth::user();
        }
        $currentChapter = $user->chapter_id;
        $isCompleted = true;
        $modules = DB::table('modules')->orderBy('mod_index', 'asc')->get();
        foreach ($modules as $m) {
            $chapters = DB::table('chapters')->where('module_id', $m->id)->orderBy('chap_index')->get();
            foreach ($chapters as $chapter) {
                $chapter->description = "";
                $chapter->image = DB::table('media')->where('filename', $chapter->pic)->first();
                if ($currentChapter === $chapter->id) {
                    $chapter->isCompleted = $isCompleted;
                    $isCompleted = false;
                } else {
                    $chapter->isCompleted = $isCompleted;
                }
            }
            $m->chapters = $chapters;
        }
        if ($request->wantsJson()) {
            return json_encode($modules);
        }
        return view('user.syllabus')->with('modules', $modules);
    }
    public function changepass()
    {
        return view('user.changepassword');
    }
    public function profile(Request $request, $id = null)
    {
        if ($id === null) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = User::findOrFail($request->id);
        }
        if ($request->wantsJson()) {
            return new UserResource($user);
        } else {
            return view('admin.member.details')->with('user', $user);
        }
    }

    public function showMasteAdd()
    {
        return view('admin.member.masteradd');
    }

    public function saveProfiledPic(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        if ($user->profile_img_url !== null) {
            if (file_exists(storage_path('app/profiles/' . $user->profile_img_url))) {
                unlink(storage_path('app/profiles/' . $user->profile_img_url));
            }
        }
        $image = $request->file('uploadFile');
        if ($image != null) {
            $pic_id = str_replace("-", "", Uuid::generate()->string) . ".jpg";
            $destinationPath = base_path('storage/app/profiles');
            $image->move($destinationPath, $pic_id);
            $user->profile_img_url = $pic_id;
            $user->save();
            app('App\Http\Controllers\ActivityController')->store($request, $user->id, "Profile picture changed.", "update");
        } else {
            $pic_id = $image;
        }
        return new UserResource($user);
    }

    public function savePancardPic(Request $request, $previousFile = null)
    {
        if ($previousFile !== null) {
            if (file_exists(storage_path('app/pancards/' . $previousFile))) {
                unlink(storage_path('app/pancards/' . $previousFile));
            }
        }
        $image = $request->file('uploadFile');
        if ($image != null) {
            $pic_id = str_replace("-", "", Uuid::generate()->string) . ".jpg";
            $destinationPath = base_path('storage/app/pancards');
            $image->move($destinationPath, $pic_id);
        } else {
            $pic_id = $image;
        }
        return $pic_id;
    }

    public function verifyMail(Request $request, $hashVerifyCode, $user_id)
    {
        $result = array();
        $user = User::findOrFail(urldecode($user_id));
        if ($user->isVerified === '1') {
            $result['status'] = 'verified';
            return view('user/mail')->with(['result' => $result]);
        }
        if ($user->verification_code === urldecode($hashVerifyCode)) {
            // The passwords match...
            $result['status'] = 'matched';
            $user->isVerified = '1';
            $user->save();
        } else {
            $result['status'] = 'unsuccessful';
        }
        return view('user/mail')->with(['result' => $result]);
    }

    public function register(Request $request)
    {
        dd($request);
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:255',
            'pancard' => 'required|unique:users|max:255',
            'firstname' => 'required',
            'lastname' => 'required',
            'dob' => 'required',
            'contact' => 'required',
            'password' => 'required',
            'membership' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $user = new User;
        $module_id = Module::where('mod_index', Module::min('mod_index'))->first() ? Module::where('mod_index', Module::min('mod_index'))->first()->id : -1;
        $user->email = $request->email;
        $user->name = $request->firstname . ' ' . $request->lastname;
        $user->contact = '' . $request->contact;
        $user->dob = $request->dob;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->pancard = $request->pancard;
        $user->pancard_img_url = $request->panfile;
        $user->password = bcrypt($request->password);
        $user->membership = $request->membership;
        $user->chapter_id = Chapter::where('module_id', $module_id)->where('chap_index', Chapter::where('module_id', $module_id)->min('chap_index'))->first() ? Chapter::where('module_id', $module_id)->where('chap_index', Chapter::where('module_id', $module_id)->min('chap_index'))->first()->id : -1;
        $user->referral_code = str_random(10);
        $user->api_key = Uuid::generate()->string;
        $user->verification_code = Uuid::generate()->string;
        // return $user;
        $user->save();
        try {
            if (!isset($request->referral_code) && $request->referral_code === null) {
                $request->referral_code = User::where('isAdmin', '1')->first()->referral_code;
            }
            $invite = new Invite;
            $invite->user_id = $user->id;
            $invite->invited_by = User::where('referral_code', $request->referral_code)->first()->id;
            $invite->save();
            if ($request->bankname !== null) {
                app('App\Http\Controllers\BankDetailsController')->store($request, $user);
            }
            app('App\Http\Controllers\ActivityController')->store($request, $user->id, "You joined GPM community.", "referral");
            app('App\Http\Controllers\ActivityController')->store($request, $invite->invited_by, "Your referral " . $user->name . " joined GPM community as a " . $request->membership . " member.", "referral");
            $request->link = urlencode($user->verification_code);
            $request->user_id = urlencode($user->id);
            Mail::to($request->email)->send(new OTPMail($request));
            app('App\Http\Controllers\TransactionController')->store($request, 'success');
        } catch (Exception $e) {
            $user->delete();
            echo 'Message: ' . $e->getMessage();
        }
        if ($request->wantsJson()) {
            return new UserResource($user);
        }
    }
    public function contactUs(Request $request)
    {
        Mail::to('info@yoovaventures.com')->send(new ContactUs($request));
        Mail::to($request->email)->send(new ContactUs());
        $result['msg'] = 'mail sended';
        $result['status'] = '200';
        return response(json_encode($result));
    }
    public function updatePassword(Request $request, $user_id = null)
    {
        $result = array();
        if ($user_id !== null) {
            $user = User::findOrFail($user_id);
        } else {
            $user = User::findOrFail($request->user_id);
        }
        if (Hash::check($request->get('old'), $user->password, [])) {
            //correct user and password
            if ($request->get('new') === $request->get('confirm')) {
                $user->password = bcrypt($request->get('new'));
                $user->save();
                $result['status'] = 200;
                $result['msg'] = "Password updated.";
                // Mail::to('swapkumbhar31@gmail.com')->send(new UserPasswordUpdate());
                Mail::to($user->email)->send(new UserPasswordUpdate());
                app('App\Http\Controllers\ActivityController')->store($request, $user->id, "password updated successfully", "update");
            } else {
                $result['status'] = 0;
                $result['msg'] = "New Password Not match with confirm password.";
            }
        } else {
            $result['status'] = 0;
            $result['msg'] = "Incorrect current password.";
        }
        return json_encode($result);
    }
    public function login(Request $request)
    {
        $result = array();
        $user = User::Where('email', $request->get('email'))->first();
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $result['status'] = "200";
            $result['api_key'] = Auth::user()->api_key;
            $result['isVerified'] = Auth::user()->isVerified;
            $result['name'] = Auth::user()->name;
            $result['email'] = Auth::user()->email;
            $result['membership'] = Auth::user()->membership;
            $result['profile_image'] = Auth::user()->profile_img_url;
            $result['isModuleCompleted'] = Auth::user()->isModuleCompleted;
        } else {
            $result['status'] = "0";
            $result['data'] = "incorrect";
            $result['email'] = $request->get('email');
            $result['password'] = $request->get('password');
        }
        if ($request->wantsJson()) {
            echo json_encode($result);
        } else {
            if (intval($result['status']) === 0) {
                return redirect('/login')->with(
                    'msg' , 'Username or Password is wrong.'
                );
            } else {
                return redirect('/');
            }
        }
    }

    public function updateContactInfo(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->isVerified = 0;
            Mail::to($request->email)->send(new OTPMail($request));
        }
        $user->name = $request->firstname . " " . $request->lastname;
        $user->address = $request->address;
        $user->dob = $request->dob;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->contact = $request->contact;
        $user->save();
        app('App\Http\Controllers\ActivityController')->store($request, $user->id, "Profile information updated successfully.", "update");
        return new UserResource($user);
    }

    public function board()
    {
        $result = array();
        $result['total_users'] = User::all()->count();
        $result['total_master'] = User::where('isAdmin', '2')->count();
        $result['new_users'] = User::whereRaw('DATE(created_at) = CURDATE()')->count();
        $result['earing'] = number_format(DB::table('transactions')->where(
            [
                'user_id' => User::where('isAdmin', '1')->first()->id,
                'type' => 'earning',
            ])->sum('amount'), 2);
        return json_encode($result);
    }
    public function approvalList()
    {
        $users = User::where('adminApproval', '0')->orderBy('created_at', 'desc')->get();
        return UserResource::collection($users);
    }

    public function approve(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->adminApproval = 1;
        $user->save();
        $users = User::where('adminApproval', '0')->orderBy('created_at', 'desc')->get();
        if ($request->wantsJson()) {
            return UserResource::collection($users);
        } else {
            return redirect()->back();
        }
    }
    public function masterAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:255',
            'pancard' => 'required|unique:users|max:255',
            'firstname' => 'required',
            'lastname' => 'required',
            'contact' => 'required',
            'dob' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $image = $request->file('panfile');
        if ($image != null) {
            $pic_id = str_replace("-", "", Uuid::generate()->string) . ".jpg";
            $destinationPath = base_path('storage/app/pancards');
            $image->move($destinationPath, $pic_id);
        } else {
            $pic_id = $image;
        }
        $password = str_random(10);
        $user = new User;
        $module_id = Module::where('mod_index', Module::min('mod_index'))->first() ? Module::where('mod_index', Module::min('mod_index'))->first()->id : -1;
        $user->chapter_id = Chapter::where('module_id', $module_id)->where('chap_index', Chapter::where('module_id', $module_id)->min('chap_index'))->first() ? Chapter::where('module_id', $module_id)->where('chap_index', Chapter::where('module_id', $module_id)->min('chap_index'))->first()->id : -1;
        $user->email = $request->email;
        $user->name = $request->firstname . ' ' . $request->lastname;
        $user->contact = '' . $request->contact;
        $user->dob = $request->dob;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->pancard = $request->pancard;
        $user->pancard_img_url = $pic_id;
        $user->password = bcrypt($password);
        $user->membership = "core";
        $user->isAdmin = 2;
        $user->isVerified = 1;
        $user->adminApproval = 1;
        $user->api_key = Uuid::generate()->string;
        $user->referral_code = str_random(10);
        $user->save();
        Mail::to($request->email)->send(new NewMasterMail($user, $password));
        app('App\Http\Controllers\ActivityController')->store($request, Auth::user()->id, $user->name . " added as master franchise", "referral");
        return redirect(route('students'));
    }

    public function forgotPasswordMail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $result = array();
        if ($user === null) {
            $result['status'] = '0';
            $result['msg'] = 'User not found';
        } else {
            $token = rand(100000, 999999);
            DB::table('password_resets')->where('email', $request->email)->delete();
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
            ]);
            Mail::to($request->email)->send(new ForgotPassword($request, $token));
            $result['status'] = '200';
            $result['msg'] = 'Email is sended';
        }
        return json_encode($result);
    }

    public function verifyOTPForgotPassword(Request $request)
    {
        $password_reset = DB::table('password_resets')->where('email', $request->email)->first();
        $result = array();
        if ($password_reset === null) {
            $result['status'] = '0';
            $result['msg'] = 'Change password request not found.';
        } else if (intval($password_reset->token) === intval($request->otp)) {
            $result['status'] = '200';
            $result['msg'] = 'opt match';
            $password_reset->isVerified = 1;
            DB::table('password_resets')->where('email', $request->email)
                ->update(['isVerified' => 1]);
        } else {
            $result['status'] = '100';
            $result['msg'] = 'opt not match';
        }
        return json_encode($result);
    }

    public function setNewPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $password_reset = DB::table('password_resets')->where('email', $request->email)->first();
        $result = array();
        if ($request->password !== $request->cpassword) {
            $result['status'] = '0';
            $result['msg'] = 'Password not match';
        } else if ($password_reset->isVerified === 1) {
            $user->password = bcrypt($request->password);
            $user->save();
            $result['status'] = '200';
            $result['msg'] = 'Password set';
            Mail::to($user->email)->send(new UserPasswordReset());
        } else {
            $result['status'] = '100';
            $result['msg'] = 'OTP not confirmed';
        }
        return json_encode($result);
    }

    public function getAgeWiseReport()
    {
        $records = DB::select('SELECT id, (DATEDIFF(CURRENT_DATE, STR_TO_DATE(users.dob, "%Y-%m-%d"))/365) as age FROM users');
        $label = ['0-17', '18-24', '25-34' , '35-59', '60+'];
        $count_60 = 0;
        $count_35 = 0;
        $count_25 = 0;
        $count_18 = 0;
        $count_0 = 0;

        foreach ($records as $record) {
            if ($record->age > 60) {
                $count_60++;
            } else if($record->age > 35) {
                $count_35++;
            } else if($record->age > 25) {
                $count_25++;
            } else if($record->age > 18) {
                $count_18++;
            } else {

            }
        }
        $data = [$count_0, $count_18, $count_25, $count_35, $count_60];
        $result['data'] = $data;
        $result['lable'] = $label;
        return json_encode($result);
    }

    public function addReffreal(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        if (Invite::where('user_id', $user->id)->first() !== null) {
            return response("Already reffred");
        }
        if (!isset($request->referral_code) && $request->referral_code === null) {
            $request->referral_code = User::where('isAdmin', '1')->first()->referral_code;
        }
        $invite = new Invite;
        $invite->user_id = $user->id;
        $invite->invited_by = User::where('referral_code', $request->referral_code)->first() !== null ? User::where('referral_code', $request->referral_code)->first()->id : null;
        if($invite->invited_by === null) {
            $request->referral_code = User::where('isAdmin', '1')->first()->referral_code;
            $invite->invited_by = User::where('referral_code', $request->referral_code)->first()->id;
        }
        $invite->save();
        app('App\Http\Controllers\ActivityController')->store($request, $user->id, "You joined GPM community.", "referral");
        if (intval($invite->invited_by) === 1) {
             app('App\Http\Controllers\ActivityController')->store($request, $invite->invited_by, "Your referral " . $user->email . " joined GPM community as a " . $user->membership . " member.", "referral");
        }
        return app('App\Http\Controllers\TransactionController')->store($user, 'success');
    }

    public function information(Request $request)
    {
        $user = User::find($request->user_id);
        if($user === null) {
            return response('User not found');
        }
        $validator = Validator::make($request->all(), [
            'pancard' => 'required|unique:users|max:255',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $user->name = $request->firstname . ' ' . $request->lastname;
        $user->contact = $request->countryCode . ' ' . $request->contact;
        $user->dob = $request->dob;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->pancard = $request->pancard;
        $user->pancard_img_url = $request->panfile;
        $user->password = bcrypt($request->password);
        $user->isVerified = 1;
        $user->save();
        if ($request->bankname !== null) {
            app('App\Http\Controllers\BankDetailsController')->store($request, $user);
        }
        if ($request->wantsJson()) {
            return new UserResource($user);
        }
    }
    public function getMembershipType(Request $request)
    {
        $user = User::find($request->user_id);
        $result['type'] = $user->membership;
        $result['email'] = $user->email;
        if (intval($user->pancard) === -1) {
            $result['isVerified'] = 0;
        } else {
            $result['isVerified'] = 1;
        }
        return response(json_encode($result));
    }
}
