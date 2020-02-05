# 权限管理

## 编辑editor 可以查看 orders列表，但不显示 成交价格！

## 目标1: 授权剪辑功能给编辑用户
	- 作为编辑用户
		- 可以按老师、学生查看课程记录列表
		- 可以进入任意上课记录 的 剪辑页面 并增删改查
	- 角色：4-editor
	- 权限：
		Create a Video
		Delete any Video
		Update any Video
		View any Video
		View List index

		View own Video
		Delete own Video
		Update own Video
## steps：
	- 解除之前的 admin middleware

## Code：

```
php artisan migrate --path=/database/migrations/alter

UPDATE `orders` SET student_uid=user_id;
update `orders` SET user_id = 1; // creator


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
$role = Role::findByName('editor');
//Video
$p1 = Permission::create(['name' => 'Create a Video']); //cut a video
$p2 = Permission::create(['name' => 'Delete any Video']);
$p3 = Permission::create(['name' => 'Update any Video']);
$p4 = Permission::create(['name' => 'View any Video']);
$p5 = Permission::create(['name' => 'View Video list']);


$role->givePermissionTo($p1);
$role->givePermissionTo($p2);
$role->givePermissionTo($p4);




//student id 259 Paul user id 385
// https://abc.dev/dev/su/385
$user = User::find(385);
$user->assignRole('editor');//4
$roles = $user->getRoleNames();

// $p1 = Permission::findOrCreate('Create a Video');
// $p2 = Permission::findOrCreate('Delete any Video');
// $p4 = Permission::findOrCreate('View any Video');

$role->hasPermissionTo('View any Video');
$user->can('View any Video')
$user->hasPermissionTo('View any Video');
    $user->hasDirectPermission('View any Video');


//Order permisson
$p6 = Permission::create(['name' => 'Create a Order']);
$role->givePermissionTo($p6);
$p7 = Permission::create(['name' => 'Delete any Order']);
$p8 = Permission::create(['name' => 'Update any Order']);
$p9 = Permission::create(['name' => 'View any Order']);
$role->givePermissionTo($p9);
// $p10 = Permission::findOrCreate('View any Order');

$p11 = Permission::create(['name' => 'View Order list']);
$role->givePermissionTo($p11);
$p12 = Permission::create(['name' => 'View Own Order']);
$role->givePermissionTo($p12);
$p13 = Permission::create(['name' => 'Update Own Order']);
$role->givePermissionTo($p13);

$p13 = Permission::create(['name' => 'Update any ClassRecord']);
$role->givePermissionTo($p14);
$p15 = Permission::create(['name' => 'Update any ClassRecord Status']);
$role->givePermissionTo($p15);
$p16 = Permission::create(['name' => 'Update any Order Status']);
$role->givePermissionTo($p16);
```