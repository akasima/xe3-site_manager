<?php
namespace Akasima\XePlugin\SiteManager;

use Route;
use Xpressengine\Plugin\AbstractPlugin;
use XeInterception;

class Plugin extends AbstractPlugin
{
    /**
     * 이 메소드는 활성화(activate) 된 플러그인이 부트될 때 항상 실행됩니다.
     *
     * @return void
     */
    public function boot()
    {
        // implement code
        $register = app('xe.pluginRegister');
        $register->add(Components\Widgets\ErrorLog::class);
        $register->add(Components\Widgets\Skins\ErrorLogSkin::class);

        $this->route();

        /** @var \Illuminate\Foundation\Application $app */
        $app = app();
        $app->singleton(Handler::class, function ($app) {
            $proxy = XeInterception::proxy(Handler::class);
            return new $proxy();
        });
        $app->alias(Handler::class, 'site_manager.handler');
    }

    protected function route()
    {
        app('xe.register')->push(
            'settings/menu',
            'setting.site_manager',
            [
                'title' => 'Site Manager',
                'description' => 'Site management ',
                'display' => true,
                'ordering' => 7000
            ]
        );

        // implement code

        Route::settings(self::getId(), function () {
            Route::get('/', ['as' => 'settings.site_manager.index', 'uses' => 'Controller@index', 'settings_menu' => 'setting.site_manager']);
            Route::get('/cache/clear', ['as' => 'settings.site_manager.cacheClear', 'uses' => 'Controller@cacheClear']);
            Route::get('/log/clear', ['as' => 'settings.site_manager.logClear', 'uses' => 'Controller@logClear']);
            Route::get('/phpinfo', ['as' => 'settings.site_manager.phpinfo', 'uses' => 'Controller@phpinfo']);
            Route::get('/solution/{type}', ['as' => 'settings.site_manager.solution', 'uses' => 'Controller@solution']);

            Route::get('/setup/handy', ['as' => 'settings.site_manager.setupHandy', 'uses' => 'Controller@setupHandy']);
            Route::post('/update/handy', ['as' => 'settings.site_manager.updateHandy', 'uses' => 'Controller@updateHandy']);

            Route::get('/get_log_file/{file?}', ['as' => 'settings.site_manager.getLogFile', 'uses' => 'Controller@getLogFile']);
            Route::get('/get_log_file/{file}/tail', ['as' => 'settings.site_manager.getLogFileTail', 'uses' => 'Controller@getLogFileTail']);
            Route::get('/download_log_file/{file}', ['as' => 'settings.site_manager.downloadLogFile', 'uses' => 'Controller@downloadLogFile']);
        }, ['namespace' => 'Akasima\XePlugin\SiteManager']);



        // for checking enable rewrite
        Route::group(
            ['namespace' => 'Akasima\XePlugin\SiteManager'],
            function () {
                Route::get('/site_manager/check/rewrite/enable', ['as' => 'site_manager.check.rewrite', 'uses' => 'Controller@checkRewrite']);
            }
        );

    }

    /**
     * 플러그인이 활성화될 때 실행할 코드를 여기에 작성한다.
     *
     * @param string|null $installedVersion 현재 XpressEngine에 설치된 플러그인의 버전정보
     *
     * @return void
     */
    public function activate($installedVersion = null)
    {
        // implement code
    }

    /**
     * 플러그인을 설치한다. 플러그인이 설치될 때 실행할 코드를 여기에 작성한다
     *
     * @return void
     */
    public function install()
    {
        // add error log wigdet to settings dashboard
        $users = app('xe.user')->users()->where('rating', 'super')->get();

        $widgetboxHandler = app('xe.widgetbox');
        $widgetboxPrefix = 'dashboard-';
        $widgetStr = json_dec('[{"grid":{"md":"12"},"rows":[],"widgets":[{"limit_day":"3","@attributes":{"id":"widget\/site_manager@errorLog","title":"dddd","skin-id":"widget\/site_manager@errorLog\/skin\/site_manager@default"}}]}]');

        foreach ($users as $user) {
            $widgetBoxId = $widgetboxPrefix.$user->getId();
            $widgetbox = $widgetboxHandler->find($widgetBoxId);
            $content = $widgetbox->content;
            array_push($content, $widgetStr);
            $widgetbox->content = $content;
            $widgetbox->update();
        }
    }

    /**
     * 해당 플러그인이 설치된 상태라면 true, 설치되어있지 않다면 false를 반환한다.
     * 이 메소드를 구현하지 않았다면 기본적으로 설치된 상태(true)를 반환한다.
     *
     * @return boolean 플러그인의 설치 유무
     */
    public function checkInstalled()
    {
        // implement code

        return parent::checkInstalled();
    }

    /**
     * 플러그인을 업데이트한다.
     *
     * @return void
     */
    public function update()
    {
        // implement code
    }

    /**
     * 해당 플러그인이 최신 상태로 업데이트가 된 상태라면 true, 업데이트가 필요한 상태라면 false를 반환함.
     * 이 메소드를 구현하지 않았다면 기본적으로 최신업데이트 상태임(true)을 반환함.
     *
     * @return boolean 플러그인의 설치 유무,
     */
    public function checkUpdated()
    {
        // implement code

        return parent::checkUpdated();
    }
}
