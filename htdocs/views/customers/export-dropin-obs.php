<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\helpers\Html;

$this->title = date('l jS \of F Y');


$this->params['breadcrumbs'][] = $this->title;
?>
<div class="export-all">
    <br>
<?php
$gridColumns = [
    'ID',
    'Name',
    'Gender',
    'ConfirmationDate',
    'Eligible',
    'Comments',
    'CommentsOld',
    'Interpreter',
    'NeedInterpreter',
    'FirstDropin',
    'Dropin',
    'DropinDate',
    'Observation',  
];

echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'fontAwesome' => true,
    'dropdownOptions' => [
        'label' => 'Export with observations',
        'class' => 'btn btn-default'
    ]
]) . "<hr>\n" .
 GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'export' => [
        'fontAwesome' => true,
    ]
]);
?>

</div>
