<?php

namespace App;

use App\Models\Agency;
use App\Models\PayMethod;
use App\Models\Profile;
use App\Models\School;
use App\Models\Social;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravelista\Comments\Commenter;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use HasRoles;
    use HasMediaTrait;
    use Commenter;
    use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('avatar')
            // ->useDisk('public')
            ->singleFile();
    }

    // $yourModel->addMedia($pathToImage)->toMediaCollection('avatar');
    // $yourModel->getMedia('avatar')->count(); // returns 1
    // $yourModel->getFirstMediaUrl('avatar'); // will return an url to the `$pathToImage` file

    const ROLES = [
        'admin'     => 'admin', //root todo no-use!!!!
        'developer' => 'developer', // '开发者',
        'manager'   => 'manager', // '管理人员',
        'editor'    => 'editor', // '网站编辑',

        'school'  => 'school', // 'schoolMaster',
        'teacher' => 'teacher', // 'Teacher',
        'student' => 'student', // '学生',
        'agency'  => 'agency', // '代理',
    ];

    //@see ClassRecordPolicy 谁可以评论 //谁可以查看
    const ADMIN_ROLES = ['developer', 'manager'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * for Horizon::auth.
     * @return bool [description]
     */
    public function isRoot()
    {
        return $this->id == 1;
    }

    public function isAdmin()
    {
        return $this->isRoot() || $this->hasAnyRole(self::ADMIN_ROLES);
    }

    public function isOnlyHasOneRole()
    {
        // get the names of the user's roles
        // $roles = $user->getRoleNames(); // Returns a collection
        return $this->getRoleNames()->count() === 1;
    }

    // 判断用户有且只有一个角色为student
    public function isOnlyHasStudentRole()
    {
        return $this->hasRole('student') && $this->isOnlyHasOneRole();
    }

    //姓名转pinyin和english
    public static function pinyin($name)
    {
        $name = str_replace(' ', '', $name); //去除空格
        $name = implode('', pinyin($name, 16)); //PINYIN_NAME
        if (! $name) {
            $name = implode('_', pinyin($name, 64)); //PINYIN_KEEP_ENGLISH
        }

        return $name;
    }

    public function routeNotificationForWechat($notification)
    {
        $openId = 'oTjEws-8eAAUqgR4q_ns7pbd0zN8';
        $social = $this->socials->first();
        if ($social && $social->type == Social::TYPE_WECHAT) {
            $openId = $social->social_id;
        }

        return $openId;
    }

    /**
     * isWeixinBind or isFacebookBind.
     */
    public function isSocialBind($type = Social::TYPE_WECHAT)
    {
        return Social::where('user_id', $this->id)
            ->where('type', $type)
            ->first();
    }

    // Agency::getRecommends() ProfileNameByUid() for form.
    public static function getAllReference()
    {
        return self::with('profiles')->get()->pluck('profiles.0.name', 'id')->filter()->toArray();
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function socials()
    {
        return $this->hasMany(Social::class);
    }

    public function paymethod()
    {
        return $this->hasOne(PayMethod::class);
    }

    public function school()
    {
        return $this->hasOne(School::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function agency()
    {
        return $this->hasOne(Agency::class);
    }

    /**
     * $u->isTeacher();
     * $u->isStudent();
     * $u->isAgency();.
     *
     * $profile->user->isTeacher();
     * return null or the Modle of teacher
     */
    public function isTeacher()
    {
        return $this->teacher;
    }

    public function isStudent()
    {
        return $this->student;
    }

    public function isAgency()
    {
        return $this->agency;
    }

    // u_pinyin_2
    public static function getRegisterName($registerName)
    {
        //todo 判定是否汉语拼音检测重名！！
        //1.检查profiles里相同名字的个数。
        $count = Profile::where('name', $registerName)->count();

        $name = self::pinyin($registerName);
        //处理是英文的情况
        if (! $name) {
            $name = str_replace(' ', '_', $registerName);
        }
        $name = 'u_'.$name;
        //重名处理
        if ($count) {
            $name .= '_'.$count;
        }

        return $name;
    }

    public function getShowName()
    {
        $userShowName = $this->name;
        $profileName = $this->profiles->first();
        if ($profileName) {
            $userShowName = $profileName->name;
        }

        if ($this->hasRole('agency')) {
            $agencyName = Agency::where('user_id', $this->id)->pluck('name')->first();
            if ($agencyName) {
                $userShowName = $agencyName;
            }
        }

        return $userShowName;
    }
}
