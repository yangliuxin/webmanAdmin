<?php
declare(strict_types=1);

namespace plugin\webmanAdmin\app\controller;

use plugin\webmanAdmin\app\annotations\Middleware;
use plugin\webmanAdmin\app\annotations\RequestMapping;
use plugin\webmanAdmin\app\middleware\AuthMiddleware;
use plugin\webmanAdmin\app\model\AdminRoles;
use plugin\webmanAdmin\app\model\AdminRoleUsers;
use plugin\webmanAdmin\app\model\AdminUsers;
use plugin\webmanAdmin\app\utils\PermissionCheck;
use support\Request;
use Yangliuxin\Utils\Utils\ServiceConstant;

#[Middleware(AuthMiddleware::class)]
class UsersController extends AbstractAdminController
{
    #[RequestMapping('/admin/users', methods: ['GET'])]
    public function listUsers(Request $request)
    {
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'user');
        $where = [];
        $id = $request->input('id', 0);
        if($id){
            $where[] = ['id', '=' ,$id];
        }
        $usersList = AdminUsers::where($where)->orderBy('id', 'asc')->paginate(20);
        $this->bladeData['usersList'] = $usersList;
        $this->bladeData['pageNo'] = $usersList->currentPage();
        $totalPages = floor($usersList->total() / $usersList->perPage()) + ($usersList->total() % $usersList->perPage()) == 0 ? 0 : 1;
        $this->bladeData['totalPages'] = $totalPages;
        $this->bladeData['total'] = $usersList->total();
        $this->bladeData['pageNums'] = $usersList->perPage();
        return view('users/list', $this->bladeData);
    }

    #[RequestMapping('/admin/users/create', methods: ['GET', 'POST'])]
    public function createUsers(Request $request)
    {
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'user_add');
        $data = new AdminUsers();
        $data['roles'] = 0;
        $this->bladeData["error"] = [];
        $this->bladeData['data'] = $data;
        $this->bladeData['roles'] = AdminRoles::pluck('name', 'id');
        if ($request->method() == "POST") {
            $csrf_token = $request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $request->session()->get('csrf_token')){
                $errors['username'] = 'csrf token error';
                return view('users/edit', ['error' => $errors]);
            }
            if ($request->post("username") == '') {
                $errors['username'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return view('users/edit', $this->bladeData);
            }
            if ($request->post("password") == '') {
                $errors['password'] = '密码不许为空';
                $this->bladeData["error"] = $errors;
                return view('users/edit', $this->bladeData);
            }
            $username = $request->post('username');
            $name = $request->post('name');
            $password = $request->post('password');
            $avatarFile = $request->file('avatar_file');
            $userData['username'] = $username;
            if ($name) {
                $userData['name'] = $name;
            }
            $userData['salt'] = str_random(8);
            $userData['password'] = md5($password . $userData['salt']);

            $path = $this->dealFiletype($avatarFile);
            if ($path) {
                $userData['avatar'] = $path;
            } else {
                $userData['avatar'] = '';
            }

            $adminUser = AdminUsers::create($userData);
            if($request->input("roles")){
                AdminRoleUsers::create([
                    'user_id' => $adminUser['id'],
                    'role_id' => intval($request->post("roles"))
                ]);
            }

            return redirect("/admin/users");
        }
        return view('users/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/users/edit/{id}', methods: ['GET', 'POST'])]
    public function editUsers(Request $request, $id)
    {
        if (!$id) {
            return redirect('/admin/users');
        }
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'user');
        $data = AdminUsers::find($id)->toArray();
        $data['roles'] = AdminRoleUsers::getRoleIdByUserId($id);
        $this->bladeData["error"] = [];
        $this->bladeData['data'] = $data;
        $this->bladeData['roles'] = AdminRoles::pluck('name', 'id');
        if ($request->method() == "POST") {
            $csrf_token = $request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $request->session()->get('csrf_token')){
                $errors['username'] = 'csrf token error';
                return view('users/edit', ['error' => $errors]);
            }
            if ($request->post("username") == '') {
                $errors['username'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return view('users/edit', $this->bladeData);
            }
            if ($request->post("username") != $data['username']) {
                $errors['username'] = '用户名不允许修改';
                $this->bladeData["error"] = $errors;
                return view('users/edit', $this->bladeData);
            }
            if ($request->post("password") == '') {
                $errors['password'] = '密码不许为空';
                $this->bladeData["error"] = $errors;
                return view('users/edit', $this->bladeData);
            }
            $name = $request->post('name');
            $password = $request->post('password');
            $avatarFile = $request->file('avatar_file');
            $userData = [];
            if ($name) {
                $userData['name'] = $name;
            }
            if ($password && $password != $data['password']) {
                $userData['password'] = md5($password . $data['salt']);
            }
            $path = $this->dealFiletype($avatarFile);
            if ($path) {
                $userData['avatar'] = $path;
            }
            AdminUsers::where('id', $id)->update($userData);
            if($request->input("roles")){
                AdminRoleUsers::where('user_id', $id)->delete();
                AdminRoleUsers::create([
                    'user_id' => $id,
                    'role_id' => intval($request->post("roles"))
                ]);
            }
            return redirect("/admin/users");
        }

        return view('users/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/users/delete/{id}', methods: ['DELETE'])]
    public function deleteUsers(Request $request, $id)
    {
        if (!$id) {
            return json(ServiceConstant::error(ServiceConstant::MSG_PARAM_ERROR));
        }
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'user');
        AdminUsers::where('id', $id)->delete();
        AdminRoleUsers::where('user_id', $id)->delete();
        return json(ServiceConstant::success());
    }
}