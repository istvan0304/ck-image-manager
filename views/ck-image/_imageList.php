<?php

use istvan0304\imagemanager\models\CkImage;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View */
/* @var $ckImages */

?>

<?php Pjax::begin(['id' => 'ck-pjax-image-list', 'options' => ['class' => 'ck-image-list']]); ?>
<?php foreach ($ckImages as $ckImage): ?>
    <?php if ($ckImage->isExistsFile()): ?>

        <div class="ck-image-container">
            <div class="ck-img-box" data-id="<?= $ckImage->id ?>">
                <img src="<?= Url::to(['ck-image/preview-thumbnail', 'id' => $ckImage->id]) ?>" class="ck-img" alt="">
                <p class="ck-image-name"
                   title="<?= $ckImage->orig_name ?>"><?= StringHelper::truncate($ckImage->orig_name, 15) ?><br>
                    <span class="ck-img-data"><?= $ckImage->cr_date ?></span><br>
                    <span class="ck-img-data"><?= CkImage::formatSizeUnits($ckImage->size) ?></span>
                </p>
            </div>
        </div>

    <?php endif; ?>
<?php endforeach; ?>
<?php Pjax::end(); ?>
