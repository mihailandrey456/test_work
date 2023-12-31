<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

use backend\models\Apple;

class AppleController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'generate', 'delete', 'eat', 'shake-tree'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'generate' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find(),
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGenerate()
    {
        $count = rand(1, 10);
        $failedApplesCount = 0;
        for ($i = 0; $i < $count; $i++) {
            $apple = new Apple();
            if ($apple->save() === false) {
                $failedApplesCount++;
            }
        }

        Yii::$app->session->setFlash('success', "Сгенерировано яблок:" . ($count - $failedApplesCount) . ".");
        if ($failedApplesCount != 0) {
            Yii::$app->session->setFlash('warning', "Количество яблок, которые были не сохранены: $failedApplesCount.");
        }

        return $this->redirect(['apple/index']);
    }

    public function actionDelete($id)
    {
        $apple = Apple::findOne($id);
        if (!is_null($apple)) {
            $apple->delete();
            Yii::$app->session->setFlash('success', "Яблоко #$id было удалено из БД.");
        }

        return $this->redirect(['apple/index']);
    }

    public function actionEat($id, $precent)
    {
        $apple = Apple::findOne($id);
        if (!is_null($apple))
        {
            try {
                $apple->eat($precent);
                if ($apple->size == 0.0) {
                    Yii::$app->session->setFlash('success', "Яблоко #$id было съедено.");
                } else {
                    $apple->save();
                    Yii::$app->session->setFlash('success', "Яблоко #$id было откушено.");
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', "Невозможно съесть яблоко #$id. {$e->getMessage()}.");
            }

        }

        return $this->redirect(['apple/index']);
    }

    public function actionShakeTree($id)
    {
        $apple = Apple::findOne($id);
        if (!is_null($apple)) {
            try {
                $apple->fallToGround();
                $apple->save();
                Yii::$app->session->setFlash('success', "Яблоко #$id упало.");
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', "Невозможно потрясти дерево, чтобы уронить яблоко #$id. {$e->getMessage()}.");
            }

        }

        return $this->redirect(['apple/index']);
    }
}
