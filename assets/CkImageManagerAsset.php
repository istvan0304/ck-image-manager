<?php

namespace app\ckImageManager\assets;

class CkImageManagerAsset extends \yii\web\AssetBundle
{
    public $baseUrl = '@web';
    public $sourcePath = '@app/ckImageManager/assets/';

    public $css = [
//        'css/ckImageManager.css',
    ];

    public $js = [
        'js/ckImageManager.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
