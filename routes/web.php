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
    //redirect()->back();
})->name('sudo.su');

Route::get('/', function () {

    flashy()->success('创建成功');
    // dd(
    //     Storage::disk('onedrive')->put('00134.txt','00134'),
    //     // Storage::disk('onedrive')->files('/')
    // ) ;
    // dd(
    //     Storage::disk('upyun')->put('00134.txt','00134'),
    //     Storage::disk('upyun')->files('/')
    // ) ;

    // $access_token = env('ONEDRIVE_ACCESSS_TOKEN');
    // $graph = new Graph();
    // $graph->setAccessToken($access_token);

    // $adapter = new OneDriveAdapter($graph, 'root');
    // $filesystem = new Filesystem($adapter);
    // $path = '/tmp/test02.txt';
    // $contents = 'Hello Flysystem of OneDriveAdapter';
    // $response = $filesystem->write($path, $contents);
    // dd($response);
    return view('welcome');
});

Auth::routes();

Route::get('/captcha/{config?}', function(\Mews\Captcha\Captcha $captcha, $config='default'){
    return $captcha->create($config);
});


Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('login/weixin', 'Auth\LoginController@redirectToWechatProvider');
Route::get('login/weixin/callback', 'Auth\LoginController@handleWechatProviderCallback');

Route::get('login/facebook', 'Auth\LoginController@redirectToFacebookProvider');
Route::get('login/facebook/callback', 'Auth\LoginController@handleFacebookProviderCallback');

 
Route::get('/home', 'HomeController@index')->name('home');

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

