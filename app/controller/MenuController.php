<?php
declare(strict_types=1);

namespace plugin\webmanAdmin\app\controller;

use plugin\webmanAdmin\app\annotations\Middleware;
use plugin\webmanAdmin\app\annotations\RequestMapping;
use plugin\webmanAdmin\app\middleware\AuthMiddleware;
use plugin\webmanAdmin\app\model\AdminMenus;
use plugin\webmanAdmin\app\utils\PermissionCheck;
use support\Request;
use Yangliuxin\Utils\Utils\ServiceConstant;
use Yangliuxin\Utils\Utils\TreeUtils;


#[Middleware(AuthMiddleware::class)]
class MenuController extends AbstractAdminController
{
    #[RequestMapping('/admin/menu', methods: ['GET'])]
    public function listMenu(Request $request)
    {
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'menu');
        $menuList = AdminMenus::all();
        $menuList = TreeUtils::getTree($menuList);
        $this->bladeData['menuList'] = $menuList;
        return view('menu/list', $this->bladeData);

    }

    #[RequestMapping('/admin/menu/create', methods: ['GET', 'POST'])]
    public function createMenu(Request $request)
    {
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'menu');
        $selectMenuOptions = AdminMenus::where('type', 1)->get()->toArray();
        $selectMenuOptions = TreeUtils::getTree($selectMenuOptions);
        $this->bladeData['selectMenuOptions'] = $selectMenuOptions;
        $data = new AdminMenus();
        $this->bladeData['data'] = $data;
        $this->bladeData["error"] = [];
        if ($request->method() == "POST") {
            $csrf_token = $request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $request->session()->get('csrf_token')){
                $errors['title'] = 'csrf token error';
                return view('menu/edit', ['error' => $errors]);
            }
            if ($request->post("title") == '') {
                $errors['title'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return view('menu/edit', $this->bladeData);
            }

            AdminMenus::create([
                'title' => $request->input("title"),
                'uri' => $request->input("uri"),
                'parent_id' => $request->input("parent_id"),
                'type' => $request->input("type"),
                'icon' => $request->input("icon"),
                'slug' => $request->input("slug"),
                'sort' => $request->input("sort"),
            ]);
            return redirect("/admin/menu");
        }

        return view('menu/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/menu/edit/{id}', methods: ['GET', 'POST'])]
    public function editMenu(Request $request, $id)
    {
        if (!$id) {
            return redirect('/admin/menu');
        }
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'menu');
        $selectMenuOptions = AdminMenus::where('type', 1)->get()->toArray();
        $selectMenuOptions = TreeUtils::getTree($selectMenuOptions);
        $this->bladeData['selectMenuOptions'] = $selectMenuOptions;
        $data = AdminMenus::find($id);
        $this->bladeData['data'] = $data;
        $this->bladeData["error"] = [];
        if ($request->method() == "POST") {
            $csrf_token = $request->input('csrf_token');
            if(!$csrf_token || $csrf_token != $request->session()->get('csrf_token')){
                $errors['title'] = 'csrf token error';
                return view('menu/edit', ['error' => $errors]);
            }
            if ($request->post("title") == '') {
                $errors['title'] = '名称不许为空';
                $this->bladeData["error"] = $errors;
                return view('menu/edit', $this->bladeData);
            }

            AdminMenus::where('id', $id)->update([
                'title' => $request->input("title"),
                'uri' => $request->input("uri"),
                'parent_id' => $request->input("parent_id"),
                'type' => $request->input("type"),
                'icon' => $request->input("icon"),
                'slug' => $request->input("slug"),
                'sort' => $request->input("sort"),
            ]);
            return redirect("/admin/menu");
        }

        return view('menu/edit', $this->bladeData);
    }

    #[RequestMapping('/admin/menu/delete/{id}', methods: ['DELETE'])]
//    #[PermissionCheck('menu')]
    public function deleteMenu(Request $request, $id)
    {
        if (!$id) {
            return json(ServiceConstant::error(ServiceConstant::MSG_PARAM_ERROR));
        }
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'menu');
        AdminMenus::where('id', $id)->delete();

        return json(ServiceConstant::success());
    }

}