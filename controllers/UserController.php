<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Transfer;
use app\models\Transaction;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'transfer', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTransfer()
    {
        $model = new Transfer();

        if ($model->load(Yii::$app->request->post())) {
            $id = Yii::$app->user->identity->id;
            $userFrom = $this->findModel($id);
            $userTo = $this->findModel($model->to);
            $userFrom->balance -= $model->sum;
            $userTo->balance += $model->sum;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $userFrom->save();
                $userTo->save();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
            }
            return $this->redirect(['index']);
        }

        return $this->render('transfer', [
            'model' => $model
        ]);
    }

    public function actionTransaction()
    {
        $id = Yii::$app->user->identity->id;
        $from = Transaction::find()->where(['from' => $id])->all();
        $to = Transaction::find()->where(['to' => $id])->all();

        return $this->render('transaction', [
            'from' => $from,
            'to' => $to
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
