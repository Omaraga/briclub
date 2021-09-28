<?php


namespace backend\controllers;


use common\models\Actions;
use common\models\Tokens;
use common\models\User;
use common\models\WalletTypes;
use TheSeer\Tokenizer\Token;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;

class WalletsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(){
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => WalletTypes::find(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        $tokens_all = Tokens::find()->where(['wallet_type'=>[7,8]])->andWhere(['not in','user_id',1])->sum('balans');
        $tokens_main = Tokens::find()->where(['wallet_type'=>7])->andWhere(['not in','user_id',1])->sum('balans');
        $tokens_bonus = Tokens::find()->where(['wallet_type'=>8])->andWhere(['not in','user_id',1])->sum('balans');

        $all_buyed_tokens = Actions::find()->where(['type'=>55])->sum('tokens');
        $all_admin_tokens = Actions::find()->where(['type'=>63])->sum('tokens');
        $all_payed_tokens = Actions::find()->where(['type'=>[58,59,60,61,67,68,71,65]])->sum('tokens');
        $all_support_tokens = Actions::find()->where(['type'=>60])->sum('tokens');
        $all_besroit_tokens = Actions::find()->where(['type'=>68])->sum('tokens');
        $all_greenswop_tokens = Actions::find()->where(['type'=>67])->sum('tokens');
        $all_fee_tokens = Actions::find()->where(['type'=>[58,59,61]])->sum('tokens');



        $main_balans = Tokens::find()->where(['user_id'=>1,'wallet_type'=>6])->one();
        $main_balans->balans = 500000 - $all_buyed_tokens;
        $main_balans->save();

        $fee_balans = Tokens::find()->where(['user_id'=>1,'wallet_type'=>5])->one();
        $fee_balans->balans = $all_fee_tokens;
        $fee_balans->save();

        $support_balans = Tokens::find()->where(['user_id'=>1,'wallet_type'=>4])->one();
        $support_balans->balans = $all_support_tokens;
        $support_balans->save();

        $besroit_balans = Tokens::find()->where(['user_id'=>1,'wallet_type'=>2])->one();
        $besroit_balans->balans = $all_besroit_tokens;
        $besroit_balans->save();

        $greenswop_balans = Tokens::find()->where(['user_id'=>1,'wallet_type'=>3])->one();
        $greenswop_balans->balans = $all_greenswop_tokens;
        $greenswop_balans->save();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'tokens_all' => $tokens_all,
            'tokens_main' => $tokens_main,
            'tokens_bonus' => $tokens_bonus,
            'all_buyed_tokens' => $all_buyed_tokens,
            'all_admin_tokens' => $all_admin_tokens,
            'all_payed_tokens' => $all_payed_tokens,
        ]);
    }
    public function actionReports(){
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }


        $tokens_all = Tokens::find()->where(['wallet_type'=>[7,8]])->sum('balans');
        $tokens_main = Tokens::find()->where(['wallet_type'=>7])->sum('balans');
        $tokens_bonus = Tokens::find()->where(['wallet_type'=>8])->sum('balans');

        $all_buyed_tokens = Actions::find()->where(['type'=>55])->sum('tokens');
        $all_admin_tokens = Actions::find()->where(['type'=>63])->sum('tokens');
        $all_payed_tokens = Actions::find()->where(['type'=>[58,59,60,61,67,68,71,65]])->sum('tokens');
        $all_buyed_tokens_sum = Actions::find()->where(['type'=>55])->all();


        /*foreach ($all_buyed_tokens_sum as $item) {
            if($item['tokens'] == 0){
                $item->tokens = $item['sum']/10;
                $item->save();
            }

        }*/

        /*$tokens = Tokens::find()->all();
        foreach ($tokens as $token) {
            echo "<br>";
            echo $token['user_id'];
            echo " ";
            echo User::findOne($token['user_id'])['username'];
            echo "<br>";
            echo Tokens::checkTokenBalans($token['user_id']);
        }*/


        return $this->render('reports', [
            'tokens_all' => $tokens_all,
            'tokens_main' => $tokens_main,
            'tokens_bonus' => $tokens_bonus,
            'all_buyed_tokens' => $all_buyed_tokens,
            'all_admin_tokens' => $all_admin_tokens,
            'all_payed_tokens' => $all_payed_tokens,
        ]);
    }
}