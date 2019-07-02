<?php

use Yii;

/* @var $this \yii\web\View */
/* @var $ckImageArray */

?>

<div class="ck-selected-img-container">
    <div class="ck-selected-img">
        <img src="<?= '/imagemanager/ck-image/preview-thumbnail?id=' . $ckImageArray['id'] ?>" class="ck-img">
    </div>
    <p class="ck-detail ck-selected-img-name"><?= $ckImageArray['orig_name'] ?></p>
    <p class="ck-detail ck-selected-img-size"><?= $ckImageArray['size'] ?></p>
</div>

<button class="ck-btn ck-btn-first" id="ck-select" data-id="<?= $ckImageArray['id'] ?>"><?= Yii::t('ckimage', 'Select') ?></button>
<button class="ck-btn ck-btn-third" id="ck-delete" data-id="<?= $ckImageArray['id'] ?>"><?= Yii::t('ckimage', 'Delete') ?></button>
