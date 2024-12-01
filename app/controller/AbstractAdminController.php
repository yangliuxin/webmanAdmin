<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace plugin\webmanAdmin\app\controller;

use GuzzleHttp\Psr7\MultipartStream;
use plugin\webmanAdmin\app\model\AdminMenus;
use plugin\webmanAdmin\app\model\AdminRolePermissions;
use plugin\webmanAdmin\app\model\AdminRoleUsers;
use support\Request;
use Webman\Http\UploadFile;
use Yangliuxin\Utils\Utils\TreeUtils;

abstract class AbstractAdminController
{
    protected array $bladeData;

    protected ?array $user;

    protected function _init(Request $request)
    {
        $this->bladeData = [];
        $userSession = $request->session()->get("admin");
        if($userSession){
            $user = json_decode($userSession, true);
            $this->user = $user;
            $this->bladeData['user'] = $user;
            $menuDataList = AdminMenus::where('type', 1)->get()->toArray();
            foreach ($menuDataList as $key => $menu) {
                if ($menu['uri'] == '') {
                    $menuDataList[$key]['uri'] = '/admin/#';
                } elseif ($menu['uri'] == '/') {
                    $menuDataList[$key]['uri'] = '/admin';
                } else {
                    $menuDataList[$key]['uri'] = '/admin/' . $menu['uri'];
                }
                if (!self::hasPermission($user['id'], $menu['id'])) {
                    unset($menuDataList[$key]);
                }
            }

            $this->bladeData['menuDataList'] = TreeUtils::getTree($menuDataList);
            if(!$request->session()->get('csrf_token')){
                $request->session()->set('csrf_token', str_random(32));
            }
            $this->bladeData['_token'] = $request->session()->get('csrf_token');
        }
    }

    protected function dealFiletype(UploadFile $file): ?string
    {
        if ($file->isValid()) {
            $image = file_get_contents($file->getRealPath());
            if ($image) {
                $imagePath = '/uploads/images/' . str_random(10) . '.png';
                $storagePath = realpath(BASE_PATH . '/public');
                file_put_contents($storagePath . $imagePath, $image);
                return $imagePath;
            }
            return null;
        }
        return null;
    }

    protected function dealMultiFiletype($files): array
    {
        $result = [];
        foreach ($files as $file) {
            if ($file->isValid()) {
                $image = file_get_contents($file->getRealPath());
                if ($image) {
                    $imagePath = '/uploads/images/' . str_random(10) . '.png';
                    $storagePath = realpath(BASE_PATH . '/public');
                    file_put_contents($storagePath . $imagePath, $image);
                    $result[] =  $imagePath;
                }
            }
        }

        return $result;
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
