<?php
declare(strict_types=1);

namespace plugin\webmanAdmin\app\controller;

use plugin\webmanAdmin\app\annotations\Middleware;
use plugin\webmanAdmin\app\annotations\RequestMapping;
use plugin\webmanAdmin\app\middleware\AuthMiddleware;
use plugin\webmanAdmin\app\model\AdminStats;
use plugin\webmanAdmin\app\model\AdminUsers;
use plugin\webmanAdmin\app\utils\PermissionCheck;
use support\Request;

#[Middleware(AuthMiddleware::class)]
class IndexController extends AbstractAdminController
{
    #[RequestMapping(['/admin/index', '/admin', '/admin/'], methods: ['GET', 'POST', 'HEADER'])]
    public function index(Request $request)
    {
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'home');
        $this->bladeData['statisticsShow'] = config('plugin.webmanAdmin.app.statistics.show');
        $this->bladeData['statistics'] = config('plugin.webmanAdmin.app.statistics.data');
        $this->bladeData['hotUriList'] = AdminStats::getHotUrlList();
        $data = AdminStats::getPieData();
        $pieChartData = [
            'title' => '访问地区统计(pv)',
            'legends' => $data['legends'],
            'seriesName' => '访问地区统计(PV)',
            'seriesData' => $data['seriesData']
        ];
        $this->bladeData['pieChartData'] = json_encode($pieChartData);

        $data = AdminStats::getLineStatData();
        $lineChartData = [
            'title' => '访问统计折线图',
            'legend' => [
                'data' => ['UV', 'PV'],
                'selected' => ['UV' => true, 'PV' => true,]
            ],
            'yAxisName' => '访问量',
            'xAxisData' => ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
            'seriesData' => [
                [
                    'name' => 'UV',
                    'type' => 'line',
                    'stack' => '总量',
                    'data' => $data[1]
                ],
                [
                    'name' => 'PV',
                    'type' => 'line',
                    'stack' => '总量',
                    'data' => $data[0]
                ],
            ]
        ];

        $this->bladeData['lineChartData'] = json_encode($lineChartData);

        return view('index', $this->bladeData);
    }

    #[RequestMapping('/admin/profile', methods: ['GET', 'POST'])]
    public function profile(Request $request)
    {
        $this->_init($request);
        PermissionCheck::check($this->bladeData['user']['id'], 'home');
        $user = $this->user;
        $user = AdminUsers::find($user['id']);
        $this->bladeData['data'] = $user;
        if ($request->method() == 'POST') {
            $name = $request->post('name');
            $password = $request->post('password');
            $avatarFile = $request->file('avatar_file');
            $userData = [];
            if ($name) {
                $userData['name'] = $name;
            }
            if ($password && $password != $user['password']) {
                $userData['password'] = md5($password . $user['salt']);
            }
            $path = $this->dealFiletype($avatarFile);
            if ($path) {
                $userData['avatar'] = $path;
            }
            AdminUsers::where('id', $user['id'])->update($userData);
            $request->session()->set("admin", json_encode($user, 320));
            return view('profile', $this->bladeData);
        }
        return view('profile', $this->bladeData);
    }

}