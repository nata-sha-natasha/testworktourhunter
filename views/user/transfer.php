<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;

$this->title = 'Перевод';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-transfer">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $balance = Yii::$app->user->identity->balance;
    $max_sum = $balance + 1000;
    ?>

    <?php if ($max_sum == 0) : ?>

    <h4 style="color: red">Недостаточно средств для перевода</h4>

    <?php else : ?>

    <h5 style="color: red">Максимальная сумма перевода: <?= $max_sum?></h5>

    <p>Пожалуйста, заполните следующие поля для перевода:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'transfer-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?php
        $id = Yii::$app->user->identity->id;
        $users = User::find()->where('id != :id', [':id' => $id])->all();
        $items = ArrayHelper::map($users, 'id', 'username');
    ?>

    <?= $form->field($model, 'to')->dropDownList($items) ?>

    <?= $form->field($model, 'sum')->widget(MaskedInput::className(), [
        'clientOptions' => [
            'alias' =>  'decimal',
            'digits' => 2,
            'min' => 0.01,
            'max' => $max_sum,
            'separator' => ','
        ],
    ]) ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Перевести', ['class' => 'btn btn-primary', 'name' => 'transfer-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php endif; ?>

</div>
