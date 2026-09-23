<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\AttendanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$deleteTip = "Delete this Dropin attendances records.";
$deleteMsg = "Are you sure you want to delete this client dropin detail?";

$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;

?>


<div class="customers-attendance-list">
    <?php
    $gridColumns = [
        [
            'attribute' => 'DropinDate',
            'format' => ['date', 'php:d M Y'],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '30px',
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'Dropin',
            'vAlign' => 'middle',
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'Doctor',
            'vAlign' => 'middle',
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'Lawyer',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'Observation',
            'vAlign' => 'middle',
        ],
        [
            'header' => 'Delete',
            'format' => 'raw',
            'value' => function($model) use ($deleteTip, $deleteMsg, $csrfParam, $csrfToken) {
                $url = Html::encode(Url::to(['/attendance/delete', 'id' => $model->ID]));
                $msg = Html::encode($deleteMsg);
                // Use native DOM form submit to bypass yii.js/PJAX entirely
                $js = "if(confirm('{$msg}')){var f=document.createElement('form');"
                    . "f.method='post';f.action='{$url}';"
                    . "var i=document.createElement('input');i.type='hidden';"
                    . "i.name='{$csrfParam}';i.value='{$csrfToken}';"
                    . "f.appendChild(i);"
                    . "var r=document.createElement('input');r.type='hidden';"
                    . "r.name='returnUrl';r.value=window.location.href;"
                    . "f.appendChild(r);"
                    . "document.body.appendChild(f);f.submit();}";
                return Html::button(
                    '<i class="glyphicon glyphicon-trash"></i>',
                    ['type' => 'button', 'class' => 'btn btn-danger btn-xs',
                     'title' => $deleteTip, 'onclick' => $js]
                );
            },
        ],        
        
        
//        [
//            'class' => 'kartik\grid\ActionColumn',
//        ],
    ];
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'resizableColumns' => false,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjax' => false,
        'hover' => true,
        'toolbar' => false,
//        'panel' => [
//            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Previous visits</h3>',
//            'type' => 'info',
//            'footer' => false,
//        ],
        'panel' => false,
        'columns' => $gridColumns,
    ]);
    ?>


</div>
