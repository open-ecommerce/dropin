<?php
$this->title = 'Tutorial';
?>
<div class="site-tutorial">
    <h2 class="lead">Application Screencasts</h2>

    <h3>Dropin Management</h3>
    <video width="100%" controls poster="<?= Yii::$app->request->baseUrl ?>/tutorials/main-entrance-video.jpg">
        <source src="<?= Yii::$app->request->baseUrl ?>/tutorials/drop-in-entrance-tutorial.mp4" type="video/mp4">
    </video>

    <h3>New Clients</h3>
    <video width="100%" controls poster="<?= Yii::$app->request->baseUrl ?>/tutorials/new-clients-video.jpg">
        <source src="<?= Yii::$app->request->baseUrl ?>/tutorials/drop-in-new-clients-tutorial.mp4" type="video/mp4">
    </video>
</div>
