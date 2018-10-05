<?php


namespace app\models;

use Yii;
use yii\base\Model;

class Transfer extends Model
{
    public $to;
    public $sum;

    public function rules()
    {
        return [
            [['to', 'sum'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'to' => 'Кому',
            'sum' => 'Сумма перевода',
        ];
    }

}