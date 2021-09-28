<?php
namespace frontend\controllers;

use common\models\Confirms;
use common\models\Courses;
use common\models\Exceptions;
use common\models\Lessons;
use common\models\MatrixRef;
use common\models\MatrixStart;
use common\models\Messages;
use common\models\MLevelsNew;
use common\models\MLevelsStart;
use common\models\Referals;
use common\models\ShanyrakBeds;
use common\models\ShanyrakInfo;
use common\models\ShanyrakUser;
use common\models\ShanyrakUserPays;
use common\models\Tickets;
use common\models\UserLessons;
use frontend\models\forms\Doc3Form;
use frontend\models\forms\MessageForm;
use frontend\models\forms\ShanpayForm;
use frontend\models\forms\ShanyrakBedForm;
use frontend\models\forms\TicketForm;
use frontend\models\forms\TransfersForm;
use common\models\Actions;
use common\models\UserCourses;
use common\models\User;
use common\models\UserPlatforms;
use common\models\Withdraws;
use http\Client;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class ShanyrakController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','info','start','activ','doc3','pay','program','payment','calculator'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        $programs = ShanyrakInfo::find()->all();
        return $this->render('index',[
            'programs' => $programs,
            'user' => $user,
        ]);
    }

    public function actionInfo()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $programs = ShanyrakInfo::find()->all();
        return $this->render('info',[
            'programs' => $programs
        ]);
    }

    public function actionStart($program=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        if(Yii::$app->request->post()){
            $model = new ShanyrakBedForm();
            if($model->load(Yii::$app->request->post())){
                $rooms = $model->rooms;
                $term1 = $model->term1;
                $term2 = $model->term2;
                if($program == 5){
                    if($rooms == 1){
                        $sum = $model->sum_kv_all_1;
                        $first = 2500;
                    }elseif($rooms == 2){
                        $sum = $model->sum_kv_all_2;
                        $first = 3500;
                    }elseif($rooms == 3){
                        $sum = $model->sum_kv_all_3;
                        $first = 5000;
                    }
                    $model->sum_month_1 = round(($sum/2 - $first)/$term1);
                    $model->sum_month_2 = round(($sum/2)/$term2);
                }elseif($program == 6){
                    if($rooms == 1){
                        $sum = $model->sum_auto_all_1;
                        $first = 2500;
                    }elseif($rooms == 2){
                        $sum = $model->sum_auto_all_2;
                        $first = 3500;
                    }elseif($rooms == 3){
                        $sum = $model->sum_auto_all_3;
                        $first = 4500;
                    }elseif($rooms == 4){
                        $sum = $model->sum_auto_all_3;
                        $first = 7500;
                    }
                    $model->sum_month_1 = round(($sum/2 - $first)/$term1);
                    $model->sum_month_2 = round(($sum/2)/$term2);
                }elseif($program == 7){
                    $sum = $model->sum_tech_all_1;
                    $first = 500;
                    $model->sum_month_1 = round((($sum*5)/10 - $first)/$term1);
                    $model->sum_month_2 = round((($sum*5)/10)/$term2);
                }



                $model->sum_first = $first;


                $model->sum_all = $sum;

                $file = UploadedFile::getInstance($model, 'file');
                $link = null;
                if ($file && $file->tempName) {
                    $model->file = $file;
                    if ($model->validate(['file'])) {

                        $rand = rand(900000,9000000);
                        $dir = Yii::getAlias('@frontend/web/docs/shanyrak/');
                        $dir2 = Yii::getAlias('docs/shanyrak/');
                        $fileName = $rand . '.' . $model->file->extension;
                        $model->file->saveAs($dir . $fileName);
                        $model->file = $fileName; // без этого ошибка
                        $link = '/'.$dir2 . $fileName;
                    }
                }
                $model->doc1 = $link;


                $shan = new ShanyrakBeds();
                $shan = $model;
                $shan->save();

                $program = ShanyrakInfo::findOne($model->program_id);
                $shanyrak_user = ShanyrakUser::find()->where(['user_id'=>$user['id'],'program_id'=>$program['id']])->one();
                $shanyrak_user->step = 2;
                $shanyrak_user->save();

                return $this->redirect('/shanyrak/start?program='.$model->program_id);
            }
        }
        //if($user['global'] == 1 and $user['newmatrix'] == 1){

            $program = ShanyrakInfo::findOne($program);
            $shanyrak_user = ShanyrakUser::find()->where(['user_id'=>$user['id'],'program_id'=>$program['id']])->one();
            $step = 1;
            $status = 2;
            if(!empty($shanyrak_user)){
                $step = $shanyrak_user['step']+1;
                $status = $shanyrak_user['status'];
            }
            if($status == 2){
                return $this->render('start',[
                    'step'=>$step,
                    'program'=>$program,
                    'user'=>$user,
                    'status'=>$status,
                    'price'=>$program['price'],
                ]);
            }else{
                return $this->redirect('/shanyrak/program?id='.$program['id']);
            }

        /*}else{
            Yii::$app->session->setFlash('danger', 'Активируйте программы Personal и Global чтобы участвовать в Shanyrak!');
            return $this->redirect('/shanyrak');
        }*/
    }

    public function actionCalculator($program=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;

            $program = ShanyrakInfo::findOne($program);

        return $this->render('calculator',[
            'program'=>$program,
            'user'=>$user,
            'price'=>$program['price'],
        ]);

    }

    public function actionProgram($id=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        $user_shanyrak = ShanyrakUser::find()->where(['user_id'=>$user['id'],'program_id'=>$id])->one();
        if(empty($user_shanyrak)){
            return $this->redirect('/shanyrak');
        }
        return $this->render('program',[
            'user'=>$user,
            'program'=>$id,
        ]);
    }

    public function actionDoc3()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        if(Yii::$app->request->post()){
            $model = new Doc3Form();
            if($model->load(Yii::$app->request->post())){
                /*echo "<pre>";
                var_dump($model);
                exit;*/
                $file = UploadedFile::getInstance($model, 'file');
                $link = null;

                    $model->file = $file;
                    if ($model->validate(['file'])) {

                        $rand = rand(900000,9000000);
                        $dir = Yii::getAlias('@frontend/web/docs/shanyrak/');
                        $dir2 = Yii::getAlias('docs/shanyrak/');
                        $fileName = $rand . '.' . $model->file->extension;
                        $model->file->saveAs($dir . $fileName);
                        $model->file = $fileName; // без этого ошибка
                        $link = '/'.$dir2 . $fileName;
                    }


                $bed = ShanyrakBeds::findOne($model->bed_id);
                $bed->doc3 = $link;
                $bed->status = 2;
                $bed->save();

                $shanyrak_user = ShanyrakUser::find()->where(['user_id'=>$bed['user_id'],'program_id'=>$bed['program_id']])->one();
                $shanyrak_user->step = 3;
                $shanyrak_user->save();

                return $this->redirect('/shanyrak/start?program='.$bed['program_id']);
            }
        }
    }

    public function actionActiv($program=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->identity['id']);
        if($program){
            if($program == 5 or $program == 6 or $program == 7){
                $prog = ShanyrakInfo::findOne($program);
                $price = $prog['price'];
                if($user->w_balans >= $price){
                    $shanyrak_user = ShanyrakUser::find()->where(['user_id'=>$user['id'],'program_id'=>$program])->one();
                    if(empty($shanyrak_user)){
                        $user->w_balans = $user->w_balans - $price;
                        $user->save();


                        $shanyrak_user = new ShanyrakUser();
                        $shanyrak_user->user_id = $user['id'];
                        $shanyrak_user->program_id = $program;
                        $shanyrak_user->time = time();
                        $shanyrak_user->step = 1;
                        $shanyrak_user->status = 2;
                        $shanyrak_user->save();


                        $action = new Actions();
                        $action->time = time();
                        $action->type = 97;
                        $action->status = 1;
                        $action->sum = $price;
                        $action->title = "Вы активировали программу Shanyrak ".($prog['title']);
                        $action->user_id = $user['id'];
                        $action->save();
                        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Cell Account успешно активирован!'));
                        return $this->redirect('/shanyrak/start?program='.$program);
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Недостаточно средств на балансе!'));
                    return $this->redirect('/shanyrak/start?program='.$program);
                }

            }
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
            return $this->redirect('/profile');
        }
    }

    public function actionPay($pay_id=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->identity['id']);
        if($pay_id){
                $shanyrak_user_pay = ShanyrakUserPays::findOne($pay_id);
                $price = $shanyrak_user_pay['sum_need'];
                $program = $shanyrak_user_pay['program_id'];
                if($user->w_balans >= $price){
                    $shanyrak_user = ShanyrakUser::find()->where(['user_id'=>$user['id'],'program_id'=>$program])->one();
                    if(!empty($shanyrak_user)){

                        $shanyrak_user_pay->time_pay = time();
                        $shanyrak_user_pay->time_need = time();
                        $shanyrak_user_pay->sum_pay = $shanyrak_user_pay->sum_need;
                        $shanyrak_user_pay->status = 1;
                        $shanyrak_user_pay->save();

                        $user->w_balans = $user->w_balans - $price;
                        $user->save();

                        if($shanyrak_user_pay['type'] == 1){
                            $shanyrak_user = ShanyrakUser::findOne($shanyrak_user_pay['user_shanyrak_id']);
                            $shanyrak_user->status = 1;
                            $shanyrak_user->save();

                            $user_bed = ShanyrakBeds::findOne($shanyrak_user_pay->bed_id);
                            $months = $user_bed['term1'] + $user_bed['term2'];
                            $prev_time = $shanyrak_user_pay->time_pay;
                            for ($i=1;$i<$months+1;$i++){
                                $user_pay = new ShanyrakUserPays();
                                $user_pay->user_id = $user['id'];
                                $user_pay->program_id = $program;
                                $user_pay->bed_id = $shanyrak_user_pay->bed_id;
                                $user_pay->user_shanyrak_id = $shanyrak_user_pay->user_shanyrak_id;

                                $day = date("d",$prev_time);
                                $month = date("m",$prev_time);
                                $year = date("Y",$prev_time);



                                $year_cur = $year;
                                $day_cur = $day;
                                if($month < 12){
                                    $month_cur = $month + 1;
                                }else{
                                    $month_cur = 1;
                                    $year_cur = $year + 1;
                                }

                                $user_pay->time_need = strtotime($day_cur.".".$month_cur.".".$year_cur);
                                $prev_time = $user_pay->time_need;
                                $user_pay->type = 2;
                                $user_pay->status = 2;
                                if($i>$user_bed['term1']){
                                    $user_pay->sum_need = $user_bed['sum_month_2'];
                                }else{
                                    $user_pay->sum_need = $user_bed['sum_month_1'];
                                }

                                $user_pay->save();

                            }
                        }

                        $action = new Actions();
                        $action->time = time();
                        $action->type = 98;
                        $action->status = 1;
                        $action->sum = $price;
                        $action->title = "Вы внесли платеж по программе Shanyrak ";
                        $action->user_id = $user['id'];
                        $action->save();
                        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Платеж принят!'));
                        return $this->redirect('/shanyrak/start?program='.$program);
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Недостаточно средств на балансе!'));
                    return $this->redirect('/shanyrak/start?program='.$program);
                }

        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
            return $this->redirect('/shanyrak');
        }
    }

    public function actionPayment($pay_id=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->identity['id']);
        if($pay_id){

            $pay = ShanyrakUserPays::findOne($pay_id);
            $model = new ShanpayForm();
            if($model->load(Yii::$app->request->post())){
                if($model->sum >=$pay['sum_need']){
                    if($user['w_balans']>=$model->sum){
                        $user->w_balans = $user->w_balans - $model->sum;
                        $pay->status = 1;
                        $pay->time_pay = time();
                        $pay->sum_pay = $model->sum;
                        $pay->save();
                        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Вы успешно оплатили ежемесяный платеж!'));
                        return $this->redirect('/shanyrak/program?id='.$pay['program_id']);
                    }else{
                        Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Недостаточно средств на балансе!'));
                        return $this->redirect('/shanyrak/program?id='.$pay['program_id']);
                    }
                }
            }
            return $this->render('payment',[
                'model' => $model,
                'pay' => $pay,
            ]);
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
            return $this->redirect('/shanyrak');
        }

    }

}
