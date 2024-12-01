<?php
declare(strict_types=1);

namespace plugin\webmanAdmin\app\middleware;

use Webman\Http\Response;
use Webman\Http\Request;
use Webman\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        $session =  $request->session();
        $user = $session->get("admin");
        $cookie = $request->cookie('admin');
        if (!$user && !$cookie) {
            $session->delete("admin");
            $session->refresh();
            return redirect('/admin/login');
        }
        if($user){
            $user = json_decode($user,true);
        } else {
            $user = json_decode($cookie, true);
            $session->set("admin", $cookie);
        }
        if(!$user){
            $session->delete("admin");
            $session->refresh();
            return redirect('/admin/login');
        }
        return  $handler($request);
    }
}