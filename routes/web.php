<?php

/*
/ Narasimha
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/home',function(){
    return redirect('/');
});
Route::get('/',  'UserController@syllabus')->middleware('auth')->name('dashboard');
Route::get('/chapter/mindset-arena',  'ChapterController@mindset')->middleware('auth')->name('mindset');
Route::get('/chapter/spending-pattern',  'ChapterController@spending_pattern')->middleware('auth')->name('spending_pattern');
Route::get('/chapter/passive-income',  'ChapterController@passive_income')->middleware('auth')->name('passive_income');
Route::get('/chapter/networking',  'ChapterController@networking')->middleware('auth')->name('networking');

Route::group(['middleware' => 'notifications'], function()
{
      Route::get('/temp', function(Request $request) {
            return json_encode($request);
      });
});

Route::group(['middleware'=>'admin', 'prefix' => 'admin'], function() {
    Route::get('/',function()
    {
        return redirect('/admin/home');
    });
    Route::get('payment', 'PaymentController@userList')->name('pay')->middleware('auth');
    Route::get('transactions', 'TransactionController@transactions')->name('transactions')->middleware('auth');
    Route::get('/home','AdminController@home')->name('home');
    Route::group(['prefix' => 'media'], function() {
        Route::get('/','MediaController@index')->name('media');
        Route::get('/add','MediaController@create')->name('addmedia');
        Route::post('/upload','MediaController@store')->name('uploadmedia');
        Route::post('/delete/{id}','MediaController@destroy')->name('deletemedia');
    });
    Route::group(['prefix' => 'calender'], function() {
        Route::get('/','EventController@calender')->name('calender');
    });
    Route::group(['prefix' => 'question'], function() {
        Route::get('/add','QuestionController@create')->name('addquestion');
        Route::post('/add','QuestionController@store');
        Route::get('/edit/{id}','QuestionController@edit')->name('editquestion');
        Route::post('/edit/{id}','QuestionController@update');
        Route::post('/delete/{id}','QuestionController@destroy')->name('deletequestion');
    });
    Route::group(['prefix' => 'event'], function() {
        Route::get('/','EventController@index')->name('event');
        Route::get('/add','EventController@create')->name('addevnet');
        Route::post('/add','EventController@store');
        Route::get('/edit/{id}','EventController@edit')->name('editevent');
        Route::post('/edit','EventController@update');
        Route::post('/delete/{id}','EventController@destroy')->name('deleteevent');
        Route::get('/{id}','EventController@view')->name('eventview');
    });
    Route::group(['prefix' => 'chapter'], function() {
        Route::get('/add','ChapterController@create')->name('addchapter');
        Route::post('/add','ChapterController@store')->name('savechapter');
        Route::get('/edit/{id}','ChapterController@edit')->name('savechapter');
        Route::post('/edit/{id}','ChapterController@update')->name('updatechapter');
        Route::get('/view/{id}','ChapterController@show')->name('viewchapter');
        Route::post('/delete/{id}','ChapterController@destroy')->name('deletechapter');
        Route::get('/{id}','ChapterController@index')->name('chapter');
    });
    Route::group(['prefix' => 'module'], function() {
        Route::get('/','ModuleController@index')->name('modules');
        Route::get('/add','ModuleController@create')->name('addmodule');
        Route::post('/add','ModuleController@store')->name('savemodule');
        Route::get('/edit/{id}','ModuleController@edit')->name('editmodule');
        Route::put('/edit/{id}','ModuleController@store')->name('updatemodule');
        Route::post('/delete/{id}','ModuleController@destroy')->name('deletemodule');
    });
    Route::get('/{id}/chapters','AdminController@chapters')->name('chapters');
    Route::get('/question/{chapter_id}','AdminController@questions')->name('questions');
    Route::group(['prefix' => 'members'], function() {
        Route::get('/','AdminController@students')->name('students');
        Route::get('/add/master', 'UserController@showMasteAdd')->name('master.add');
        Route::post('/add/master', 'UserController@masterAdd');
        Route::get('/details/{id}','UserController@profile')->name('member.details');
    });
    Route::get('/changepassword','AdminController@changepassword')->name('changepassword');
    Route::get('/send/notifications','NotificationController@send')->name('sendNotification');
    Route::post('/notification/add','NotificationController@addNotifications');
    Route::get('/stream', 'AdminController@stream')->name('admin.streamlive');
    Route::post('/admin/upload/video/{id}', 'ChapterController@UploadFile');
});


Auth::routes();
Route::get('/chapter/progress/{user_id}','ChapterController@chapterProgress');
Route::get('chapter/next/{id}', 'ChapterController@nextChapter');
Route::post('/login','UserController@login')->name('custom.login');
Route::get('/view/{id?}', 'ChapterController@viewVideo')->middleware('auth');
Route::get('/view/video/{id?}', 'ChapterController@view')->middleware('auth');
Route::get('/syllabus', 'HomeController@index')->middleware('auth')->name('syllabus');
Route::get('/change-password', 'UserController@changepass')->name('userchangepass');
Route::get('livestream', 'StreamController@livestream')->name('user.stream')->middleware('auth');
Route::get('/current-chapter', 'HomeController@index')->name('current-chapter')->middleware('auth');
Route::get('/calender','EventController@usercalender')->name('usercalender')->middleware('auth');
Route::get('/notifications','NotificationController@index')->name('notifications')->middleware('auth');
Route::post('/notifications/delete/{id}','NotificationController@delete');
Route::get('/dashboard', function()
{
    return view('user.test.view');
})->middleware('auth')->name('test');
Route::get('/test/result', function()
{
    return view('user.test.result');
})->middleware('auth')->name('test.result');
Route::get('/test/start', function()
{
    $questions = DB::table('questions')->where("chapter_id", Auth::user()->chapter_id)->get();
    return view('user.test.start')->with('questions', $questions);
})->middleware('auth')->name('test.start');
Route::post('test/check','TestController@checkAnswer');
// stream api
Route::post('stream/init','StreamController@startStreaming');
Route::post('stream/start','StreamController@liveSuccessful');
Route::post('stream/stop','StreamController@stopLiveStream');
Route::get('/video/{filename}', function ($filename) {
    // Pasta dos videos.
    $videosDir = base_path('storage/app/uploads');
    if (file_exists($filePath = $videosDir."/".$filename)) {
        $stream = new \App\Http\VideoStream($filePath);
        return response()->stream(function() use ($stream) {
            $stream->start();
        });
    }
    return response("File doesn't exists", 404);
})->middleware('auth');

Route::get('chapters',function(){
    return response("Invalid request URI", 200);
});
Route::get('chapters/thumbnail/',function(){
    return response("Invalid request URI", 200);
});
Route::get('/chapters/{filename}', function ($filename)
{
    $path = storage_path('app/uploads/' . $filename);
    if (!File::exists($path)) {
        // abort(404);
        $path = asset('images/image.png');
    }
    $img = Image::make($path);
    return $img->response('jpg');
});
Route::get('/chapters/thumbnail/{filename}', function ($filename)
{
    $path = storage_path('app/uploads/' . $filename);
    if (!File::exists($path)) {
        // abort(404);
        $path = asset('images/image.png');
    }
    $img = Image::make($path);
    $height = $img->height();
    $width = $img->width();
    if($height > $width){
        $img = $img->crop($width, $width);
    }else{
        $img = $img->crop($height, $height);
    }
    $img->resize(300,300);
    return $img->response('jpg');
});

Route::post('/upload','ChapterController@upload');
Route::get('/verify/mail/{hashCode}/{user_id}', 'UserController@verifyMail');
Route::get('/register', function()
{
    return redirect('/login');
});

// Payment
Route::post('/payment/pay/confirm/{id}', 'PaymentController@payForReffreal')->middleware('auth')->middleware('admin');

Route::get('payment/basic/{api_key}', ['as' => 'payment', 'uses' => 'PaymentController@payment_basic']);
Route::get('payment/core/{api_key?}', ['as' => 'payment', 'uses' => 'PaymentController@payment_core']);
Route::get('payment/status', ['as' => 'payment.status', 'uses' => 'PaymentController@status']);

Route::post('paymnet/redirect', 'PaymentController@redirect');
Route::post('payment/register', 'PaymentController@register');
Route::post('payment/upgrade', 'PaymentController@upgrade');
