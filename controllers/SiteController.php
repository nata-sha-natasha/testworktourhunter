<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Login;
use app\models\User;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect('/user/index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Login();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['user/index']);
    }

}
