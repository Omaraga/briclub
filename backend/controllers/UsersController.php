<?php

namespace backend\controllers;

use app\models\Activities;
use app\models\Parents;
use app\models\UserInfo;
use backend\models\ResetPasswordForm;
use common\models\ActionTypes;
use common\models\MatClons;
use common\models\MatParents;
use common\models\UserEmails;
use common\models\Actions;
use common\models\Acts;
use common\models\Confirms;
use common\models\EventTickets;
use common\models\EventTicketTypes;
use common\models\HarvestTime;
use common\models\MatrixRef;
use common\models\UserCourses;
use common\models\Withdraws;
use kartik\mpdf\Pdf;
use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
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
        ];
    }

    public function actionGiveticket($user_id,$type_id){

        if($type_id == 1){
            $tokens = 115;
        }elseif ($type_id == 2){
            $tokens = 65;
        }elseif ($type_id == 3){
            $tokens = 45;
        }elseif ($type_id == 4){
            $tokens = 15;
        }
        $user = User::findOne($user_id);
        $background = 'ticket'.$type_id;
        $ticket = new EventTickets();
        $ticket->user_id = $user_id;
        $ticket->event_id = 1;
        $ticket->type_id = $type_id;
        $ticket->time = time();
        $ticket->status = 1;
        $ticket->save();

        $ticket_type = EventTicketTypes::findOne($type_id);
        $ticket_type->count = $ticket_type->count - 1;
        $ticket_type->save();

        if(!empty($ticket)){
            $content = $this->renderPartial('/events/cert',[
                'id'=>$ticket['id'],
                'name'=>$user['username']
            ]);

            $pdf = new Pdf([
                // set to use core fonts only
                'mode' => Pdf::MODE_CORE,
                // A4 paper format
                'format' => Pdf::FORMAT_A4,
                // portrait orientation
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                // stream to browser inline
                'destination' => Pdf::DEST_BROWSER,
                // your html content input
                'content' => $content,
                'cssFile' => '@frontend/web/css/cert.css',
                'mode' => Pdf::MODE_UTF8,
                'marginLeft' =>0,
                'marginRight' =>0,
                'marginTop' =>0,
                'marginBottom' =>0,
            ]);
            //return $pdf->render();
            $rand = rand(900000,9000000);
            $dir = Yii::getAlias('@frontend/web/tickets/');
            $fileName = $rand . '.pdf';
            $mpdf = $pdf->api;
            $stylesheet = "
                                    .cr-content{
                                        overflow: hidden;
                                        height: 100%;
                                        width: 100%;
                                        background: url(\"/img/".$background.".jpg\") no-repeat;
                                        background-size: contain;
                                        background-position: center;
                                        position: relative;
                                    }
                                    .cr-name{
                                        
                                        padding-left: 980px!important;
                                        font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                                        font-weight: lighter;
                                        color: #fff;
                                        display: block;
                                        font-size: 16px;
                                    }
                                    .cr-id{
                                        padding-top: 230px!important;
                                        padding-left: 540px!important;
                                        text-align: center;
                                        font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                                        font-weight: lighter;
                                        color: #fff;
                                        display: block;
                                        font-size: 36px;
                                    }
                                    .cr-id2{
                                        padding-top: 300px!important;
                                        padding-left: 1000px!important;
                                        font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                                        font-weight: lighter;
                                        color: #fff;
                                        display: block;
                                        font-size: 36px;
                                    }
                                    
                                ";
            $mpdf->WriteHtml($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHtml($content);
            $mpdf->Output($dir . $fileName); // call the mpdf api output as needed

            $link = '/tickets/'. $fileName;
            $ticket->link = $link;
            $ticket->save();

            $action = new Actions();
            $action->type = 300;
            $action->title = "Покупка билета на мероприятие";
            $action->user_id = $user_id;
            $action->time = time();
            $action->status = 1;
            $action->content = $link;
            $action->tokens = $tokens;
            $action->comment = "Покупка билета на мероприятие";
            $action->save();

            Acts::sendEmail($user['id'],$link,1);
            Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Билет создан'));
            return $this->redirect('/users/view?id='.$user_id);

        }
    }
    public function actionEvents(){
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => EventTickets::find(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);
        $dataProvider1 = new ActiveDataProvider([
            'query' => EventTickets::find()->where(['type_id'=>1]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        $dataProvider2 = new ActiveDataProvider([
            'query' => EventTickets::find()->where(['type_id'=>2]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        $dataProvider3 = new ActiveDataProvider([
            'query' => EventTickets::find()->where(['type_id'=>3]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        $dataProvider4 = new ActiveDataProvider([
            'query' => EventTickets::find()->where(['type_id'=>4]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);


        return $this->render('events',[
            'dataProvider' => $dataProvider,
            'dataProvider1' => $dataProvider1,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
            'dataProvider4' => $dataProvider4,
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex($username = null,$email=null,$phone=null,$fio=null,$platform=null,$from=null,$to=null,$newmatrix=null,$countr=null)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }


        $user = null;
        $userinfo = null;
        $sponsor = null;
        $activities = null;
        $error = null;
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['not in','id',1]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        /*
        if(!empty($from)){
            $user = \common\models\User::find()->where(['username'=>$username])->one();
            $time = strtotime($from);
            if(!empty($to)){
                $time2 = strtotime($to);
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','created_at',$time])->andWhere(['<=','created_at',$time2]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','created_at',$time]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }
        }
        if(!empty($to)){
            $user = \common\models\User::find()->where(['username'=>$username])->one();
            $time2 = strtotime($to);
            if(!empty($from)){
                $time = strtotime($from);
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','created_at',$time])->andWhere(['<=','created_at',$time2]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['<=','created_at',$time2]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }
        }*/
        if(!empty($from)){
            $user = \common\models\User::find()->where(['username'=>$username])->one();
            $time = strtotime($from);
            if(!empty($to)){
                $time2 = strtotime($to);
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','time_personal',$time])->andWhere(['<=','time_personal',$time2]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','time_personal',$time]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }
        }
        if(!empty($to)){
            $user = \common\models\User::find()->where(['username'=>$username])->one();
            $time2 = strtotime($to);
            if(!empty($from)){
                $time = strtotime($from);
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','time_personal',$time])->andWhere(['<=','time_personal',$time2]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['<=','time_personal',$time2]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }
        }
        if(!empty($username)){
            $user = \common\models\User::find()->where(['username'=>$username])->one();

            if(empty($user)){
                $username = $user['username'];
                $error = "Такого пользователя нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['username'=>$user['username']]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }

        }
        if(!empty($platform)){
            if($platform == 11){
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['activ'=>null]),
                ]);
            }elseif($platform == 12){
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['activ'=>1]),
                ]);
            }else{
                $mats = MatrixRef::find()->where(['platform_id'=>$platform])->all();
                $users = [];
                foreach ($mats as $mat) {
                    if(!in_array($mat['user_id'],$users)){
                        $users[] = $mat['user_id'];
                    }
                }
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['id'=>$users]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }

        }
        if(!empty($countr)){
            if($countr == null){
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find(),
                ]);
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['country_id'=>$countr]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }

        }
        if(!empty($email)){
            $user = \common\models\User::find()->where(['email'=>$email])->one();

            if(empty($user)){
                $email = $user['email'];
                $error = "Такого email-а нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['email'=>$user['email']]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }

        }
        if(!empty($newmatrix)){

            $dataProvider = new ActiveDataProvider([
                'query' => User::find()->where(['newmatrix'=>$newmatrix]),
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ]
                ],
            ]);


        }
        if(!empty($phone)){
            $user = \common\models\User::find()->where(['phone'=>$phone])->one();

            if(empty($user)){
                $phone = $user['phone'];
                $error = "Такого телефона нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['phone'=>$user['phone']]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }

        }
        if(!empty($fio)){
            $user = \common\models\User::find()->where(['fio'=>$fio])->one();

            if(empty($user)){
                $fio = $user['fio'];
                $error = "Такого ФИО нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['fio'=>$user['fio']]),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                ]);
            }

        }

        return $this->render('index',[
            'newmatrix' =>$newmatrix,
            'username' =>$username,
            'email' =>$email,
            'user' => $user,
            'fio' => $fio,
            'phone' => $phone,
            'from' => $from,
            'to' => $to,
            'userinfo' => $userinfo,
            'activities' => $activities,
            'platform' => $platform,
            'sponsor' => $sponsor,
            'error' => $error,
            'countr' => $countr,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCloses($username = null,$email=null,$phone=null,$fio=null,$platform=null,$from=null,$to=null,$newmatrix=null)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }


        $user = null;
        $userinfo = null;
        $sponsor = null;
        $activities = null;
        $error = null;
        $query = User::find()->where(['not in','id',1]);

		if ($from && $to && $platform){
            $mat = MatrixRef::find()->select('id, shoulder2_2, user_id')->where(['platform_id' => $platform])
                ->andWhere(['!=', 'shoulder2_2', 'null'])
                ->asArray()->all();
            $arr = [];
            $arr2 = [];
            foreach ($mat as $item){
                $arr[$item['shoulder2_2']] = $item['user_id'];
                $arr2[] = $item['shoulder2_2'];
            }
            $list = MatrixRef::find()->select('id')->where(['id'=>$arr2])->andWhere(['>=', 'time', strtotime($from)])->andWhere(['<=', 'time', strtotime($to)])->asArray()->all();
            $userIds = [];
            foreach ($list as $item){
                $userIds[] = $arr[$item['id']];
            }
            $query->andWhere(['id' => $userIds])->andWhere(['>=', 'created_at', strtotime($from)])->andWhere(['<=', 'created_at', strtotime($to)]);

        }
		
//        if(!empty($from)){
//            $time = strtotime($from);
////            $query = $query->andWhere(['>=','time_personal',$time]);
//            $query = $query->andWhere(['>=','created_at',$time]);
//        }
//        if(!empty($to)){
//            $time2 = strtotime($to);
////            $query = $query->andWhere(['<=','time_personal',$time2]);
//            $query = $query->andWhere(['<=','created_at',$time2]);
//        }
//
//

//        if(!empty($platform)){
//            if($platform == 11){
//                $query = $query->andWhere(['activ'=>null]);
//            }else{
//                $mats = MatrixRef::find()->where(['platform_id'=>$platform,'slots'=>4])->all();
//                $users = [];
//                foreach ($mats as $mat) {
//                    if(!in_array($mat['user_id'],$users)){
//                        $ex_mat = MatrixRef::findOne(['platform_id' => $platform - 1, 'user_id' => $mat['user_id']]);
//                        if($ex_mat['shoulder1_1'] != null && $ex_mat['shoulder1_2'] != null
//                        && $ex_mat['shoulder2_1'] != null && $ex_mat['shoulder2_2'] != null){
//
//                            if($mat['time'] > ){
//                                $users[] = $mat['user_id'];
//                            }
//                        }
//                    }
//                }
//                $query = $query->andWhere(['id'=>$users]);
//            }
//
//        }



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time_personal' => SORT_DESC,
                ]
            ],
        ]);


        return $this->render('closes',[
            'newmatrix' =>$newmatrix,
            'username' =>$username,
            'email' =>$email,
            'user' => $user,
            'fio' => $fio,
            'phone' => $phone,
            'from' => $from,
            'to' => $to,
            'userinfo' => $userinfo,
            'activities' => $activities,
            'platform' => $platform,
            'sponsor' => $sponsor,
            'error' => $error,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCountries($country=null,$from=null,$to=null)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }


        $user = null;
        $userinfo = null;
        $sponsor = null;
        $activities = null;
        $error = null;
        $query = User::find()->where(['activ'=>1]);

        if(!empty($from)){
            $time = strtotime($from);
            $query = $query->andWhere(['>=','time_personal',$time]);
        }
        if(!empty($to)){
            $time2 = strtotime($to);
            $query = $query->andWhere(['<=','time_personal',$time2]);
        }

        if(!empty($country)){
            if($country == 1111){
                $query = $query->andWhere(['activ'=>1]);
            }else{
                $query = $query->andWhere(['country_id'=>$country]);
            }
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time_personal' => SORT_DESC,
                ]
            ],
        ]);


        return $this->render('countries',[
            'from' => $from,
            'to' => $to,
            'country' => $country,
            'error' => $error,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionProfit()
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return $this->render('profit', [

        ]);
    }

	public function actionTestProfit(){
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        $users = User::find()->where(['activ'=>1])->all();

                $time = 1626480000;

        $first_profit = \common\models\MatrixRef::find()->where(['platform_id'=>1,'reinvest'=>0])->andWhere(['>', 'time', $time])->count() * 3;
        $fourth_profit = \common\models\MatrixRef::find()->where(['platform_id'=>4,'slots'=>1])->andWhere(['>', 'time', $time])->count() * 100;
        $fifth_profit = \common\models\MatrixRef::find()->where(['platform_id'=>5,'slots'=>1])->andWhere(['>', 'time', $time])->count() * 400;


        $sixth_profit = \common\models\MatrixRef::find()->where(['platform_id'=>6,'slots'=>1])->andWhere(['>', 'time', $time])->count() * 800;
        $sixth_profit += \common\models\MatrixRef::find()->where(['platform_id'=>6,'slots'=>3])->andWhere(['>', 'time', $time])->count() * 16200;
        $sixth_profit += \common\models\MatrixRef::find()->where(['platform_id'=>6,'slots'=>4])->andWhere(['>', 'time', $time])->count() * 16200;



        $all_profit = $first_profit + $fourth_profit + $fifth_profit + $sixth_profit;




        return $this->render('test-profit', [
            'first_profit' => $first_profit,
            'fourth_profit' => $fourth_profit,
            'fifth_profit' => $fifth_profit,
            'sixth_profit' => $sixth_profit,
            'all_profit' => $all_profit,
        ]);
    }
	
    public function actionProfitmain()
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return $this->render('profitmain', [

        ]);
    }

    public function actionProfituser()
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return $this->render('profituser', [

        ]);
    }

    public function actionView($id)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionBinar($id)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return $this->render('binar', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionInfo($id,$from=null,$to=null)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        $error = null;
        $time_start = time();
        $time_end = time();
        if(!empty($from)){
            $time_start = strtotime($from);
        }
        if(!empty($to)){

            $time_end = strtotime($to);

        }
        return $this->render('info', [
            'model' => $this->findModel($id),
            'from' => $from,
            'to' => $to,
            'error' => $error,
            'time_start' => $time_start,
            'time_end' => $time_end,
        ]);
    }

    public function actionBlock($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = $this->findModel($id);
        if($user['block'] == 1){
            $user->block = 2;
        }else{
            $user->block = 1;
        }
        $user->save();
        return $this->redirect('/users/view?id='.$user['id']);
    }
    public function actionAccess($user_id,$course_id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user_course = UserCourses::find()->where(['user_id'=>$user_id,'course_id'=>$course_id])->one();
        if(!empty($user_course)){
            $user_course->delete();
        }else{
            $user_course = new UserCourses();
            $user_course->user_id = $user_id;
            $user_course->course_id = $course_id;
            $user_course->date = time();
            $user_course->save();
        }
        return $this->redirect('/users/view?id='.$user_id);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
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
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::findOne($model->id);
            if(!empty($model->email)){
				
				$old_email = new UserEmails();
        		$old_email->user_id = $user->id;
        		$old_email->old_email = $user->email;
        		$old_email->new_email = $model->email;
        		$old_email->username = $user->username;
				$old_email->time = time();
        		$old_email->save(false);
				
                $user->email = $model->email;
            }
            if(!empty($model->password)){
                $user->setPassword($model->password);
                $user->generateAuthKey();
            }

            $user->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'id' => $id,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionConfirm($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $confirm = Confirms::find()->where(['user_id'=>$id])->one();
        if(empty($confirm)){
            $confirm = new Confirms();
            $confirm->user_id = $id;
            $confirm->status = 1;
            $confirm->save();
        }

        return $this->redirect(['view', 'id' => $id]);
    }

	
	public function actionAddBonus(){

        $last_harvest = HarvestTime::findOne(1);

        if(time() - $last_harvest->time >= 86400){

            $user = User::findOne(26674);
            $user->w_balans += 14.75;
            $user->save(false);

            $last_harvest->time = time();
            $last_harvest->save();

            $action = new Actions();
            $action->type = 5;
            $action->user_id = 26674;
            $action->time = time();
            $action->status = 1;
            $action->sum = 14.75;
            $action->comment = "Дивиденты за стройку";
			$action->title = "Пополнение администратором";
            $action->save();
        }
    }
	
	public function actionCancelDeposit($id){
        $user = User::findOne($id);
        $action = Actions::find()->where(['type' => 5, 'user_id' => $user->id])->orderBy(['id' => SORT_DESC])->one();
        if($action){
            $user->w_balans -= $action->sum;
            $user->save();
            $action->delete();
            Yii::$app->session->setFlash('success', 'Последнее пополнение было отменено');
            return $this->redirect('/users/view?id='.$id);
        }
        Yii::$app->session->setFlash('danger', 'Пополнение не было найдено');
        return $this->redirect('/users/view?id='.$id);
    }

    public function actionBalanceT()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $total_pv=0;
        $total_cv=0;
        $total_withdraws=0;
        $actions = Actions::find()->asArray()->orderBy('time asc')->all();
        $withdraws = Withdraws::find()->asArray()->orderBy('time asc')->all();
        foreach ($withdraws as $withdraw) {
            $total_withdraws += $withdraw['sum'];
        }
        foreach ($actions as $action){
            if ($action['sum'] == NULL) {
                continue;
            }
        }
        $actions_withdraws = ArrayHelper::merge($actions, $withdraws);
        ArrayHelper::multisort($actions_withdraws, ['time'], SORT_ASC);
        foreach ($actions_withdraws as $action) {
            $current_cv=0;
            $current_pv=0;
            if ($action['sum'] == NULL) {
                continue;
            }
            if (array_key_exists('type', $action)) {
                $type = ActionTypes::find()->where(['id' => $action['type']])->one();
                if (!$type) {
                    //print_r($action['id'].'<br>');
                    continue;
                }
                if ($action['type'] == 7 || $action['type'] == 105) {
                    //echo "PV  +" . $action['sum'] . "<br>";
                    $current_pv += $action['sum'];
                } else if ($action['type'] == 2) {
                    // вывод денег perfect money из за бага
                    //$current_pv -= $action['sum'];
                } else if ($type->minus == 2) {
                    $current_cv += $action['sum'];
                    //echo "CV  +" . $action['sum'] . "<br>";
                } else {
                    if ($current_cv < $action['sum']) {
                        if (($current_cv + $current_pv) < $action['sum']) {
                            //недостаточно средств
                            //$current_pv -= $action['sum'];
                        } else {
                            $current_pv -= ($action['sum'] - $current_cv);
                            //echo "PV  -> CV: " . ($action['sum'] - $cv) . "<br>";
                            $current_cv = 0;
                        }
                    } else {
                        $current_cv -= $action['sum'];
                        //echo "CV  -" . $action['sum'] . "<br>";
                    }
                }
            } else {
                $current_pv -= $action['sum'];
            }
            $total_cv+=$current_cv;
            $total_pv+=$current_pv;
        }
        return $this->render('balancet', [
            'total_pv'=>$total_pv,
            'total_cv'=>$total_cv,
            'total_withdraws'=>$total_withdraws,
        ]);
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionBalanceStatistic($id){
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user = User::findOne($id);
        if(!$user) {
            return $this->goHome();
        }
        $tableHtml = "<table class='table'> <tr><th>id</th><th>Описание</th><th>Сумма</th><th>Дата</th><th>Остаток CV</th><th>Остаток PV</th></tr>";
        $statisticArray = [];
        $statisticArray['total_in'] = 0;
        $statisticArray['total_out'] = 0;
        $statisticArray['total_transfered'] = 0;
        $statisticArray['total_cashed'] = 0;
        $statisticArray['cv'] = 0;
        $statisticArray['pv'] = 0;
        $actions = Actions::find()->where(['user_id' => $user->id])->asArray()->orderBy('time asc')->all();
        $withdraws = Withdraws::find()->where(['user_id' => $user->id])->asArray()->orderBy('time asc')->all();
        $actions_withdraws = ArrayHelper::merge($actions, $withdraws);
        ArrayHelper::multisort($actions_withdraws, ['time'], SORT_ASC);
        foreach ($actions_withdraws as $action) {
            $tableHtml.="";
            if($action['sum'] == NULL)
            {
                continue;
            }

            if(array_key_exists('type', $action))
            {
                $type = ActionTypes::find()->where(['id' => $action['type']])->one();
                if(!$type)
                {
                    continue;
                }
                if ($action['type'] == 7 || $action['type'] == 105) {
                    //echo "PV  +" . $action['sum'] . "<br>";
                    $pv_before = $statisticArray['pv'];
                    $statisticArray['pv'] += $action['sum'];
                    $statisticArray['total_transfered']  += $action['sum'];
                    $tableHtml.="<tr> <td>".$action['id']."</td> <td>".$action['title']."</td> <td> +".$action['sum']."</td> <td>".date('d.m.Y H:i', $action['time'])."</td> <td>".$statisticArray['cv']."</td> <td>".$pv_before."->".$statisticArray['pv'] ."PV</td> </tr>";

                }
                else if($action['type'] == 2) {
                    // вывод денег perfect money из за бага
                    //$statisticArray['pv'] -= $action['sum'];
                    //$statisticArray['total_cashed'] += $action['sum'];
                    $tableHtml .= "<tr> <td>".$action['id']."</td> <td>".$action['title']."</td> <td> -" . $action['sum'] . "</td> <td>" . date('d.m.Y H:i', $action['time']) . "</td> <td> ".$statisticArray['cv']."</td> <td>" . $statisticArray['pv'] . "</td> </tr>";
                }
                else if ($type->minus == 2) {
                    $cv_before = $statisticArray['cv'];
                    $statisticArray['cv'] += $action['sum'];
                    //echo "CV  +" . $action['sum'] . "<br>";
                    $statisticArray['total_in'] += $action['sum'];
                    $tableHtml.="<tr> <td>".$action['id']."</td> <td>".$action['title']."</td> <td>+".$action['sum']."</td> <td>".date('d.m.Y H:i', $action['time'])."</td> <td> ".$cv_before."->".$statisticArray['cv']."CV</td> <td>".$statisticArray['pv'] ."</td> </tr>";

                } else {
                    if ($statisticArray['cv'] < $action['sum'] ) {
                        if(($statisticArray['cv'] + $statisticArray['pv']) < $action['sum'])
                        {
                            $tableHtml.="<tr> <td>".$action['id']."</td> <td>Недостаточно средств: ".$action['title']."</td> <td>-".$action['sum']."</td> <td>".date('d.m.Y H:i', $action['time'])."</td> <td> ".$statisticArray['cv']."</td> <td> ".$statisticArray['pv'] ."</td> </tr>";

                        }
                        else {
                            $pv_before =  $statisticArray['pv'];
                            $cv_before =  $statisticArray['cv'];
                            $statisticArray['pv'] -= ($action['sum'] - $statisticArray['cv']);
                            //echo "PV  -> CV: " . ($action['sum'] - $cv) . "<br>";
                            $statisticArray['cv'] = 0;
                            $statisticArray['total_out'] += $action['sum'];
                            $tableHtml .= "<tr> <td>" . $action['id'] . "</td> <td>" . $action['title'] . "</td> <td>-" . $action['sum'] . "</td> <td>" . date('d.m.Y H:i', $action['time']) . "</td> <td> " .$cv_before."->". $statisticArray['cv'] . "CV</td> <td> " .$pv_before."->". $statisticArray['pv'] . "PV</td> </tr>";
                        }
                    } else {
                        $cv_before = $statisticArray['cv'];
                        $statisticArray['cv'] -= $action['sum'];
                        $statisticArray['total_out'] += $action['sum'];
                        //echo "CV  -" . $action['sum'] . "<br>";
                        $tableHtml .= "<tr> <td>".$action['id']."</td> <td>" . $action['title'] . "</td> <td> -" . $action['sum'] . "</td> <td>" . date('d.m.Y H:i', $action['time']) . "</td> <td> " .$cv_before."->". $statisticArray['cv'] . "CV</td> <td>".$statisticArray['pv'] ."</td> </tr>";
                    }
                }
            }
            else{
                $statisticArray['pv'] -= $action['sum'];
                $statisticArray['total_cashed'] += $action['sum'];
                $tableHtml .= "<tr> <td>".$action['id']."</td> <td>Вывод денег</td> <td> -" . $action['sum'] . "</td> <td>" . date('d.m.Y H:i', $action['time']) . "</td> <td> " . $statisticArray['cv'] . "</td> <td>" . $statisticArray['pv'] . "</td> </tr>";
            }


        }

        $tableHtml.="</table>";
//        $user->PV= $statisticArray['pv'];
//        $user->CV= $statisticArray['cv'];
//        $user->save();
        return $this->render('balancestatistic', [
            'user'=>$user,
            'statisticArray'=>$statisticArray,
            'tableHtml'=>$tableHtml,

        ]);

    }

    public function actionDeactiv($userId){
        $user = User::findOne($userId);
        $matrixRefsSize = MatrixRef::find()->where(['user_id' => $user->id])->count();
        if ($matrixRefsSize == 1) {
            $matrix = MatrixRef::find()->where(['user_id' => $user->id])->one();
            $id = $matrix->id;
            if ($matrix->children == 0 && $matrix->slots == 0) {

                if (!empty($matrix)) {
                    $parent = MatrixRef::findOne($matrix['parent_id']);
                    if (!empty($parent)) {
                        if ($parent['shoulder1'] == $id) {
                            $parent->shoulder1 = null;
                        }
                        if ($parent['shoulder2'] == $id) {
                            $parent->shoulder2 = null;
                        }
                        $parent->children = $parent->children - 1;
                        $parent->save();
                    }
                    $big_parent = MatrixRef::findOne($matrix['big_parent_id']);
                    if (!empty($big_parent)) {
                        if ($big_parent['shoulder1_1'] == $id) {
                            $big_parent->shoulder1_1 = null;
                        }
                        if ($big_parent['shoulder1_2'] == $id) {
                            $big_parent->shoulder1_2 = null;
                        }
                        if ($big_parent['shoulder2_1'] == $id) {
                            $big_parent->shoulder2_1 = null;
                        }
                        if ($big_parent['shoulder2_2'] == $id) {
                            $big_parent->shoulder2_2 = null;
                        }
                        $big_parent->slots = $big_parent->slots - 1;
                        $big_parent->save();
                    }
                    $matclons = MatClons::find()->where(['mat_id' => $id])->all();
                    if (!empty($matclons)) {
                        foreach ($matclons as $matclon) {
                            $matclon->delete();
                        }
                    }
                    $matparents = MatParents::find()->where(['mat_id' => $id])->all();
                    if (!empty($matparents)) {
                        foreach ($matparents as $matparent) {
                            $matparent->delete();
                        }
                    }

                    $matrix->delete();

                }
                $user->activ = null;
                $user->newmatrix = 2;
                $user->time_personal = null;
                $user->time_global = null;
                $user->platform_id = null;
                $userCourses = UserCourses::find()->where(['user_id' => $user->id])->all();
                foreach ($userCourses as $course){
                    $course->delete();
                }


                $action = Actions::find()->where(['user_id' => $user->id, 'type' => 1])->one();
                if ($action){
                    $sum = $action->sum;
                    $action->delete();
                    $user->w_balans += $sum;
                }
                $user->save();
                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Откат активации успешно выполнено.'));
                return $this->redirect('/users/view?id='.$user->id);
            }
        }

        Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошика'));
        return $this->redirect('/users/view?id='.$user->id);
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
