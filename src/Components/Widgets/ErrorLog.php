<?php
namespace Akasima\XePlugin\SiteManager\Components\Widgets;

use Config;
use View;
use Xpressengine\Widget\AbstractWidget;
use Carbon\Carbon;
use Akasima\XePlugin\SiteManager\LogViewer;

class ErrorLog extends AbstractWidget
{

    /**
     * The component id
     *
     * @var string
     */
    protected static $id = 'widget/site_manager@errorLog';

    /**
     * Returns the title of the widget.
     *
     * @return string
     */
    public static function getTitle()
    {
        return 'site manager 에러 로그 위젯';
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $args = $this->config;

        // 로그인 limit_day 파일들을 대상으로 함
        $limitDay = (int)array_get($args, 'limit_day');
        if ($limitDay < 1) {
            $limitDay = 3;
        }

        $logFilePath = storage_path('logs');
        $date = Carbon::now();
        $logFiles = [];
        $logs = [];
        for ($i = 1; $i <= $limitDay; $i++) {
            $logDate =  $date->format('Y-m-d');
            $filePath = sprintf('%s/laravel-%s.log', $logFilePath, $logDate);
            $date->subDays($i);

            if (file_exists($filePath) == false) {
                continue;
            }

            $logViewer = new LogViewer();

            $logViewer->setFilePath($filePath);

            $logFiles[] = [
                'filename' => sprintf('laravel-%s.log', $logDate),
                'path' => $logViewer->getFilePath(),
                'size' => $logViewer->getFilesize(),
            ];

            $logs[] = $logViewer->fetch(0, 10);
        }


        return $this->renderSkin([
            'limitDay' => $limitDay,
            'logFiles' => $logFiles,
            'logs' => $logs,
        ]);
    }

    /**
     * Show the setting form for the widget.
     *
     * @param array $args arguments
     * @return string|\Xpressengine\UIObject\AbstractUIObject
     * @throws \Exception
     */
    public function renderSetting(array $args = [])
    {
        return uio('form', [
            'fields' => [
                'limit_day' => [
                    '_type' => 'text',
                    'label' => '에러 표시 기간',
                    'description' => '에러 메시지 표시 기간 설정'
                ]
            ],
            'value' => $args,
            'type' => 'fieldset'
        ]);
    }
}
