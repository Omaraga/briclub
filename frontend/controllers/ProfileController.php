<?php
namespace frontend\controllers;

use common\models\BriTokens;
use common\models\Insurances;
use common\models\Acts;
use common\models\Bills;
use common\models\Canplatforms;
use common\models\Confirms;
use common\models\Courses;
use common\models\Exceptions;
use common\models\Lessons;
use common\models\Matblocks;
use common\models\MatClons;
use common\models\MatParents;
use common\models\MatrixRef;
use common\models\MatrixStart;
use common\models\Messages;
use common\models\MLevelsNew;
use common\models\MLevelsStart;
use common\models\Pretrans;
use common\models\Referals;
use common\models\Tickets;
use common\models\TicketTypes;
use common\models\Tokens;
use common\models\UserLessons;
use common\models\UsersSearchFront;
use common\models\Verifications;
use frontend\models\forms\ConvertForm;
use frontend\models\forms\MessageForm;
use frontend\models\forms\TicketForm;
use frontend\models\forms\TransfersForm;
use common\models\Actions;
use common\models\UserCourses;
use common\models\User;
use common\models\UserPlatforms;
use common\models\Withdraws;
use http\Client;
use kartik\mpdf\Pdf;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use frontend\models\forms\ConfirmEmail;
use frontend\models\forms\InsuranceEmailForm;
use common\models\Premiums;
/**
 * Site controller
 */
class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','block','unblock','withdrawcancel', 'get-course','verification','favorites','settings','courses','structure','actions','withdraw','activ','transfer','withdraws','perfect','bonus','bonus-global','refs','refs-global','matrix','children','getnewplatform','getnewplatformstart','tickets','documents','global','start', 'promo', 'pay-bill', 'user-bills', 'view-document','statistic', 'theme', 'delete-avatar', 'upload-avatar', 'convert', 'insurance','insurance-change-status','upload-file', 'payment', 'balance', 'portfolio'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex($tabName = 'personal')
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = Yii::$app->user->identity;
        $changePasswordForm = new \frontend\models\forms\ChangePasswordForm();
        $changeDataForm = new \frontend\models\forms\ChangeDataForm();
        $avatarForm = new \frontend\models\forms\AvatarForm();

        $success = false;
        $premium = \common\models\Premiums::findOne(['user_id' => Yii::$app->user->id]);
        if ($premium){

            if(isset($_POST['image']))
            {
                $data = $_POST['image'];

                $image_array_1 = explode(";", $data);

                $image_array_2 = explode(",", $image_array_1[1]);

                $data = base64_decode($image_array_2[1]);

                if ($avatarForm->upload(Yii::$app->user->identity['id'], $data)) {
                    // file is uploaded successfully
                    return $this->redirect(Url::toRoute('/profile/settings'));
                }
            }

            if (Yii::$app->request->isPost) {
                $avatarForm->imageFile = UploadedFile::getInstance($avatarForm, 'imageFile');
                if ($avatarForm->upload(Yii::$app->user->identity['id'])) {
                    // file is uploaded successfully
                    return $this->redirect(Url::toRoute('/profile/settings'));
                }
            }
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('users', 'CHANGES_WERE_SAVED'));

            return $this->redirect(Url::toRoute('/profile/settings'));
        }

        if ($model->password_hash != '') {
            $changePasswordForm->scenario = 'requiredOldPassword';
        }


        if ($changePasswordForm->load(Yii::$app->request->post()) && $changePasswordForm->validate()) {
            $model->setPassword($changePasswordForm->new_password);
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'NEW_PASSWORD_WAS_SAVED'));
                return $this->redirect(Url::toRoute('/profile/settings'));
            }
        }
        if ($changeDataForm->load(Yii::$app->request->post()) && $changeDataForm->validate()) {
            $model->lastname = $changeDataForm->lastname;
            $model->firstname = $changeDataForm->firstname;
            $model->phone = $changeDataForm->phone;
            $model->fio = $changeDataForm->firstname. ' '.$changeDataForm->lastname;
            //$model->country_id = $сhangeDataForm->country;



            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Новые данные сохранены'));
                return $this->redirect(Url::toRoute('/profile/settings'));
            }
        }
        return $this->render('settings', [
            'model' => $model,
            'changePasswordForm' => $changePasswordForm,
            'сhangeDataForm' => $changeDataForm,
            'avatarForm' => $avatarForm,
            'tabName'=>$tabName,
        ]);
    }
    public function actionGetCourse($program=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if($program == 1){
            $name = "Start";
            $price = 15;
        }
        if($program == 2){
            $name = "Personal";
            $price = 103;
        }
        if($program == 3){
            $name = "Global";
            $price = 75;
        }
        return $this->render('get-course',[
            'program'=>$program,
            'price'=>$price,
            'name'=>$name
        ]);
    }
    public function actionBonus()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->user->identity['newmatrix'] == 1 or Yii::$app->user->identity['global'] == 1){
            $view = 'bonus_new';
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Активируйте матрицу Personal или Global чтобы участвовать в бонусной программе!'));
            return $this->redirect('/profile');
        }

        return $this->render($view);
    }
    public function actionActiv($program=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->identity['id']);
        if($program){
            if($program == 2){
                if($user['newmatrix'] == 1){
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
                    return $this->redirect('/profile');
                }else{
                    $min_balans = 5000;
                }
            }
            $balans = $user['w_balans'];
            if($balans >= $min_balans){
                $user->w_balans = $user->w_balans - $min_balans;

                $user->save();

                if($program == 2) {
                    MatrixRef::plusToRefMatrix($user['id'], 1, true, 0, true, null, 1);
                }

                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Поздравляем! Вы активировали программу!'));
                return $this->redirect('/profile');
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Недостаточно средств на балансе!'));
                return $this->redirect('/profile');
            }

        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
            return $this->redirect('/profile');
        }
    }

    public function actionGetnewplatform($platform=1)
    {
        $ref = $_SERVER['HTTP_REFERER'];
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);
        if ($user['newmatrix'] != 1) {
            return $this->goHome();
        }
        if($user){
            $balans = $user['w_balans'];
            $min_balans = MLevelsNew::findOne($platform)['price'];
            $min_tokens =\common\models\TokenFees::findOne(['platform_id'=>$platform])['fee_token'];
            $buy = true;
            $reinvest = 0;
            $canp = Canplatforms::find()->where(['platform'=>$platform,'user_id'=>$user['id']])->orderBy('id asc')->one();
            $clon_num = null;
            $without = null;
            if(!empty($canp)){
                $buy = false;
                $min_balans = 0;
                $min_tokens = 0;
                $clon = MatClons::find()->where(['mat_id'=>$canp['mat_id']])->one();
                if(!empty($clon)){
                    $clon_num = $clon['num'];
                }else{
                    $without = 1;
                }
            }
            if ($platform >= 5 && $min_balans > 0){
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Вы не можете выкупить место на '.$platform.' столе!'));
                return $this->redirect($ref);
            }

            if($balans >= $min_balans){
                /*$tokens = 0;
                $user_tokens = Tokens::findOne(['user_id'=>$user['id']]);
                if(!empty($user_tokens)){
                    $tokens = $user_tokens->balans;
                    if($tokens < $min_tokens){
                        Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! Недостаточно токенов! Пополните свой баланс в токенах!'));
                        return $this->redirect($ref);
                    }
                }
                if($min_tokens>0){
                    $user_tokens->balans = $user_tokens->balans - $min_tokens;
                    $action2 = new Actions();
                    $action2->type = 59;
                    $action2->title = "Списание комиссии в токенах за покупку стола ";
                    $action2->user_id = $user['id'];
                    $action2->time = time();
                    $action2->status = 1;
                    $action2->tokens = $min_tokens;
                    $action2->save();

                    $user_tokens->save();
                }*/


                $user->w_balans = $user->w_balans - $min_balans;
                $user->save();
                if(!empty($canp)){
                    $canp->delete();
                }
                MatrixRef::plusToRefMatrix($user['id'],$platform,false,$reinvest,$buy,$clon_num,$without);


                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Поздравляем! Вы купили место на площадке '.$platform.'!'));
                $this->redirect($ref);
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Недостаточно средств на балансе!'));
                $this->redirect($ref);
            }

        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
            $this->redirect($ref);
        }
    }
    public function actionBlock($id=null)
    {
        $ref = $_SERVER['HTTP_REFERER'];
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);
        if ($user['newmatrix'] != 1) {
            return $this->goHome();
        }
        $matrix = MatrixRef::findOne($id);

        $m_num = 3;
        $premium = Premiums::findOne(['user_id' => Yii::$app->user->id]);
        if($premium != null && $premium->is_active == 1){
            $m_num = 2;
        }

        if($matrix['platform_id']<$m_num){
            return $this->redirect($ref);
        }
        $main = MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>$matrix['platform_id']])->orderBy('id asc')->one();
        $parent = MatrixRef::findOne($matrix['parent_id']);


        $bl=0;
        if($parent['shoulder1'] == $matrix['id']){
            $neight = MatrixRef::findOne($parent['shoulder2']);
            $neight_block = Matblocks::find()->where(['user_id'=>$user['id'],'mat_id'=>$neight['id']])->one();
            if(!empty($neight_block)){
                $bl = 1;
            }
        }elseif($parent['shoulder2'] == $matrix['id']){
            $neight = MatrixRef::findOne($parent['shoulder1']);
            $neight_block = Matblocks::find()->where(['user_id'=>$user['id'],'mat_id'=>$neight['id']])->one();
            if(!empty($neight_block)){
                $bl = 1;
            }
        }


        if($matrix['id'] == $main['id']){
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! Нельзя блокировать головное место!'));
            $this->redirect($ref);
        }else{
            if($bl == 1){
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! Нельзя блокировать 2 соседних места!'));
                return $this->redirect($ref);
            }else{
                if(!empty($matrix)){
                    $block = Matblocks::find()->where(['user_id'=>$user['id'],'mat_id'=>$id])->one();
                    if(empty($block)){
                        $block = new Matblocks();
                        $block->mat_id = $id;
                        $block->user_id = $user['id'];
                        $block->save();
                        $children = MatParents::find()->where(['parent_id'=>$id])->all();
                        foreach ($children as $child) {
                            $block = new Matblocks();
                            $block->mat_id = $child['mat_id'];
                            $block->user_id = $user['id'];
                            $block->save();
                        }
                    }
                }
                return $this->redirect($ref);
            }

        }

    }
    public function actionUnblock($id=null)
    {
        $ref = $_SERVER['HTTP_REFERER'];
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $matrix = MatrixRef::findOne($id);

        $m_num = 3;
        $premium = Premiums::findOne(['user_id' => Yii::$app->user->id]);
        if($premium != null && $premium->is_active == 1){
            $m_num = 2;
        }
        if($matrix['platform_id']<$m_num){
            return $this->redirect($ref);
        }
        $user = User::findOne(Yii::$app->user->id);
        if ($user['newmatrix'] != 1) {
            return $this->goHome();
        }
        $block = Matblocks::find()->where(['user_id'=>$user['id'],'mat_id'=>$id])->one();
        $parents = MatParents::find()->where(['mat_id'=>$id])->all();

        $block2 = null;
        foreach ($parents as $parent) {
            $block2 = Matblocks::find()->where(['user_id'=>$user['id'],'mat_id'=>$parent['parent_id']])->one();
            if(!empty($block2)){
                break;
            }
        }

        if(empty($block2)){
            if(!empty($block)){
                $block->delete();
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! Нельзя раблокировать это место!'));
            $this->redirect($ref);
        }

        $children = MatParents::find()->where(['parent_id'=>$id])->all();
        foreach ($children as $child) {
            $block = Matblocks::find()->where(['user_id'=>$user['id'],'mat_id'=>$child['mat_id']])->one();
            if(!empty($block)){
                $block->delete();
            }
        }
        $this->redirect($ref);
    }


    public function actionWithdraw($system=2)
    {

        $model = new Withdraws();
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->user->identity['block'] == 1){
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Выводы временно приостановлены для вашего аккаунта'));

            return $this->redirect('/profile');
        }
        $ver = false;
        if(Yii::$app->user->identity['verification'] == 1){
            $ver = true;
        }
        $error = false;
        $success = false;
        $code = new \frontend\models\forms\CodeForm();
        $user = User::findOne(Yii::$app->user->identity['id']);
        $pretrans = Pretrans::find()->where(['user_id'=>$user['id'],'status'=>3,'system_id'=>$system])->one();
        if($code->load(Yii::$app->request->post()) and $code->validate()){
            if(!empty($pretrans)){
                if($pretrans['code'] == $code['code']){
                    $model->sum = $pretrans->sum;
                    $model->sum2 = $pretrans->sum2;
                    $model->account = $pretrans->account;
                    $model->fee = $pretrans->fee;
                    $model->time = time();
                    $model->system_id = $system;
                    $model->user_id = Yii::$app->user->identity['id'];
                    $model->save();
                    $user->p_balans = $user->p_balans - $model->sum;
                    $user->save();
                    $model->save();
                    $success = "Ваш запрос принят в обработку. Деньги поступят в течении 72 часов.";
                    $pretrans->delete();
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Ваш запрос принят в обработку. Деньги поступят в течении 72 часов.'));
                    $this->redirect('/profile');
                }else{
                    $pretrans->delete();
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Неверный код активации!'));
                    return $this->redirect('/profile/transfer');
                }
            }
        }else{
            if(!empty($pretrans)){
                $pretrans->delete();
                $pretrans = null;
            }
        }
        if($model->load(Yii::$app->request->post())){
            $model->fee = round(($model->sum/100)*3,2);
            $model->sum2 = $model->sum - $model->fee;

            $children = User::find()->where(['parent_id'=>$user['id']])->all();
            if($user['p_balans']>=($model->sum)*1){
                if($user['limit'] >= ($model->sum)*1 or $ver == true){

                    $pretrans = new Pretrans();
                    $pretrans->user_id = $user['id'];
                    $pretrans->sum = $model->sum;
                    $pretrans->account = $model->account;
                    $pretrans->fee = $model->fee;
                    $pretrans->sum2 = $model->sum2;
                    $pretrans->time = time();
                    $pretrans->system_id = $system;
                    $pretrans->code = rand(1000,999999);
                    $pretrans->save();

                    $user->limit = $user->limit - $model->sum;

                    Pretrans::sendEmail($user['id'],$pretrans->code);
                }else{
                    $error = 'Вы можете вывести $'.$user['limit'].'. Чтобы вывести больше пройдите верификацию личности!';
                }
            }else{
                $error = 'Недостаточно средств!';
            }

        }
        $activ = false;
        if(!empty($user)){
            $activ = true;
        }
        if($system == 2){
            $view = 'withdraw';
        }elseif ($system == 1){
            $view = 'withdraw1';
        }elseif ($system == 3){
            $view = 'withdraw3';
        }
        return $this->render($view,[
            'model' => $model,
            'error' => $error,
            'success' => $success,
            'code' => $code,
            'pretrans' => $pretrans,
            'user'=>$user,
        ]);
    }
    public function actionWithdrawcancel($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = User::findOne(Yii::$app->user->id);
        if(!empty($id)){
            $withdraw = Withdraws::findOne($id);
            if(!empty($withdraw)){
                if($withdraw['user_id'] == $user['id']){
                    if($withdraw['status'] == 3){
                        $withdraw->status = 2;
                        $withdraw->save();
                        $user->p_balans = $user->p_balans + $withdraw->sum;
                        $user->save();
                        $action = new Actions();
                        $action->status = 1;
                        $action->type = 22;
                        $action->sum = $withdraw->sum;
                        $action->time = time();
                        $action->title = "Отмена вывода";
                        $action->user_id = $withdraw->user_id;
                        $action->save();
                        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Вывод отменен'));

                        return $this->redirect('/profile/actions');
                    }
                }

            }
        }

    }

    public function actionPerfect()
    {
        $model = new Actions();
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $error = false;
        $success = false;


        return $this->render('perfect',[
            'model' => $model,
            'error' => $error,
            'success' => $success,
        ]);
    }

    public function actionTransfer()
    {
        $model = new TransfersForm();
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->user->identity['block'] == 1){
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Переводы временно приостановлены для вашего аккаунта'));

            return $this->redirect('/profile');
        }
        $error = false;
        $success = false;
        $code = new \frontend\models\forms\CodeForm();
        $user = User::findOne(Yii::$app->user->identity['id']);
        $pretrans = Pretrans::find()->where(['user_id'=>$user['id'],'status'=>3])->one();
        if($code->load(Yii::$app->request->post()) and $code->validate()){
            if(isset($_SESSION["token"])){
                $_SESSION["token"] = null;
                return $this->redirect('/profile');
            }else{
                $_SESSION["token"] = "123";
            }
            if(!empty($pretrans)){
                if($pretrans['code'] == $code['code']){
                    /*$user_tokens = Tokens::findOne(['user_id'=>$user['id']]);
                    if(empty($user_tokens)){
                        Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! Недостаточно токенов!'));
                        return $this->redirect('/profile');
                    }else{
                        if($user_tokens->balans < $pretrans->fee){
                            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! Недостаточно токенов!'));
                            return $this->redirect('/profile');
                        }
                    }*/
                    if($user->w_balans<$pretrans->sum){
                        Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! Недостаточно средств!'));
                        return $this->redirect('/profile');
                    }
                    $user2 = User::findOne($pretrans->user2_id);
                    $user->w_balans = $user->w_balans - $pretrans->sum;
                    //$user_tokens->balans = $user_tokens->balans - $pretrans->fee;
                    $user2->w_balans = $user2->w_balans + $pretrans->sum;

                    $action = new Actions();
                    $action->type = 4;
                    $action->title = "Вам поступил перевод";
                    $action->user_id = $user2['id'];
                    $action->user2_id = $user['id'];
                    $action->time = time();
                    $action->status = 1;
                    $action->sum = $pretrans->sum;
                    $action->fee = $pretrans->fee;
                    $action->save();

                    $action2 = new Actions();
                    $action2->type = 3;
                    $action2->title = "Вы произвели перевод";
                    $action2->user_id = $user['id'];
                    $action2->user2_id = $user2['id'];
                    $action2->time = time();
                    $action2->status = 1;
                    $action2->sum = $pretrans->sum;
                    $action2->fee = $pretrans->fee;
                    $action2->save();

                    if($pretrans->fee>0){
                        $action2 = new Actions();
                        $action2->type = 58;
                        $action2->title = "Списание комиссии в токенах за перевод пользователю ".$user2['username'];
                        $action2->user_id = $user['id'];
                        $action2->time = time();
                        $action2->status = 1;
                        $action2->tokens = $pretrans->fee;
                        $action2->save();
                    }


                    $user2->save();
                    $user->save();
                    //$user_tokens->save();
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Перевод успешно выполнен!'));
                    $this->redirect('/profile');
                }else{
                    $pretrans->delete();
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Неверный код активации!'));
                    return $this->redirect('/profile/transfer');
                }
            }
        }else{
            if(!empty($pretrans)){
                $pretrans->delete();
                $pretrans = null;
            }
        }
        if($model->load(Yii::$app->request->post()) and $model->validate()){

            $user = User::findOne(Yii::$app->user->identity['id']);
            if($user['w_balans']>=($model->sum)*1){


                $pretrans = new Pretrans();
                $pretrans->user_id = $user['id'];
                $pretrans->sum = $model->sum;
                $pretrans->user2_id = User::find()->where(['username'=>$model->username])->one()['id'];
                $pretrans->time = time();
                $pretrans->fee = $model->fee;

                $pretrans->code = rand(1000,999999);
                $pretrans->save();

                Pretrans::sendEmail($user['id'],$pretrans->code);
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! Недостаточно средств!'));
            }
        }
        return $this->render('transfer',[
            'model' => $model,
            'error' => $error,
            'success' => $success,
            'code' => $code,
            'pretrans' => $pretrans,
        ]);
    }

    public function actionStructure($id=null,$m=1,$c=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = "main-bg";
        $user = User::findOne(Yii::$app->user->id);
        $new = false;
        $username = null;
        if($user['newmatrix'] == 1){
            $is_child = false;
            $user_id = $user['id'];
            if(!empty($id)){
                $ref = Referals::find()->where(['user_id'=>$id,'parent_id'=>$user['id']])->one();
                if(!empty($ref)){
                    $user_id = $id;
                    $is_child = true;
                }
            }
            $new = true;
            $username = Yii::$app->request->get('username');
            if(!empty($username)){
                $s_user = User::find()->where(['username'=>$username])->one();
                if(!empty($s_user)){
                    $ref = Referals::find()->where(['user_id'=>$s_user['id'],'parent_id'=>$user['id']])->one();
                    if(!empty($ref)){
                        $user_id = $ref['user_id'];
                    }else{
                        Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Пользователь не найден'));
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Пользователь не найден'));
                }
            }


        }
        if($new){
            return $this->render('structure_new',[
                'is_child'=>$is_child,
                'user_id'=>$user_id,
                'username'=>$username,
                'm'=>$m,
                'c'=>$c,
            ]);
        }else{
            return $this->render('structure_buy');
        }

    }


    public function actionWithdraws()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('withdraws',[

        ]);
    }

    public function actionActions($id=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $news = null;
        $view = "actions";
        if(!empty($id)){
            $news = Actions::findOne($id);
            if($news['type'] == 104 and $news['status'] == 1){
                $view = "action";
            }
        }

        return $this->render($view,[
            'news' =>$news
        ]);
    }




    public function actionVerification()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        $documentType = [0=>'Удостоверение личности', 1=>'Паспорт', 2=>'Водительское удостоверение'];
        /*Верификация файлов*/

        $verificationForm = new \frontend\models\forms\VerificationForm();
        $success = false;
        $model = \common\models\Verifications::find()->where(['user_id'=>$user['id']])->one();
        if(empty($model)){
            $model = new Verifications();
            $model->stage = Verifications::STAGE_SEND_EMAIL;
            $model->user_id = Yii::$app->user->id;
        }

        /*Верификация почты*/
        $sendEmailModel = new ConfirmEmail();

        if ($model->stage < Verifications::STAGE_READY_TO_VALID_USER_DATA){
            if ($model->stage == Verifications::STAGE_SEND_EMAIL || Yii::$app->request->post('typeReq') == 'send'){
                $sendEmailModel->scenario = ConfirmEmail::SCENARIO_SEND_CODE;
            }elseif ($model->stage == Verifications::STAGE_CHECK_EMAIL){
                $sendEmailModel->scenario = ConfirmEmail::SCENARIO_CHECK_CODE;
            }

            if ($sendEmailModel->load(Yii::$app->request->post())){

                if ($sendEmailModel->typeReq == 'send' || $model->scenario == ConfirmEmail::SCENARIO_SEND_CODE){
                    $sendEmailModel->sendValidateCodeToEmail($user);
                    $model->stage = Verifications::STAGE_CHECK_EMAIL;
                    $model->save();
                }elseif($model->scenario == ConfirmEmail::SCENARIO_CHECK_CODE || $sendEmailModel->typeReq == 'check'){
                    if ($sendEmailModel->validate()){
                        Yii::$app->getSession()->setFlash('success', 'Email verified');
                        $model->stage = Verifications::STAGE_READY_TO_VALID_USER_DATA;
                        $model->save();
                    }
                }
            }
        }elseif ($model->stage == Verifications::STAGE_READY_TO_VALID_USER_DATA || $model->stage == Verifications::STAGE_MODIFY_USER_DATA){
            if (Yii::$app->request->isPost) {
                $model->user_id = Yii::$app->user->id;
                $model->document_type_id = Yii::$app->request->post()["VerificationForm"]['documentType'];
                $model->country_id = Yii::$app->request->post()["VerificationForm"]['country_id'];
                $model->time = time();

                if (Yii::$app->session->has('verification')){
                    $filesArr = Yii::$app->session->get('verification');
                    if (isset($filesArr['file1'])){
                        $model->doc1 = $filesArr['file1'];
                    }
                    if (isset($filesArr['file2'])){
                        $model->doc2 = $filesArr['file2'];
                    }
                    if (isset($filesArr['file3'])){
                        $model->doc3 = $filesArr['file3'];
                    }
                    Yii::$app->session->remove('verification');
                }
                $model->stage = Verifications::STAGE_USER_DATA_WAIT_VALID;
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Данные сохранены'));
                    Yii::$app->session->set('showInfo', true);
                    return $this->redirect(Url::toRoute('/profile/verification'));
                }
            }
        }else if($model->stage == Verifications::STAGE_USER_DATA_WAIT_VALID){

            if (Yii::$app->request->post('closeInfo')){
                Yii::$app->session->set('showInfo', false);
            }
        }else if($model->stage == Verifications::STAGE_READY_TO_VALID_ADDRESS || $model->stage == Verifications::STAGE_ADDRESS_MODIFY){
            if (Yii::$app->request->isPost){
                $model->address = Yii::$app->request->post()["VerificationForm"]['address'];
                $model->city = Yii::$app->request->post()["VerificationForm"]['city'];
                $model->post_index = Yii::$app->request->post()["VerificationForm"]['postIndex'];
                if (Yii::$app->session->has('verification')){
                    $filesArr = Yii::$app->session->get('verification');
                    if (isset($filesArr['file4'])){
                        $model->doc4 = $filesArr['file4'];
                    }
                    Yii::$app->session->remove('verification');
                }

                $model->stage = Verifications::STAGE_ADDRESS_WAIT_VALID;
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Данные сохранены'));
                    //Yii::$app->session->set('showInfo', true);
                    return $this->redirect(Url::toRoute('/profile/verification'));
                }
            }
        }
        return $this->render('verification', [
            'verificationForm' => $verificationForm,
            'sendEmailModel' => $sendEmailModel,
            'model'=>$model,
            'documentType'=>$documentType,
        ]);
    }



    public function actionPayBill($code){
        $bill = Bills::findOne(['code' => $code]);

        $ticket = Tickets::findOne(['bill_id' => $bill->id]);

        $receiver = User::findOne(['id' => $bill->receiver_id]);
        $sender = User::findOne(['id' => $bill->sender_id]);


        if(Yii::$app->request->post()){
            if($sender->w_balans >= $bill->sum){

                $sender->w_balans -= $bill->sum;
                $sender->save();

                $receiver->w_balans += $bill->sum;
                $receiver->save();

                $bill->status = 1;
                $bill->save(false);

                $ticket->payment_status = Tickets::PAYMENT_STATUS_PAYED;
                $ticket->save();

                $action = new Actions();
                $action->status = 1;
                $action->type = 64;

                $action->title = "Вы успешно оплатили счет";
                $action->user_id = Yii::$app->user->id; //sender
                $action->user2_id = $bill->receiver_id; //receiver
                $action->sum = $bill->sum;
                $action->time = time();
                $action->save(false);

                $receiverAction = new Actions();
                $receiverAction->status = 1;
                $receiverAction->title = "Вам пришли деньги за оплаченный счет";
                $receiverAction->type = 66;
                $receiverAction->user_id = $bill->receiver_id; //receiver
                $receiverAction->user2_id = Yii::$app->user->id; //sender
                $receiverAction->sum = $bill->sum;
                $receiverAction->time = time();
                $receiverAction->save(false);

                Yii::$app->session->setFlash('success', 'Счет был успешно оплачен');
                return $this->redirect('/profile');
            }
            else{
                Yii::$app->session->setFlash('danger', 'У вас недостаточно токенов для оплаты');
                return $this->redirect('/profile');
            }
        }


        return $this->render('pay-bill', [
            'bill' => $bill
        ]);
    }


    public function actionUserBills(){
        $bills = Bills::find()->where(['sender_id' => Yii::$app->user->id])->andWhere(['status' => 2])->all();
        return $this->render('user-bills', [
            'bills' => $bills
        ]);
    }

    public function actionViewDocument($id = null){
        if($id != null){
            $doc = \common\models\Documents::findOne(['id' => $id]);
            if($doc){
                return $this->render('view-document',[
                    'doc' => $doc
                ]);
            }
        }
        $promo = false;
        return $this->render('documents',[
            'promo' => $promo
        ]);
    }
    public function actionStatistic($type = "year", $currTab = 1){
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }else{
            $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
        }
        $statisticModel = new \frontend\models\StatisticModel($user, $type);
        return $this->render('statistic',[
            'statisticModel'=>$statisticModel,
            'type' => trim($type),
            'currTab' => intval($currTab),
        ]);
    }
    public function actionTheme($currTheme = 0){
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        /*0 - white theme, 1 - dark theme*/
        if ($currTheme == 0){
            Yii::$app->session->set('theme', 1);
        }else{
            Yii::$app->session->set('theme', 0);
        }
        return $this->redirect('/profile');

    }

    public function actionDeleteAvatar(){
        $premium = Premiums::findOne(['user_id' => Yii::$app->user->id]);
        if($premium && $premium->img_source){
            unlink(Yii::$app->basePath . '/web/' . $premium->img_source);
            $premium->img_source = null;
            $premium->save();
            Yii::$app->session->setFlash('success', 'Вы успешно удалили фото профиля');
        }
        return $this->redirect('/profile/settings');
    }

    public function actionConvert(){
        $model = new ConvertForm();
        if($model->load(Yii::$app->request->post())){

            $user = Yii::$app->user->identity;
            if($user['p_balans'] >= $model->sum){
                $user = User::findOne(Yii::$app->user->id);
                $user->w_balans += $model->sum;
                $user->p_balans -= $model->sum;
                $user->save();

                $action = new Actions();
                $action->user_id = $user->id;
                $action->time = time();
                $action->type = 100;
                $action->status = 1;
                $action->sum = $model->sum;
                $action->title = "Вы конвертировали PV в CV";
                $action->save();


                Yii::$app->session->setFlash('success', 'Вы успешно конвертировали PV в CV');
                return $this->redirect('/profile');
            }
            else{
                Yii::$app->session->setFlash('danger', 'Недостаточно средств!');
                return $this->redirect('/profile');
            }
        }
        return $this->render('/profile/convert', [
            'model' => $model
        ]);
    }

    public function actionInsurance(){
        $user = Yii::$app->user->identity;
        if (!$model = Insurances::find()->where(['user_id'=>$user->id])->one()){
            $model = new Insurances();
        }

        $fileToEmail=''; // для отправки двух файлов удостоверения через модель InsuranceEmailForm
        $fileToEmail2=''; // отправка файла требует указания полного пути
        if($model->load(Yii::$app->request->post())){

            $file = UploadedFile::getInstance($model, 'file');
            if($file && $file->tempName){
                $model->file = $file;
                $rand = rand(1,9000000);
                $dir = Yii::getAlias('@frontend/web/img/insurances/');
                $dir2 = Yii::getAlias('img/insurances/');
                $fileName = $rand . '.' . $model->file->extension;
                $model->file->saveAs($dir . $fileName);
                $model->file = $fileName; // без этого ошиб ка
                $link = '/'.$dir2 . $fileName;
                $model->img = $link;
                $fileToEmail = $dir . $fileName;
            }

            $file2 = UploadedFile::getInstance($model, 'file2');
            if($file2 && $file2->tempName){
                $model->file2 = $file2;
                $rand = rand(1,9000000);
                $dir = Yii::getAlias('@frontend/web/img/insurances/');
                $dir2 = Yii::getAlias('img/insurances/');
                $fileName = $rand . '.' . $model->file2->extension;
                $model->file2->saveAs($dir . $fileName);
                $model->file2 = $fileName; // без этого ошиб ка
                $link = '/'.$dir2 . $fileName;
                $model->img2 = $link;
                $fileToEmail2 = $dir . $fileName;
            }
            InsuranceEmailForm::sendInsuranceEmail($user, $fileToEmail, $fileToEmail2, $model->country, $model->city, $model->address, $model->phone , $model->email);
            $model->created_at = time();
            $model->user_id = Yii::$app->user->id;
            $model->status = Insurances::STATUS_MODERATION;
            $model->save();
            return $this->redirect('/profile/insurance');
        }

        return $this->render('insurance', [
            'model' => $model
        ]);
    }
    public function actionInsuranceChangeStatus(){
        $user = Yii::$app->user->identity;
        $model = Insurances::find()->where(['user_id'=>$user->id])->one();
        /* @var $model Insurances*/
        if ($model){
            $model->status = Insurances::STATUS_EMPTY;
            $model->save(false);
        }
        $this->redirect('/profile/insurance');

    }
    public function actionUploadFile(){
        if (Yii::$app->request->isPost){
            $action = Yii::$app->request->post('action');
            $name = Yii::$app->request->post('name');
            $path = Yii::getAlias('@frontend/web/docs/verification/');
            $pathShort = Yii::getAlias('docs/verification/');
            if(!empty($_FILES)){
                if($_FILES['file']['size'] > 2097152){ // 1048576
                    $response = array("status" => "error", "error" => "Ошибка! Максимальный размер файла - 2 Мб!");
                    exit(json_encode($response));
                }
                if($_FILES['file']['error']){
                    $response = array("status" => "error", "error" => "Ошибка! Возможно файл слишком большой");
                    exit(json_encode($response));
                }
                if ($action == 'verification'){
                    $new_name = sha1(uniqid()) . ".jpg";
                    $uploadfile = $path.$new_name;


                    if(@move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)){
                        $response = array("status" => "success", "error" => "", "fileName"=>'/'.$pathShort.$new_name);
                        if (Yii::$app->session->has($action)){
                            $arr = Yii::$app->session->get($action);
                            $arr[$name] = '/'.$pathShort.$new_name;
                        }else{
                            $arr = [$name => '/'.$pathShort.$new_name];
                        }
                        Yii::$app->session->set($action, $arr);
                        return json_encode($response);
                    }
                }

            }
        }

        $response = ['status'=>'error', 'error'=>"Неизвестная ошибка"];

        return json_encode($response);
    }

    public function actionPayment($program=null){
        $user=Yii::$app->user->identity;
        return $this->render('payment', [
            'user'=>$user,
        ]);
    }

    public function actionBalance(){
        $user=Yii::$app->user->identity;
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $withdraws = \common\models\Actions::find()->where(['user_id'=>$user['id']])->orderBy('id desc')->limit(4)->all();
        return $this->render('balance', [
            'user'=>$user,
            'withdraws'=>$withdraws,
        ]);
    }

    public function actionPortfolio(){
        $user=Yii::$app->user->identity;
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $rank = $user->getRank();
        $bri = $user->getBalance('bri');
        $grc = $user->getBalance('grc');
        return $this->render('portfolio', [
            'user'=>$user,
            'rank'=>$rank,
            'bri'=>$bri,
            'grc'=>$grc
        ]);
    }

}
