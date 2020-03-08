<?php
namespace Akasima\XePlugin\SiteManager;

use Encore\Admin\LogViewer\LogViewer as OriginLogViewer;

class LogViewer extends OriginLogViewer
{
    public function setFilePath($path)
    {
        $this->filePath = $path;
    }

    /**
     * Get previous page url.
     *
     * @return bool|string
     */
    public function getPrevPageUrl()
    {
        if ($this->pageOffset['end'] >= $this->getFilesize() - 1) {
            return false;
        }

        return route('settings.site_manager.getLogFile', [
            'file' => $this->file, 'offset' => $this->pageOffset['end'],
        ]);
    }

    /**
     * Get Next page url.
     *
     * @return bool|string
     */
    public function getNextPageUrl()
    {
        if ($this->pageOffset['start'] == 0) {
            return false;
        }

        return route('settings.site_manager.getLogFile', [
            'file' => $this->file, 'offset' => -$this->pageOffset['start'],
        ]);
    }

    public function getDownloadPageUrl()
    {
        return route('settings.site_manager.downloadLogFile', [
            'file' => $this->file,
        ]);
    }
}
