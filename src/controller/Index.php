<?php

namespace thans\layuiAdmin\controller;

use thans\layuiAdmin\Dashbord;
use thans\layuiAdmin\facade\AdminsAuth;
use thans\layuiAdmin\Index as Home;
use think\facade\Config;
use think\facade\Db;
use think\facade\App;

class Index
{
    public function index()
    {
        $home       = new Home();
        $adminsInfo = session('admins_info');
        $home->userName($adminsInfo->nickname ? $adminsInfo->nickname : $adminsInfo->name);
        $menu = AdminsAuth::menu();
        $home->menu($menu);
        $dashboard = Config('admin.dashboard');
        $home->firstTabUrl($dashboard['url']);
        $home->firstTabName($dashboard['title']);
        $home->logo(Config::get('admin.logo'));
        $home->sLogo(Config::get('admin.slogo'));
        $userMenus = Config::get('admin.userMenu');
        foreach ($userMenus as $menu) {
            $home->userMenu($menu[0], $menu[1], isset($menu[2]) ? $menu[2] : []);
        }
        
        return $home->render();
    }

    public function dashboard()
    {
        $dashboard = new Dashbord();
        $mysql     = Db::query('select VERSION() as version');
        $mysql     = $mysql[0]['version'];
        $mysql     = empty($mysql) ? lang('UNKNOWN') : $mysql;
        $dashboard->card()->title('系统信息')->datas([
            'Thinkphp版本' => App::version(),
            '服务器版本'      => $_SERVER['SERVER_SOFTWARE'],
            '系统'         => PHP_OS,
            'PHP版本'      => phpversion(),
            'MySql版本'    => $mysql,
            '剩余空间'       => round((@disk_free_space('.') / (1024 * 1024)), 2).'M',
        ]);
        $dashboard->card()->title('联系信息')->datas([
            '官网'   => '<a href=\'//layuiadmin.com\' target="_blank">LayuiAdmin-TP</a></a>',
            '商务联系' => '<img src="//cdn.inge.vip/thans.jpeg">',
            '为我点赞' => '<a target="_blank" href=\'https://gitee.com/thans/layuiAdmin/stargazers\'><img src=\'//gitee.com/thans/layuiAdmin/badge/star.svg?theme=white\' alt=\'star\'></img></a>',
        ]);

        return $dashboard->render();
    }
}
