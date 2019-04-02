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
Route::get('/signin', 'AuthController@signin');
Route::get('/callback', 'AuthController@callback');
Route::get('/signout', 'AuthController@signout');

Route::get('/dev/su/{id}', function($id){
    Auth::loginUsingId($id);
    return redirect('home');
})->name('sudo.su');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/captcha/{config?}', function(\Mews\Captcha\Captcha $captcha, $config='default'){
    //GD Library extension not available with this PHP installation.
    return $captcha->create($config);
});


Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('login/weixin', 'Auth\LoginController@redirectToWechatProvider');
Route::get('login/weixin/callback', 'Auth\LoginController@handleWechatProviderCallback');

Route::get('login/facebook', 'Auth\LoginController@redirectToFacebookProvider');
Route::get('login/facebook/callback', 'Auth\LoginController@handleFacebookProviderCallback');

 

//admin
Route::group( ['middleware' => ['auth']], function() {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    // Route::resource('posts', 'PostController');
    Route::resource('permissions','PermissionController');

    Route::resources([
        'schools' => 'SchoolController',
        'teachers' => 'TeacherController',
        'zooms' => 'ZoomController',
        'agencies' => 'AgencyController',
        'students' => 'StudentController',
        'products' => 'ProductController',
        'orders' => 'OrderController',
        'classRecords' => 'ClassRecordController',

        'rrules' => 'RruleController',
    ]);

    //老师、学生(代理不可以)的上课记录列表
    Route::get('class-records','ClassRecordController@indexByRole')->name('classRecords.indexByRole');
    Route::get('/download/class-records/{classRecord}','ClassRecordController@downloadMp4')->name('classRecords.download');
});

// GET	/photos	index	photos.index
// GET	/photos/create	create	photos.create
// POST	/photos	store	photos.store
// GET	/photos/{photo}	show	photos.show
// GET	/photos/{photo}/edit	edit	photos.edit
// PUT/PATCH	/photos/{photo}	update	photos.update
// DELETE	/photos/{photo}	destroy	photos.destroy

