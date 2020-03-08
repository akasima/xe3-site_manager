<?php
namespace Akasima\XePlugin\SiteManager;

use XeFrontend;
use XePresenter;
use App\Http\Controllers\Controller as BaseController;
use Akasima\XePlugin\SiteManager\LogViewer;
use Xpressengine\Http\Request;
use Illuminate\Cache\CacheManager;
use File;

class Controller extends BaseController
{
    public function index(Handler $handler)
    {
        $serverEnv = $handler->serverEnv();

        return XePresenter::make('site_manager::views.index', [
            'serverEnv' => $serverEnv,
        ]);
    }

    public function cacheClear(CacheManager $cache)
    {
        $cache->store('file')->flush();
        $cache->store('schema')->flush();

        File::cleanDirectory(storage_path('app/interception'));

        $viewPath = app('config')->get('view.compiled');
        File::move($viewPath . '/.gitignore', $viewPath . '/../.gitignore_back');
        File::cleanDirectory($viewPath);
        File::move($viewPath . '/../.gitignore_back', $viewPath . '/.gitignore');

        return redirect()->route('settings.site_manager.index');

    }

    public function logClear()
    {
        $viewPath = storage_path('logs');
        File::move($viewPath . '/.gitignore', $viewPath . '/../.gitignore_back');
        File::cleanDirectory($viewPath);
        File::move($viewPath . '/../.gitignore_back', $viewPath . '/.gitignore');

        return redirect()->route('settings.site_manager.index');
    }

    public function phpinfo()
    {
        XePresenter::htmlRenderPartial();
        return XePresenter::make('site_manager::views.phpinfo', []);
    }

    public function getLogFile($file = null, Request $request)
    {
        $offset = $request->get('offset');

        $viewer = new LogViewer($file);

        return XePresenter::make('site_manager::views.logs', [
            'logs'      => $viewer->fetch($offset),
            'logFiles'  => $viewer->getLogFiles(),
            'fileName'  => $viewer->file,
            'end'       => $viewer->getFilesize(),
            'tailPath'  => route('settings.site_manager.getLogFileTail', ['file' => $viewer->file]),
            'prevUrl'   => $viewer->getPrevPageUrl(),
            'nextUrl'   => $viewer->getNextPageUrl(),
            'downloadUrl' => $viewer->getDownloadPageUrl(),
            'filePath'  => $viewer->getFilePath(),
            'size'      => static::bytesToHuman($viewer->getFilesize()),
        ]);
    }

    public function getLogFileTail($file = null, Request $request)
    {
        $offset = $request->get('offset');

        $viewer = new LogViewer($file);

        list($pos, $logs) = $viewer->tail($offset);

        return compact('pos', 'logs');
    }

    protected static function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    public function downloadLogFile($file = null, Request $request)
    {
        $viewer = new LogViewer($file);

        $response = response()->download($viewer->getFilePath());

        return $response;
    }

    // check from handler rewriteRule
    public function checkRewrite()
    {
        return '1';
    }

    public function solution($type, Handler $handler)
    {
        $message = $handler->solution($type);
        return redirect()->route('settings.site_manager.index')->with('alert', [
            'type' => 'info',
            'message' => $message,
        ]);
    }

    // 편리하게
    public function setupHandy()
    {
        // ssl 항상 사용
        // 디버그 끄기
        // 메일 설정
        //
    }

    public function updateHandy()
    {

    }


    public function setupFileConfig()
    {

    }

    public function updateFileConfig()
    {

    }

    public function setupDBConfig()
    {

    }

    public function updateDBConfig()
    {

    }
}
