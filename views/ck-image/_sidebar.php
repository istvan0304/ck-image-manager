<?php

use Yii;

/* @var $this \yii\web\View */

?>

<aside class="ck-sidebar">
    <div class="ck-selected-img-container">
        <div class="ck-selected-img">

        </div>
        <p class="ck-selected-img-name"></p>
    </div>

    <button class="ck-btn ck-btn-first" id="ck-select"><?= Yii::t('ckimage', 'Select') ?></button>
    <button class="ck-btn ck-btn-third" id="ck-delete"><?= Yii::t('ckimage', 'Delete') ?></button>
</aside>
