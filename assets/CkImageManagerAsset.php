<?php

namespace istvan0304\imagemanager\assets;

use yii\web\AssetBundle;

class CkImageManagerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/istvan0304/ck-image-manager/assets';

    public $css = [
        'css/all.min.css',
        'css/ckImageManager.css',
    ];

    public $js = [
        'js/ckImageManager.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];
}
