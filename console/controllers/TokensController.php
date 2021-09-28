<?php

namespace console\controllers;

use common\models\Actions;
use common\models\TokenHarvests;
use common\models\TokenNodes;
use common\models\TokenNodeTypes;
use common\models\Tokens;
use common\models\User;
use yii\console\Controller;

/**
 * TicketsController implements the CRUD actions for Tickets model.
 */
class TokensController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function actionIndex()
    {
        $start = strtotime('yesterday');
        $start = strtotime('today');
        $end = strtotime('today');
        $end = strtotime('tomorrow');

        $current_harvest = TokenHarvests::find()->where(['>=','time',$start])->andWhere(['<','time',$end])->one();
        if(empty($current_harvest)){

            $all_tokens = 0;
            $fees = Actions::find()->where(['type'=>[58,59,60,61]])->andWhere(['>=','time',$start])->andWhere(['<','time',$end])->all();
            foreach ($fees as $fee) {
                $all_tokens += $fee['tokens'];
            }
            $super_node_percent = TokenNodeTypes::findOne(1)['percent'];
            $node_percent = TokenNodeTypes::findOne(2)['percent'];
            $pull_node_percent = TokenNodeTypes::findOne(3)['percent'];

            $super_node_sum = $all_tokens*$super_node_percent/100;
            $node_sum = $all_tokens*$node_percent/100;
            $pull_node_sum = $all_tokens*$pull_node_percent/100;

            $super_node_all = 0;
            $node_all = 0;
            $pull_node_all = 0;

            $nodes = TokenNodes::find()->all();
            foreach ($nodes as $node) {
                if($node['type'] == 1){
                    $super_node_all += $node['tokens'];
                }
                if($node['type'] == 2){
                    $node_all += $node['tokens'];
                }
                if($node['type'] == 3){
                    $pull_node_all += $node['tokens'];
                }
            }

            $nodes = TokenNodes::find()->where(['type'=>2])->all();
            foreach ($nodes as $node) {
                $user = User::findOne($node['user_id']);
                if(!empty($user)){
                    $user_tokens = Tokens::findOne(['user_id'=>$user['id']]);
                    if(empty($user_tokens)){
                        $user_tokens = new Tokens();
                        $user_tokens->user_id = $user['id'];
                        $user_tokens->save();
                    }
                    if(!empty($user_tokens)){
                        $user_tokens['balans'] = $user_tokens['balans'] + $node_sum*$node['tokens']/$node_all;
                        $action2 = new Actions();
                        $action2->type = 62;
                        $action2->title = "Вознаграждение для ноды в GRC ";
                        $action2->user_id = $node['user_id'];
                        $action2->time = time();
                        $action2->status = 1;
                        $action2->tokens = $node_sum*$node['tokens']/$node_all;
                        $action2->save();
                    }
                }
            }
            $super_nodes = TokenNodes::find()->where(['type'=>1])->all();
            foreach ($super_nodes as $node) {
                $user = User::findOne($node['user_id']);
                if(!empty($user)){
                    $user_tokens = Tokens::findOne(['user_id'=>$user['id']]);
                    if(empty($user_tokens)){
                        $user_tokens = new Tokens();
                        $user_tokens->user_id = $user['id'];
                        $user_tokens->save();
                    }
                    if(!empty($user_tokens)){
                        $user_tokens['balans'] = $user_tokens['balans'] + $super_node_sum*$node['tokens']/$super_node_all;
                        $action2 = new Actions();
                        $action2->type = 62;
                        $action2->title = "Вознаграждение для ноды в GRC ";
                        $action2->user_id = $node['user_id'];
                        $action2->time = time();
                        $action2->status = 1;
                        $action2->tokens = $super_node_sum*$node['tokens']/$super_node_all;
                        $action2->save();
                    }
                }
            }
            $harvest = new TokenHarvests();
            $harvest->time = time();
            $harvest->all_sum = $all_tokens;
            $harvest->save();
        }
    }

}
