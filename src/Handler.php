<?php
namespace Akasima\XePlugin\SiteManager;

use File;
use App\Http\Controllers\ArtisanBackgroundHelper;

class Handler
{
    use ArtisanBackgroundHelper;

    public function serverEnv()
    {
        $data = [];

        // check php version
        $result = [
            'text' => 'php version',
            'status' => true,
            'message' => PHP_VERSION,
        ];
        $phpVersion = 70000;
        if (PHP_VERSION_ID < $phpVersion) {
            $result['status'] = false;
            $result['message'] = sprintf('PHP 버전은 7.0 보다 높아야 합니다. 현재 PHP 버전[%s]', PHP_VERSION_ID);
        }
        $data[] = $result;

        // check mysql pdo
        $result = [
            'text' => 'mysql pdo',
            'status' => true,
            'message' => '',
        ];
        $check = extension_loaded('PDO') &&
            (extension_loaded('pdo_mysql') || extension_loaded('pdo_cubrid'));
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('POD 익스텐션을 찾을 수 없습니다. [해결 방법 link : %s]', '');
        }
        $data[] = $result;

        // check curl
        $result = [
            'text' => 'curl',
            'status' => true,
            'message' => '',
        ];
        $check = extension_loaded('curl');
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('CURL 익스텐션을 찾을 수 없습니다. [해결 방법 link : %s]', '');
        }
        $data[] = $result;

        // fileinfo extension
        $result = [
            'text' => 'fileinfo extension',
            'status' => true,
            'message' => '',
        ];
        $check = extension_loaded('fileinfo');
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('fileinfo 익스텐션을 찾을 수 없습니다. [해결 방법 link : %s]', '');
        }
        $data[] = $result;

        // gd extension
        $result = [
            'text' => 'gd extension',
            'status' => true,
            'message' => '',
        ];
        $check = extension_loaded('gd');
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('gd 익스텐션을 찾을 수 없습니다. [해결 방법 link : %s]', '');
        }
        $data[] = $result;

        // image extension
        $result = [
            'text' => 'image extension',
            'status' => true,
            'message' => '',
        ];
        $check = function_exists('imagejpeg') &&
            function_exists('imagepng') &&
            function_exists('imagegif');
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('imagejpeg, imagepng, imagegif 함수를 사용할 수 없습니다. [해결 방법 link : %s]', '');
        }
        $data[] = $result;

        // mbstring extension
        $result = [
            'text' => 'mbstring extension',
            'status' => true,
            'message' => '',
        ];
        $check = extension_loaded('mbstring');
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('mbstring 익스텐션을 찾을 수 없습니다. [해결 방법 link : %s]', '');
        }
        $data[] = $result;

        // openssl extension
        $result = [
            'text' => 'openssl extension',
            'status' => true,
            'message' => '',
        ];
        $check = extension_loaded('openssl');
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('openssl 익스텐션을 찾을 수 없습니다. [해결 방법 link : %s]', '');
        }
        $data[] = $result;

        // zip extension
        $result = [
            'text' => 'zip extension',
            'status' => true,
            'message' => '',
        ];
        $check = extension_loaded('zip');
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('zip 익스텐션을 찾을 수 없습니다. [해결 방법 link : %s]', '');
        }
        $data[] = $result;

        // allow url fopen
        $result = [
            'text' => 'allow url fopen',
            'status' => true,
            'message' => '',
        ];
        $check = ini_get('allow_url_fopen');
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('allow url fopen 을 사용할 수 없습니다. [해결 방법 link : %s]', '');
        }
        $data[] = $result;

        // composer home path write able permission
        $result = [
            'text' => 'composer home path',
            'status' => true,
            'message' => '',
        ];
        // composer home path config
        $pluginConfig = app('xe.config')->get('plugin');
        $composerHomePath = $pluginConfig->get('composer_home') ?: (getenv('COMPOSER_HOME') ?: (getenv('HOME') ? rtrim(getenv('HOME'), '/') . '/.composer' : ''));
        // check is write able
        $check = is_writable($composerHomePath);
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('[%s] 에 쓰기 권한이 없습니다. [해결 방법 link : %s]', $composerHomePath, '');
        }
        $data[] = $result;

        // rewrite rule
        $result = [
            'text' => 'rewrite rule',
            'status' => true,
            'message' => '',
        ];
        $check = $this->rewriteRule();
        if ($check == false) {
            $result['status'] = false;
            $result['message'] = sprintf('[%s] 에 쓰기 권한이 없습니다. [해결 방법 link : %s]', $composerHomePath, '');
        }
        $data[] = $result;

        return $data;
    }

    public function rewriteRule()
    {
        $url = route('site_manager.check.rewrite');
        try {
            $response = file_get_contents($url);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        if ($response != "1") {
            return false;
        }
        return true;
    }

    public function solution($type)
    {
        $methodName = camel_case('solution_'. $type);
        if (method_exists($this, $methodName) == false) {
            $message = sprintf('[%s] method is not exists. (request type : [%s])', $methodName, $type);
            throw new \Exception($message, 500);
        }

        return $this->$methodName();
    }

    public function solutionUnlimitedUpdateLoading()
    {
        $path = storage_path('app');
        $logFile = $path . '/operations.json';

        if (file_exists($logFile)) {
            File::move(
                $logFile,
                $path . sprintf('/operations_solution_%s.json', date('YmdHis'))
            );
        }
        return '[unlimited update loading] resolved.';
    }

    public function solutionFixComposerHomePath()
    {
        // composer home path config
        $pluginConfig = app('xe.config')->get('plugin');
        $path = storage_path('framework/composer');
        $this->makeDir($path);
        $pluginConfig->set('composer_home', $path);
        app('xe.config')->modify($pluginConfig);
        return '[composer home path] changed to [' . $path . '].';
    }

    public function solutionUpdateTranslation()
    {
        $updateLists = ['core'];
        $this->runArtisan('translation:import');

        $pluginConfig = app('xe.config')->get('plugin');
        foreach($pluginConfig->get('list') as $name => $info) {
            if (isset($info['status']) && $info['status'] == 'deactivated') {
                continue;
            }

            $updateLists[] = $name;
            $this->runArtisan('translation:import', [
                'name' => $name,
            ]);
        }

        return '[update translations] of [' . implode(', ' , $updateLists) . '].';
    }

    public function configFileGenerate($key, array $data)
    {
        $dir = config_path() . '/' . env('APP_ENV', 'production');
        $this->makeDir($dir);

        $data = $this->encodeArr2Str($data);

        $file = $dir . "/{$key}.php";
        file_put_contents($file, '<?php' . str_repeat(PHP_EOL, 2) . 'return [' . PHP_EOL . $data . '];' . PHP_EOL);
    }

    public function makeDir($dir)
    {
        /** @var Filesystem $filesystem */
        $filesystem = app('files');
        if (!$filesystem->isDirectory($dir)) {
            return $filesystem->makeDirectory($dir);
        }

        return true;
    }

    /**
     * Encode array to string.
     *
     * @param array $arr   array
     * @param int   $depth depth
     * @return string
     */
    public function encodeArr2Str(array $arr, $depth = 0)
    {
        $output = '';

        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $output .= $this->getIndent($depth) . "'{$key}' => " . '[' . PHP_EOL . $this->encodeArr2Str($val, $depth + 1) . $this->getIndent($depth) . '],' . PHP_EOL;
            } else {
                if (is_bool($val)) {
                    $val = $val ? 'true' : 'false';
                } elseif (!is_int($val)) {
                    $val = "'{$val}'";
                }
                $output .= $this->getIndent($depth) . "'{$key}' => " . $val .',' . PHP_EOL;
            }
        }

        return $output;
    }

    /**
     * Get indent.
     *
     * @param int $depth depth
     * @return string
     */
    public function getIndent($depth)
    {
        $indent = '';
        for ($a = 0; $a <= $depth; $a++) {
            $indent .= str_repeat(' ', 4);
        }

        return $indent;
    }
}
