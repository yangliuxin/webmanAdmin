<?php
declare(strict_types=1);

namespace plugin\webmanAdmin\app\controller;

use plugin\webmanAdmin\app\annotations\Middleware;
use plugin\webmanAdmin\app\annotations\RequestMapping;
use plugin\webmanAdmin\app\middleware\AuthMiddleware;
use plugin\webmanAdmin\app\model\AdminMenus;
use plugin\webmanAdmin\app\model\AdminRolePermissions;
use plugin\webmanAdmin\app\model\AdminRoles;
use plugin\webmanAdmin\app\utils\PermissionCheck;
use support\Request;
use Yangliuxin\Utils\Utils\ServiceConstant;

#[Middleware(AuthMiddleware::class)]
class RolesController extends AbstractAdminController
{
    #[RequestMapping('/admin/roles', methods: ['GET'])]
    public function listRoles(Request $request)
    {
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'role');
        $where = [];
        $id = $request->input('id', 0);
        if($id){
            $where[] = ['id', '=' ,$id];
        }
        $rolesList = AdminRoles::where($where)->orderBy('id', 'asc')->paginate(20);
        $this->bladeData['rolesList'] = $rolesList;
        $this->bladeData['pageNo'] = $rolesList->currentPage();
        $totalPages = floor($rolesList->total() / $rolesList->perPage()) + ($rolesList->total() % $rolesList->perPage()) == 0 ? 0 : 1;
        $this->bladeData['totalPages'] = $totalPages;
        $this->bladeData['total'] = $rolesList->total();
        $this->bladeData['pageNums'] = $rolesList->perPage();
        return view('roles/list', $this->bladeData);
    }

    #[RequestMapping('/admin/roles/create', methods: ['GET', 'POST'])]
    public function createRoles(Request $request)
    {
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'role');
        $jsTreeList = [];
        $menuList = AdminMenus::all();
        foreach ($menuList as $key => $menu) {
            $jsTreeList[] = [
                'id' => strval($menu['id']),
                'text' => $menu['title'],
                'parent' => $menu['parent_id'] == 0 ? '#' : $menu['parent_id'],
                'icon' => $menu['type'] == 1 ? 'fa ' . $menu['icon'] : ' glyphicon glyphicon-file ',
                'state' => ['opened' => true, 'disabled' => false, 'selected' => false],
            ];
        }
        $data = new AdminRoles();
        $this->bladeData['treeData'] = json_encode($jsTreeList);
        $this->bladeData["error"] = [];
        $this->bladeData['data'] = $data;
        $this->bladeData["permissions"] = json_encode([]);
        if ($request->method() == "POST") {
            $csrf_token = $request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $request->session()->get('csrf_token')){
                $errors['name'] = 'csrf token error';
                return view('roles/edit', ['error' => $errors]);
            }
            if ($request->post("name") == '') {
                $errors['name'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return view('roles/edit', $this->bladeData);
            }
            $permissions = $request->post("permissions");
            $role = AdminRoles::create([
                'name' => $request->post("name"),
                'slug' => $request->post("slug"),
            ]);
            AdminRolePermissions::where('role_id', $role['id'])->delete();
            $permissions = explode(",", $permissions);
            foreach ($permissions as $permission) {
                if($permission) {
                    AdminRolePermissions::create([
                        'role_id' => $role['id'],
                        'permission_id' => $permission,
                    ]);
                }
            }
            return redirect("/admin/roles");
        }
        return view('roles/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/roles/edit/{id}', methods: ['GET', 'POST'])]
    public function editRoles(Request $request, $id)
    {
        if (!$id) {
            return redirect('/admin/roles');
        }
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'role');
        $jsTreeList = [];
        $menuList = AdminMenus::all();
        foreach ($menuList as $key => $menu) {
            $jsTreeList[] = [
                'id' => strval($menu['id']),
                'text' => $menu['title'],
                'parent' => $menu['parent_id'] == 0 ? '#' : $menu['parent_id'],
                'icon' => $menu['type'] == 1 ? 'fa ' . $menu['icon'] : ' glyphicon glyphicon-file ',
                'state' => ['opened' => true, 'disabled' => false, 'selected' => false],
            ];
        }
        $data = AdminRoles::find($id);
        $this->bladeData['treeData'] = json_encode($jsTreeList);
        $this->bladeData["error"] = [];
        $this->bladeData['data'] = $data;
        $permissions = AdminRolePermissions::where('role_id', $id)->pluck("permission_id")->toArray();
        $this->bladeData["permissions"] = json_encode($permissions);
        if ($request->method() == "POST") {
            $csrf_token = $request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $request->session()->get('csrf_token')){
                $errors['name'] = 'csrf token error';
                return view('roles/edit', ['error' => $errors]);
            }
            if ($request->post("name") == '') {
                $errors['name'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return view('roles/edit', $this->bladeData);
            }

            AdminRoles::where('id', $id)->update([
                'name' => $request->input("name"),
                'slug' => $request->input("slug"),
            ]);
            $permissions = $request->post("permissions");
            AdminRoles::where('id', $id)->update([
                'name' => $request->post("name"),
                'slug' => $request->post("slug"),
            ]);
            AdminRolePermissions::where('role_id', $id)->delete();
            $permissions = explode(",", $permissions);
            foreach ($permissions as $permission) {
                if($permission){
                    AdminRolePermissions::create([
                        'role_id' => $id,
                        'permission_id' => $permission,
                    ]);
                }
            }
            return redirect("/admin/roles");
        }

        return view('roles/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/roles/delete/{id}', methods: ['DELETE'])]
    public function deleteRoles(Request $request, $id)
    {
        if (!$id) {
            return json(ServiceConstant::error(ServiceConstant::MSG_PARAM_ERROR));
        }
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'role');
        AdminRoles::where('id', $id)->delete();
        AdminRolePermissions::where('role_id', $id)->delete();
        return json(ServiceConstant::success());
    }
}