<?php

namespace backend\controllers;
use common\models\MatrixRef;
use common\models\User;
use yii\rest\Controller;
use Yii;
class RestController extends Controller
{

    public function actionMatrix(){

        $this->enableCsrfValidation = false;
        if (Yii::$app->request->isPost){
            $username = Yii::$app->request->post('username');
            $level = Yii::$app->request->post('level');
            if (!$level){
                $level = 1;
            }
            $user = User::find()->where(['username' => $username])->one();
            if (!$user){
                return json_encode(['error' => 'Не найден пользователь']);
            }
            $matrixRef = MatrixRef::find()->where(['user_id'=> $user->id, 'platform_id' => $level])->orderBy('id ASC')->one();
            if (!$matrixRef){
                return json_encode(['error' => 'Матрица не найдена']);
            }
            $response = ['username' => $user->username, 'matrixId' => $matrixRef->id];
            return json_encode($response);
        }
    }
    public function actionMatrixChild(){
        $this->enableCsrfValidation = false;
        if (Yii::$app->request->isPost){
            $id = Yii::$app->request->post('id');
            $matrixRef = MatrixRef::findOne($id);
            if ($matrixRef->shoulder1){
                $left = MatrixRef::findOne($matrixRef->shoulder1);
                $leftUser = User::findOne($left->user_id);
                $leftUsername = $leftUser->username;
                $leftId = $left->id;
            }else{
                $left = null;
                $leftUser = null;
                $leftUsername = null;
                $leftId = null;
            }
            if ($matrixRef->shoulder2){
                $right = MatrixRef::findOne($matrixRef->shoulder2);
                $rightUser = User::findOne($right->user_id);
                $rightUsername = $rightUser->username;
                $rightId = $right->id;
            }else{
                $right = null;
                $rightUser = null;
                $rightUsername = null;
                $rightId = null;
            }
            $response = ['username' => $user->username, 'matrixId' => $matrixRef->id, 'children' => ['left' => ['username' => $leftUsername, 'matrixId' => $leftId], 'right' => ['username' => $rightUsername, 'matrixId' => $rightId]]];
            return json_encode($response);
        }
    }

}