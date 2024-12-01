<?php
namespace plugin\webmanAdmin\app\utils;
use plugin\webmanAdmin\app\model\AdminMenus;
use plugin\webmanAdmin\app\model\AdminRolePermissions;
use plugin\webmanAdmin\app\model\AdminRoleUsers;
use support\Request;

class PermissionCheck
{

    /**
     * @throws \Exception
     */
    public static function check($uid, String $slug): void
    {
        if(!$uid){
            throw new \Exception('no permission', 401);
        }
        if(!self::checkPermission($uid, $slug)){
            throw new \Exception('no permission', 403);
        }
    }

    protected static function hasPermission($uid, $targetId): bool
    {
        if ($uid == 1) {
            return true;
        }
        $roleId = AdminRoleUsers::where('user_id', $uid)->value('role_id');
        if ($roleId == 1) {
            return true;
        }
        $permissions = AdminRolePermissions::getAllPermissions($roleId);
        if (in_array($targetId, $permissions)) {
            return true;
        }
        return false;

    }
    protected static function checkPermission($uid, $slug): bool
    {
        $permissionId = AdminMenus::getMenuIdBySlug($slug);
        if(self::hasPermission($uid, $permissionId)){
            return true;
        }
        return false;
    }

}