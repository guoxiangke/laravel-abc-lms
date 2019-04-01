<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Illuminate\Support\Str;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	foreach (User::ROLES as $roleName) {
  			Role::create([
  			  'name' => $roleName
  			]);
    	}
      // ls app/Models
      $models = ['Agency','Order','Post','Zoom','School','Student','Teacher'];
      foreach ($models as $key => $modelName) {

        $plural = Str::plural(strtolower($modelName));

        Permission::create(['name' => 'create ' . $plural]);
        
        Permission::create(['name' => 'view own ' . $plural]);
        Permission::create(['name' => 'view any ' . $plural]);

        Permission::create(['name' => 'edit own ' . $plural]);
        Permission::create(['name' => 'edit any ' . $plural]);
      }

      // // 重置角色和权限的缓存
      // app()['cache']->forget('spatie.permission.cache');

      // $role = Role::create(['name' => 'wx']);//wxUser微信用户 openId@wx
      // $role = Role::create(['name' => 'mp']);//wxUser微信用户 ==>关联 ==〉wxAccount微信公众用户 openId@mp
      // $role = Role::create(['name' => 'seeker']);//婚恋用户


      // // 创建权限
      // Permission::create(['name' => 'edit posts']);
      // Permission::create(['name' => 'delete posts']);
      // Permission::create(['name' => 'publish posts']);
      // Permission::create(['name' => 'unpublish posts']);

      // // 创建角色并赋予已创建的权限
      // $role = Role::create(['name' => 'writer']);
      // $role->givePermissionTo('edit posts');
      // $role->givePermissionTo('delete posts');

      // $role = Role::create(['name' => 'admin']);
      // $role->givePermissionTo('publish posts');
      // $role->givePermissionTo('unpublish posts');
    }
}
