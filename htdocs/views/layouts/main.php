<?php

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */
$this->title = $this->title . ' - ' . Yii::$app->params['appName'];

$bundle = AppAsset::register($this);
$imgPath = $bundle->baseUrl;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
<?php $this->head() ?>
    </head>
    <body>
            <?php $this->beginBody() ?>
        <div class="wrap">
            <?php
            NavBar::begin(
                    [
                        'brandLabel' => '',
                        'brandUrl' => Yii::$app->homeUrl,
                        'options' => [
                            'class' => 'navbar navbar-fixed-top navbar-bold',
                        ],
                    ]
            );
            $menuItems = [
                ['label' => 'About', 'url' => ['/site/about']],
//                ['label' => 'Contact', 'url' => ['/site/contact']],
            ];
            if (Yii::$app->hasModule('user')) {
                if (Yii::$app->user->isGuest) {
                    $menuItems[] = ['label' => 'Signup', 'url' => ['/user/registration/register']];
                    $menuItems[] = ['label' => 'Login', 'url' => ['/user/security/login']];
                } else {
                    $menuItems = [
                        ['label' => 'Clients Dropin', 'url' => ['/customers/index']],
                        ['label' => 'Doctor List', 'url' => ['/doctor/index']],
                        ['label' => 'Lawyer List', 'url' => ['/lawyer/index']],
                        ['label' => 'Utilities', 'items' => [
                                ['label' => 'Dropins Dates', 'url' => ['/dropin/index']],
                                ['label' => 'Export to file', 'url' => ['/customers/export-all']],
                                ['label' => 'Export with observations', 'url' => ['/customers/export-dropin-obs']],
                                ['label' => 'Queue Display', 'url' => ['/attendance/queue']],
                                ['label' => 'Total Attendance Statistics', 'url' => ['/attendance/statistics']],
                                ['label' => 'Confirmation Report', 'url' => '#'],
                            ]],
                    ];
                    $menuItems[] = [
                        'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::$app->user->identity->username,
                        'url' => ['/user/profile/show', 'id' => \Yii::$app->user->id],
                    ];
                    $menuItems[] = [
                        'label' => '<i class="glyphicon glyphicon-dashboard"></i>',
                        'url' => ['/admin'],
                    ];
                    $menuItems[] = [
                        'label' => '<i class="glyphicon glyphicon-log-out"></i>',
                        'url' => ['/user/security/logout'],
                        'linkOptions' => ['data-method' => 'post', 'id' => 'link-logout']
                    ];
                }
            }
            echo Nav::widget(
                    [
                        'options' => ['class' => 'navbar-nav navbar-right'],
                        'encodeLabels' => false,
                        'items' => $menuItems,
                    ]
            );
            NavBar::end();
            ?>
<?= '<div id="top-header"></div>' ?>
<?= Alert::widget() ?>
<?= $content ?>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-right">
                    &copy; <?= date('Y') ?> |
<?= Html::a('About', ['/site/about']) ?>
                </p>

                <p class="pull-left"><?=
                    Html::a(
                            Html::img($imgPath . '/img/powered-by-open-ecommerce-org.png', ['alt' => 'open-ecommerce.org']), '#', ['data-toggle' => 'modal', 'data-target' => '#infoModal']
                    )
                    ?></p>
            </div>
        </footer>

        <!-- Info Modal -->
        <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <?= $this->render('_modal') ?>
        </div>

<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
