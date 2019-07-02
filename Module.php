<?php

namespace istvan0304\imagemanager;

use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $defaultRoute = 'ck-image';

    public function init()
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['ckimage'])) {
            Yii::$app->i18n->translations['ckimage'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@istvan0304/imagemanager/messages'
            ];
        }
    }
}