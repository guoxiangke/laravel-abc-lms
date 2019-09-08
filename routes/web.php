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

//首页
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dos', function () {
    $fileName = time().'.txt'; // generate unique name.
    $res = Storage::disk('spaces')->put($fileName, 'Hi,');
    // Setting 'public' permission for files uploaded on S3
    // https://github.com/spatie/laravel-medialibrary/issues/241
    Storage::disk('spaces')->setVisibility($fileName, 'public'); // Set the visibility to public.
    $url = Storage::disk('spaces')->url($fileName);

    return Response::json(['success' => true, 'response' => $url]);
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

Route::resources(['socials' => 'SocialController']); //post need

// https://abc.dev/orders/overdue
// https://abc.dev/orders/done
// https://abc.dev/orders/xxxx
foreach (App\Models\Order::LIST_BY as $item) {
    Route::get("orders/$item", 'OrderController@index')->middleware('admin')->name("orders.$item");
}

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

    //登陆用户 角色登记
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
    Route::get('classRecords/order/{order}', 'ClassRecordController@indexbyOrder')->name('classRecords.indexbyOrder');
    //某个学生的上课记录 for agncy！
    Route::get('classRecords/student/{student}', 'ClassRecordController@indexbyStudent')->name('classRecords.indexbyStudent');
    //某个学生的上课记录 by teacher, only for Admin
    Route::get('classRecords/teacher/{teacher}', 'ClassRecordController@indexbyTeacher')->name('classRecords.indexbyTeacher');
    //老师/管理人员快速请假标记 ajax
    Route::post('/classRecords/{classRecord}/exception/{id}', 'ClassRecordController@flagException')->name('classRecords.flagException')->where('id', '[0-4]');

    //自动生成0-1-2-3-4-5-6-7 今天/昨天/前天 过去7天记录
    Route::post('/classRecords/order/{order}/generate', 'ClassRecordController@generate')->name('classRecords.generate');

    Route::post('/order/{order}/status/{id}', 'OrderController@flagStatus')->name('orders.flagStatus')->where('id', '[0-4]');

    Route::get('/referrals', function () {
        return view('referrals');
    })->name('referrals');

    Route::get('/autologin', function () {
        return view('autologin');
    })->name('autologin');

    Route::get('charts', 'ChartController@index');
});

// Route::any('botman', 'BotmanController@handle')->name('botman');
// GET	/photos	index	photos.index
// GET	/photos/create	create	photos.create
// POST	/photos	store	photos.store
// GET	/photos/{photo}	show	photos.show
// GET	/photos/{photo}/edit	edit	photos.edit
// PUT/PATCH	/photos/{photo}	update	photos.update
// DELETE	/photos/{photo}	destroy	photos.destroy

//OAuth2 test！！
Route::get('/signin', 'AuthController@signin');
Route::get('/callback', 'AuthController@callback');
Route::get('/signout', 'AuthController@signout');

// only for dev root user!
Route::get('dev/su/{id}', 'RootController@su')->name('sudo.su');

// AdminController only for admin middleware
Route::get('admin/genClass', 'AdminController@genClass')->name('admin.genClass');
