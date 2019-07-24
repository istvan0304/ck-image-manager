<?php

/* @var $this \yii\web\View */
/* @var $ckImageManagerForm istvan0304\imagemanager\models\CkImageForm */
/* @var $ckImages */

?>

<div class="ck-details">
    <div class="ck-details-header">
        <h4><?= Yii::t('ckimage', 'Details') ?></h4>
        <a href="#" class="ck-close ck-details-close"></a>
    </div>
    <div class="ck-details-body"></div>
</div>

<?php echo $this->render('_handlerRow',
    [
        'ckImageManagerForm' => $ckImageManagerForm,
    ]); ?>

<div id="ck-upload-status">
    <a href="#" class="ck-close ck-progress-close"></a>
    <div class="ck-progress-container">
        <div class="ck-progress-line">
            <div class="ck-progress"><span id="ck-percentage"></span></div>
        </div>
        <button id="ck-upload-details" class="ck-btn ck-btn-second"><?= Yii::t('ckimage', 'Details') ?></button>
    </div>

</div>

<?php echo $this->render('_imageList',
    [
        'ckImages' => $ckImages
    ]); ?>

<aside id="ck-sidebar">
    <p class="ck-no-select"><?= Yii::t('ckimage', 'No image selected.') ?></p>
    <div class="ck-sidebar-content"></div>
</aside>
