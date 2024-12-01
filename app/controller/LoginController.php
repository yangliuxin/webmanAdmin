<?php
declare(strict_types=1);

namespace plugin\webmanAdmin\app\controller;

use plugin\webmanAdmin\app\annotations\RequestMapping;
use plugin\webmanAdmin\app\model\AdminUsers;
use support\Request;
use support\Response;

class LoginController extends AbstractAdminController
{

    #[RequestMapping('/admin/login', methods: ['GET'])]
    public function login(Request $request)
    {
        if(!$request->session()->get('csrf_token')){
            $request->session()->set('csrf_token', str_random(32));
        }
        $this->bladeData = [];
        $this->bladeData['_token'] = $request->session()->get('csrf_token');
        return view('login', $this->bladeData);
    }

    #[RequestMapping('/admin/login', methods: ['POST'])]
    public function loginPost(Request $request)
    {
        if(!$request->session()->get('csrf_token')){
            $request->session()->set('csrf_token', str_random(32));
        }
        $this->bladeData['_token'] = $request->session()->get('csrf_token');
        $username = $request->input('username');
        $password = $request->input('password');
        $remember = $request->input('remember');
        $csrf_token = $request->input('csrf_token');
        if(!$csrf_token || $csrf_token != $request->session()->get('csrf_token')){
            $this->bladeData['error']['username'] = 'csrf token error';
            return view('login', $this->bladeData);
        }
        if (!$username) {
            $this->bladeData['error']['username'] = '用户名不许为空';
            return view('login', $this->bladeData);
        }
        if (!$password) {
            $this->bladeData['error']['password'] = '请输入密码';
            return view('login', $this->bladeData);
        }

        $user = AdminUsers::where('username', $username)->first();
        if (!$user) {
            $this->bladeData['error']['username'] = '用户不存在';
            return view('login', $this->bladeData);
        }
        if (md5($password . $user['salt']) != $user['password']) {
            $this->bladeData['error']['password'] = '密码不正确';
            return view('login', $this->bladeData);
        }

        $request->session()->set("admin", json_encode($user, 320));
        if($remember){
            $response = new Response(302, ['Location' => '/admin/index']);
            $response->cookie('admin', json_encode($user, 320), 3600 * 24 * 7);
            return $response;
        } else {
            return new Response(302, ['Location' => '/admin/index']);
        }
    }

    #[RequestMapping('/admin/logout', methods: ['POST', 'get'])]
    public function logout(Request $request)
    {
        $request->session()->delete("admin");
        $request->session()->refresh();
        $response = new Response(302, ['Location' => '/admin/login']);
        $response->cookie('admin', '', time() - 3600);
        $this->bladeData = [];
        return $response;
    }


}