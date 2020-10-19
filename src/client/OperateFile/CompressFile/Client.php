<?php

namespace file\FileHandleClient\OperateFile\CompressFile;

use file\FileHandleClient\Application;
use file\FileHandleClient\Base\BaseClient;

/**
 * 客户端.
 */
class Client extends BaseClient
{
    /**
     * @var Application
     */
    protected $credentialValidate;

    //源文件路径
    private $src;

    //临时图片信息
    private $image;

    //源文件路径
    private $image_info;

    //压缩比例
    private $percent = 0.5;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->credentialValidate = $app['credential'];
    }

    /**
     * 销毁图片.
     */
    public function __destruct()
    {
        imagedestroy($this->image);
    }

    /** 高清压缩图片.
     * @param string $saveName 提供图片名（可不带扩展名，用源图扩展名）用于保存。或不提供文件名直接显示
     */
    public function compressImg($saveName = '', $src, $percent = 1)
    {
        $this->src = $src;
        if (filesize($this->src) > 819200) {
            $this->percent = 0.5;
        } else {
            $this->percent = $percent;
        }
        clearstatcache();

        $this->_openImage();

        if (!empty($saveName)) {
            $this->_saveImage($saveName);
        }  //保存
        else {
            $this->_showImage();
        }
    }

    /**
     * 内部：打开图片.
     */
    private function _openImage()
    {
        [$width, $height, $type, $attr] = getimagesize($this->src);

        $this->image_info = [
            'width'  => $width,
            'height' => $height,
            'type'   => image_type_to_extension($type, false),
            'attr'   => $attr,
        ];
        $fun = 'imagecreatefrom' . $this->image_info['type'];

        $this->image = $fun($this->src);

        $this->_thumpImage();
    }

    /**
     * 内部：操作图片.
     */
    private function _thumpImage()
    {
        $new_width   = $this->image_info['width'] * $this->percent;
        $new_height  = $this->image_info['height'] * $this->percent;
        $image_thump = imagecreatetruecolor($new_width, $new_height);

        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled($image_thump, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->image_info['width'], $this->image_info['height']);
        imagedestroy($this->image);
        $this->image = $image_thump;
    }

    /**
     * 输出图片:保存图片则用saveImage().
     */
    private function _showImage()
    {
        header('Content-Type: image/' . $this->image_info['type']);
        $funcs = 'image' . $this->image_info['type'];
        $funcs($this->image);
    }

    /**
     * 保存图片到硬盘：.
     * @param string $dstImgName 1、可指定字符串不带后缀的名称，使用源图扩展名 。2、直接指定目标图片名带扩展名。
     */
    private function _saveImage($dstImgName)
    {
        if (empty($dstImgName)) {
            return false;
        }
        $allowImgs = ['.jpg', '.jpeg', '.png', '.bmp', '.wbmp', '.gif'];   //如果目标图片名有后缀就用目标图片扩展名 后缀，如果没有，则用源图的扩展名
        $dstExt    = strrchr($dstImgName, '.');
        $sourseExt = strrchr($this->src, '.');
        if (!empty($dstExt)) {
            $dstExt = strtolower($dstExt);
        }
        if (!empty($sourseExt)) {
            $sourseExt = strtolower($sourseExt);
        }

        //有指定目标名扩展名
        if (!empty($dstExt) && in_array($dstExt, $allowImgs)) {
            $dstName = $dstImgName;
        } elseif (!empty($sourseExt) && in_array($sourseExt, $allowImgs)) {
            $dstName = $dstImgName . $sourseExt;
        } else {
            $dstName = $dstImgName . $this->image_info['type'];
        }
        $funcs = 'image' . $this->image_info['type'];
        $funcs($this->image, $dstName);
    }
}
