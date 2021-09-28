<?php
namespace backend\controllers;

use backend\models\UploadImage;
use common\models\Actions;
use common\models\Data;
use common\models\Data2;
use common\models\MatrixRef;
use common\models\MatrixStart;
use common\models\Referals;
use common\models\User;
use common\models\UserPlatforms;
use common\models\UserPlatforms2;
use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'mat', 'index', 'upload','matrix','matrix2','matrixref','matrixstart','bons','shanyrakplus','closes'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function actionUpload(){
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new UploadImage();
        if(Yii::$app->request->isPost){
            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();
            return $this->render('upload', ['model' => $model]);
        }
        return $this->render('upload', ['model' => $model]);
    }
    /**
     * Displays homepage.
     *
     * @return string
     */public $enableCsrfValidation = false;
    public function actionIndex()
    {

        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        return $this->render('index',[

        ]);
    }
    public function actionMat()
    {

        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        $mat = Yii::$app->request->get('mat');
        $level = Yii::$app->request->get('level');
        $username = Yii::$app->request->get('username');
        $error = null;
        if(!empty($mat)){
            $mat = MatrixRef::findOne($mat);
            $level = $mat['platform_id'];
        }else{
            if(empty($level)){
                $level = 6;
            }
            $mat = MatrixRef::find()->where(['platform_id'=>$level,'deleted'=>2])->orderBy('id asc')->one();
        }
        if(!empty($username)){
            $user = User::findOne(['username'=>$username]);
            if(!empty($user)){
                $mat = MatrixRef::findOne(['user_id'=>$user['id'],'platform_id'=>$level]);
            }
        }




        $data = array();
        $user_ids = MatrixRef::find()->select('user_id')->where(['platform_id'=>$level])->distinct('user_id')->all();
        foreach ($user_ids as $user_id) {
            $data[] = User::findOne($user_id['user_id'])['username'];
        }


        return $this->render('mat',[
            'mat'=>$mat,
            'level'=>$level,
            'data'=>$data,
            'error'=>$error,
            'username'=>$username,
        ]);
    }

    public function actionCloses()
    {

        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        return $this->render('closes',[

        ]);
    }
    public function actionShanyrakplus()
    {

        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        return $this->render('shanyrakplus',[

        ]);
    }
    public function actionBons()
    {

        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Actions::find()->where(['comment'=>"bon"]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        return $this->render('bons',[
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionMatrix()
    {

        if (!User::isAccess('admin')) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    public static function getDom($mat_id,$main_user_id=null){
    $mat = MatrixRef::findOne($mat_id);
    $user = User::findOne($mat['user_id']);
    $parent = MatrixRef::findOne($mat['parent_id']);
    if($parent['shoulder1']==$mat_id){
        $sholder = "Л";
    }else{
        $sholder = "П";
    }
        ?>
            <li class="li-item">
                <span class="mat-info <? if($user['id'] == $main_user_id){echo "mine";} ?>">
                    <span><?=$sholder?></span>
                    <? if ($mat['reinvest'] == 1) {?>
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                            <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                        </svg>
                    <?}elseif ($mat['buy'] == 1){?>
                        <i class="fa fa-dollar"></i>
                    <?}else{?>
                        <i class="fa fa-dashboard"></i>
                    <?}?>
                    <?=$user['username']?>
                    <?
                    $num1 = \common\models\MatClons::find()->where(['mat_id'=>$mat['id']])->one();
                    if(!empty($num1)){?>
                        (<?=$num1['num']?>)
                    <?}
                    ?>
                    [<?=$mat['id']?>]
                </span>

                <ul class="ul-item">
                <?
                    $children = \common\models\MatrixRef::find()->where(['parent_id'=>$mat['id']])->all();
                    foreach ($children as $child) {
                        \backend\controllers\SiteController::getDom($child['id'],$main_user_id);
                    }
                ?>
                </ul>
            </li>
    <?}}
