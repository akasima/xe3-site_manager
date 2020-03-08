<?php
namespace Akasima\XePlugin\SiteManager\Components\Widgets\Skins;

use Xpressengine\Skin\GenericSkin;

class ErrorLogSkin extends GenericSkin
{


    /**
     * The component id
     *
     * @var string
     */
    protected static $id = 'widget/site_manager@errorLog/skin/site_manager@default';

    /**
     * The information for component
     *
     * @var array
     */
    protected static $componentInfo = [
        'name' => '기본 에러 로그 위젯 스킨',
        'description' => 'Xpressengine의 기본 에러 로그 위젯 스킨입니다'
    ];

    /**
     * @var string
     */
    protected static $path = 'site_manager/src/Components/Widgets/Skins/views/errorLog';

    /**
     * @var string
     */
    protected static $viewDir = '';

    /**
     * @var array
     */
    protected static $info = [
        'support' => [
            'mobile' => true,
            'desktop' => true
        ]
    ];

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $this->data = array_merge($this->data, ['title' => array_get($this->setting(), "@attributes.title")]);

        return parent::render();
    }
}
