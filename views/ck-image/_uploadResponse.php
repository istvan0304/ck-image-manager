

<p><?= Yii::t('ckimage', 'Uploaded: ') ?><?= $successUpload ?>/<?= $filesNumber ?>...</p>
<?php foreach ($responsesData as $key => $responseData): ?>
<div class="ck-response-box <?= $responseData['class'] ?>">
    <p><strong><?= $key ?></strong></p>
    <p><?= $responseData['message'] ?></p>
</div>
<?php endforeach; ?>
