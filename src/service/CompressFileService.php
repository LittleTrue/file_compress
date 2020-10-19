<?php

namespace file\FileHandleService;

use file\FileHandleClient\Application;
use file\FileHandleClient\Base\Exceptions\ClientError;

/**
 * 海关直连总署 - CEB报文 - 出口运抵单.
 */
class CompressFileService
{
    /**
     * @var ArrivalExport
     */
    private $_arrivalExport;

    public function __construct(Application $app)
    {
        $this->_arrivalExport = $app['compress_file'];
    }

    /**
     * 压缩图片.
     *
     * @throws ClientError
     * @throws \Exception
     */
    public function compressImg($saveName = '', $src, $percent = 1)
    {
        if (empty($src)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_arrivalExport->compressImg($saveName, $src, $percent);
    }
}
