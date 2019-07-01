<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\StringHelper;

?>

<?php Pjax::begin(['id' => 'ck-pjax-image-list', 'options' => ['class' => 'ck-image-list']]); ?>
<?php foreach ($ckImages as $ckImage): ?>
    <?php if ($ckImage->isExistsFile()): ?>
        <div class="ck-image-container">
            <div class="ck-img-box" data-id="<?= $ckImage->id ?>">
                <img src="<?= Url::to(['ck-image/preview-thumbnail', 'id' => $ckImage->id]) ?>" class="ck-img" alt="">
                <p class="ck-image-name"
                   title="<?= $ckImage->orig_name ?>"><?= StringHelper::truncate($ckImage->orig_name, 15) ?></p>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
<?php Pjax::end(); ?>
