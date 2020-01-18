<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::get('/register/{user}', 'Auth\RegisterController@showRegistrationFormByRecommend')->name('register.recommend');
Route::get('/recommend', 'AnonymousController@recommend')->name('recommend');

//首页
Route::get('/', function () {
    return view('welcome');
});

//登录后第一页
Route::get('/home', 'HomeController@index')->name('home');

//验证码
Route::get('/captcha/{config?}', function (\Mews\Captcha\Captcha $captcha, $config = 'default') {
    //GD Library extension not available with this PHP installation.
    return $captcha->create($config);
});

//社交认证登陆路由
Route::get('/MP_verify_0Tyj6A2d0WC0Yizo.txt', function () {
    return '0Tyj6A2d0WC0Yizo';
});

Route::get('login/wechat/callback', 'SocialController@handleWechatProviderCallback')->name('login.weixin.callback');
Route::get('login/wechat', 'SocialController@redirectToWechatProvider')->name('login.weixin');

Route::get('login/github', 'SocialController@redirectToGithubProvider');
Route::get('login/github/callback', 'SocialController@handleGithubProviderCallback');

Route::get('login/facebook', 'SocialController@redirectToFacebookProvider')->name('login.facebook');
Route::get('login/facebook/callback', 'SocialController@handleFacebookProviderCallback');

//route for verification
Route::get('/facebook/webhook', 'FacebotController@receive')->middleware('verify');
//where Facebook sends messages to. No need to attach the middleware to this because the verification is via GET
Route::post('/facebook/webhook', 'FacebotController@receive');
Route::get('/facebook/bind', 'FacebotController@bindPsid')->middleware('auth')->name('bind.facebook');

Route::resources(['socials' => 'SocialController']); //post need

// https://abc.dev/orders/overdue
// https://abc.dev/orders/done
// https://abc.dev/orders/xxxx
foreach (App\Models\Order::LIST_BY as $item) {
    Route::get("orders/$item", 'OrderController@index')->middleware('admin')->name("orders.$item");
}

//后台配置
Route::group(['prefix'=>'admin', 'middleware' => ['admin']], function () {
    Route::resource('voteTypes', 'VoteTypeController');
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    // Route::resource('posts', 'PostController');
    Route::resource('permissions', 'PermissionController');

    Route::resources([
        'schools'      => 'SchoolController',
        'teachers'     => 'TeacherController',
        'books'        => 'BookController',
        'zooms'        => 'ZoomController',
        'agencies'     => 'AgencyController',
        'students'     => 'StudentController',
        'products'     => 'ProductController',
        'orders'       => 'OrderController',
        'classRecords' => 'ClassRecordController',

        'rrules' => 'RruleController', //except create!!! create from order

        // 'socials'  => 'SocialController', // bug!!! only for anonymous
        'profiles' => 'ProfileController',
        'bills'    => 'BillController',

        'videos'        => 'VideoController',
    ]);
    Route::get('videos/cut/{class_record}', 'VideoController@cut')->name('videos.cut');
    //升级学生用户为代理用户
    Route::get('agencies/upgrade/{user}', 'AgencyController@upgrade')->name('agencies.upgrade');
    Route::post('agencies/upgrade/{user}', 'AgencyController@upgradeStore')->name('agencies.upgradeStore');
    //覆盖create！！！
    Route::get('rrules/create/{order}', 'RruleController@create')->name('rrules.create');

    //登陆用户 角色登记 todo delete some!!
    Route::get('student/register', 'StudentController@register')->name('students.register');
    Route::get('teacher/register', 'TeacherController@register')->name('teachers.register');
    Route::get('agency/register', 'AgencyController@register')->name('agencies.register');
    Route::post('student/register', 'StudentController@registerStore');
    Route::post('teacher/register', 'TeacherController@registerStore');
    Route::post('agency/register', 'AgencyController@registerStore');

    //老师、学生(代理不可以)的上课记录列表
    Route::get('class-records', 'ClassRecordController@indexByRole')->name('classRecords.indexByRole');
    // 代理： 我的学生
    Route::get('student-recommend', 'StudentController@indexByRecommend')->name('students.recommend');
    Route::get('classRecords/order/{order}', 'ClassRecordController@indexByOrder')->name('classRecords.indexbyOrder');
    //某个学生的上课记录 for agncy！
    Route::get('classRecords/student/{user}', 'ClassRecordController@indexByStudent')->name('classRecords.indexbyStudent');
    //某个学生的上课记录 by teacher, only for Admin
    Route::get('classRecords/teacher/{teacher}', 'ClassRecordController@indexByTeacher')->name('classRecords.indexbyTeacher');
    //某个代理的上课记录 by agncy, only for Admin
    Route::get('classRecords/agency/{agency}', 'ClassRecordController@indexByAgency')->name('classRecords.indexByAgency');
    //老师/管理人员快速请假标记 ajax
    Route::post('/classRecords/{classRecord}/exception/{id}', 'ClassRecordController@flagException')->name('classRecords.flagException')->where('id', '[0-4]');

    //自动生成0-1-2-3-4-5-6-7 今天/昨天/前天 过去7天记录
    Route::post('/classRecords/order/{order}/generate', 'ClassRecordController@generate')->name('classRecords.generate');

    //classNotify for teacher/student via sms
    Route::get('classNotify/{classRecord}/teacher', 'ClassRecordController@classNotifyTeacher')->name('admin.classNotifyTeacher');
    Route::get('classNotify/{classRecord}/student', 'ClassRecordController@classNotifyStudent')->name('admin.classNotifyStudent');

    Route::post('/order/{order}/status/{id}', 'OrderController@flagStatus')->name('orders.flagStatus')->where('id', '[0-4]');

    Route::get('/referrals', function () {
        return view('referrals');
    })->name('referrals');

    Route::get('/autologin', function () {
        return view('autologin');
    })->name('autologin');

    Route::get('charts', 'ChartController@index');

    Route::get('student/import', 'StudentController@import')->name('students.import');
    Route::post('student/import', 'StudentController@importStore');

    Route::post('/classRecord/{classRecord}/rate/{voteType}/{value}', 'ClassRecordController@rate')->name('classRecords.rate');
});

Route::get('videos/{video}', 'VideoController@show')->name('videos.show');
//OAuth2 test！！
Route::get('/signin', 'AuthController@signin');
Route::get('/callback', 'AuthController@callback');
Route::get('/signout', 'AuthController@signout');

// AdminController only for admin middleware
Route::get('admin/genClass', 'AdminController@genClass')->name('admin.genClass');

// classNotify by role via sms
Route::get('dev/su/{user}', 'AdminController@su')->name('sudo.su');

Route::get('/admin', function () {
    return view('sb-admin2/demo');
});
