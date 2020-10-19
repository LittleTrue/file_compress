<?php

namespace file\FileHandleClient\OperateFile\CompressFile;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 */
class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['compress_file'] = function ($app) {
            return new Client($app);
        };
    }
}
