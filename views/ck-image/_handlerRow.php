<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

?>

<div class="ck-handle-row">
    <button id="ck-img-upload" class="ck-btn ck-btn-second"><?= Yii::t('ckimage', 'Upload') ?></button>
    <?php $form = ActiveForm::begin(['id' => 'img-upload-form', 'enableClientValidation' => false]); ?>

    <?= $form->field($ckImageManagerForm, 'img_files[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label(false) ?>

    <?php ActiveForm::end(); ?>
</div>
