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
Route::get('/captcha/{config?}', function(\Mews\Captcha\Captcha $captcha, $config='default'){
    //GD Library extension not available with this PHP installation.
    return $captcha->create($config);
});

//社交认证登陆路由
Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('login/wechat', 'Auth\LoginController@redirectToWechatProvider')->name('login.weixin');
Route::get('login/wechat/callback', 'Auth\LoginController@handleWechatProviderCallback');

Route::get('/MP_verify_TneROHDiBDphZRvS.txt', function(){
    return 'TneROHDiBDphZRvS';
});

Route::get('login/facebook', 'Auth\LoginController@redirectToFacebookProvider')->name('login.facebook');
Route::get('login/facebook/callback', 'Auth\LoginController@handleFacebookProviderCallback');

Route::resources(['socials' => 'SocialController']); //post need

//admin
Route::group( ['middleware' => ['auth']], function() {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    // Route::resource('posts', 'PostController');
    Route::resource('permissions','PermissionController');

    Route::resources([
        'schools' => 'SchoolController',
        'teachers' => 'TeacherController',
        'books' => 'BookController',
        'zooms' => 'ZoomController',
        'agencies' => 'AgencyController',
        'students' => 'StudentController',
        'products' => 'ProductController',
        'orders' => 'OrderController',
        'classRecords' => 'ClassRecordController',

        'rrules' => 'RruleController', //except create!!! create from order
    ]);
    //覆盖create！！！
    Route::get('rrules/create/{order}','RruleController@create')->name('rrules.create');

    //登陆用户 角色登记
    Route::get('student/register','StudentController@register')->name('students.register');
    Route::get('teacher/register','TeacherController@register')->name('teachers.register');
    Route::get('agency/register','AgencyController@register')->name('agencies.register');
    Route::post('student/register','StudentController@registerStore');
    Route::post('teacher/register','TeacherController@registerStore');
    Route::post('agency/register','AgencyController@registerStore');

    //老师、学生(代理不可以)的上课记录列表
    Route::get('class-records','ClassRecordController@indexByRole')->name('classRecords.indexByRole');
    // 代理： 我的学生
    Route::get('student-recommend','StudentController@indexByRecommend')->name('students.recommend');
    Route::get('classRecords/order/{order}','ClassRecordController@indexbyOrder')->name('classRecords.indexbyOrder');
    //某个学生的上课记录 for agncy！
    Route::get('classRecords/student/{student}','ClassRecordController@indexbyStudent')->name('classRecords.indexbyStudent');
    //某个学生的上课记录 for tacher
    Route::get('classRecords/teacher/{teacher}','ClassRecordController@indexbyTeacher')->name('classRecords.indexbyTeacher');
    //老师/管理人员快速请假标记 ajax
    Route::post('/classRecords/{classRecord}/exception/{id}','ClassRecordController@flagException')->name('classRecords.flagException')->where('id', '[0-4]');


    
});

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

//for dev
//todo Comment when live 
Route::get('/dev/su/{id}', function($id){
    Auth::loginUsingId($id);
    return redirect('home');
})->name('sudo.su')->middleware('admin');
