<?php
declare(strict_types=1);

namespace plugin\webmanAdmin\app\model;

class AdminRoleUsers extends Model
{

    protected  $table = 'admin_role_users';

    protected  $primaryKey = 'id';

    protected  $fillable = ['id', 'role_id', 'user_id', 'created_at', 'updated_at'];

    protected  $casts = ['id' => 'integer', 'role_id' => 'integer', 'user_id' => 'integer'];

    public static function getRoleIdByUserId($userId): int{
        $roleId = AdminRoleUsers::where('user_id', $userId)->value('role_id');
        if(!$roleId){
            return 0;
        }
        return $roleId;
    }

}