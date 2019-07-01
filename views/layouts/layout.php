<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;

$this->title = Yii::t('ckimage', 'Upload images to the server');
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<meta charset="<?= Yii::$app->charset ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?= Html::csrfMetaTags() ?>
<title><?= Html::encode($this->title) ?></title>
<link rel="stylesheet" href="/css/ckImageManager.css">
<?php $this->head() ?>
<body>
<?php $this->beginBody() ?>

<main class="ck-grid-container">
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
