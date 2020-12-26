<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('getappversion', function() {
      return "3";
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/forgot-password/mail', 'UserController@forgotPasswordMail');
Route::post('/forgot-password/verify', 'UserController@verifyOTPForgotPassword');
Route::post('/forgot-password/set-password', 'UserController@setNewPassword');
Route::post('/chapter/mindset',  'ChapterController@mindset')->middleware('api_check');
Route::post('/chapter/spending_pattern',  'ChapterController@spending_pattern')->middleware('api_check');
Route::post('/chapter/passive_income',  'ChapterController@passive_income')->middleware('api_check');
Route::post('/chapter/networking',  'ChapterController@networking')->middleware('api_check');
//login
Route::post('login', 'UserController@login');
// Register
Route::post('register', 'UserController@register');
//list modules
Route::get('modules', 'ModuleController@index');
// list single module
Route::get('module/{id}', 'ModuleController@show');
//create new module
Route::post('module','ModuleController@store');
//update module
Route::put('module', 'ModuleController@store');
//delete module
Route::delete('module/{id}','ModuleController@destroy');
//create module
Route::post('chapter', 'ChapterController@store');
//update module
Route::post('chapter/update', 'ChapterController@update');
//list chapters
Route::get('chapters/{module_id}', 'ChapterController@index');
//delete chapter
Route::delete('chapter/{id}','ChapterController@destroy');
//list questions
Route::get('questions/{chapter_id}', 'QuestionController@index');
//list questions
Route::get('test/questions/{chapter_id}', 'QuestionController@testQuestion');
Route::post('test/questions/{chapter_id}', 'QuestionController@testQuestion');
//create new module
Route::post('question','QuestionController@store');
//create new module
Route::post('test/check','TestController@checkAnswer')->middleware('api_check');
//update module
Route::put('question', 'QuestionController@store');
//list students
Route::get('students', 'UserController@index');
// get current chapter
Route::post('chapter/current', 'ChapterController@chapter')->middleware('api_check');
// list next chapters
// Route::get('chapter/next/{id}', 'ChapterController@nextChapter');
// list of syllabus
// Route::get('syllabus','ModuleController@syllabus');
Route::post('/syllabus', 'UserController@syllabus')->middleware('api_check');
//Update password
Route::put('update/password/{user_id}', 'UserController@updatePassword');
//Chapter progress
// Route::get('chapter/progress/{user_id}','ChapterController@chapterProgress');
//Test progress
Route::get('test/progress/{user_id}','TestController@testAvg');
// Test chart data
Route::get('test/chart/records/{user_id}','TestController@testRecords');
Route::post('test/chart/records/{user_id?}','TestController@testRecords')->middleware('api_check');
Route::get('adminboard','UserController@board');
Route::get('account/approval/list','UserController@approvalList');
Route::post('get/all/activities', 'ActivityController@getAllActivities');
Route::post('admin/month/earing', 'TransactionController@getCurrentMonthEaring')->middleware('api_check');
// Profile
Route::group(['prefix' => 'profile','middleware'=>'api_check'], function() {
    Route::post('/','UserController@profile');
    Route::post('update','UserController@updateProfile');
    Route::post('update/password','UserController@updatePassword');
    Route::post('update/contactInfo','UserController@updateContactInfo');
    Route::post('get/bankdetails','BankDetailsController@show');
    Route::post('update/bankdetails','BankDetailsController@update');
    Route::post('getearning','TransactionController@getEarning');
    Route::post('progress','ChapterController@moduleProgress');
    Route::post('activities','ActivityController@getAllActivitiesByUser');
    Route::post('earning','ActivityController@getAllEarningByUser');
    Route::post('approve','UserController@approve')->name('approve');
});
Route::group(['prefix' => 'event'], function() {
    Route::post('/all','EventController@getAllEvents');
    Route::post('/store','EventController@store');
    Route::post('/next/{count?}','EventController@getNextEvents');
    Route::post('/{id}','EventController@getEvent');
    Route::post('/delete/{id}','EventController@destroy');
});
Route::post('/contactus', 'UserController@contactus')->name('contactus');
Route::get('payment/{status}','TransactionController@store')->name('payment');
Route::post('/save/pancard/{previousFile?}','UserController@savePancardPic');
Route::post('/save/profiles','UserController@saveProfiledPic')->middleware('api_check');
Route::post('/contact-us/mail','UserController@contactUs');
Route::get('/notifications', 'NotificationController@get')->middleware('api_check');
Route::post('/notifications', 'NotificationController@get')->middleware('api_check');
Route::post('/notifications/read/{id}', 'NotificationController@readNotification')->middleware('api_check');
Route::post('/notifications/delete/all', 'NotificationController@deleteAll')->middleware('api_check');
Route::post('/notifications/delete/{id}', 'NotificationController@destroy')->middleware('api_check');
Route::get('profile/pic/thumbnail/{profile_img_url}', function (Request $request, $profile_img_url)
{
    $path = storage_path('app/profiles/' . $profile_img_url);
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

    // return 'data:image/jpg;base64,' . file_get_contents($path);
    return $img->response('jpg');
});
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

Route::post('report/age-wise', 'UserController@getAgeWiseReport')->middleware('api_check');
Route::post('/view/{id}', 'ChapterController@viewVideo')->middleware('api_check');
Route::post('add/reffreal', 'UserController@addReffreal')->middleware('api_check');
Route::post('register/information', 'UserController@information')->middleware('api_check');
Route::post('getmembershiptype', 'UserController@getMembershipType')->middleware('api_check');
Route::post('getcountries', function()
{
    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
    return json_encode($countries);
});
