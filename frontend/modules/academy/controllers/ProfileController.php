<?php


namespace frontend\modules\academy\controllers;

use common\models\User;
use common\models\Verifications;
use frontend\models\forms\ConfirmEmail;
use frontend\modules\academy\components\BaseAcademyController;
use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use frontend\models\forms\ChangeDataForm;

class ProfileController extends BaseAcademyController
{

    /**
     * @return string|yii\web\Response
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        $changeDataForm = new ChangeDataForm();

        if ($changeDataForm->load(Yii::$app->request->post()) && $changeDataForm->validate()) {
            $user->lastname = $changeDataForm->lastname;
            $user->firstname = $changeDataForm->firstname;
            $user->phone = $changeDataForm->phone;
            $user->fio = $changeDataForm->firstname . ' ' . $changeDataForm->lastname;
            $user->country_id = $changeDataForm->country;


            if ($user->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Новые данные сохранены'));
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка сохранении данных'));
            }
            return $this->redirect(Url::toRoute('/academy/profile'));
        }

        return $this->render('index', [
            'user' => $user,
            'changeDataForm' => $changeDataForm,
        ]);
    }

    /**
     * @return string|yii\web\Response
     */
    public function actionSecurity()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        $changePasswordForm = new \frontend\models\forms\ChangePasswordForm();
        if ($changePasswordForm->load(Yii::$app->request->post()) && $changePasswordForm->validate()) {
            $user->setPassword($changePasswordForm->new_password);
            if ($user->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Пароль был успешно изменён'));
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка сохранении данных'));
            }
            return $this->redirect(Url::toRoute('/academy/profile/security'));
        }
        return $this->render('security', [
            'user' => $user,
            'changePasswordForm' => $changePasswordForm,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionVerification()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        $documentType = [0 => 'Удостоверение личности', 1 => 'Паспорт', 2 => 'Водительское удостоверение'];
        /*Верификация файлов*/

        $verificationForm = new \frontend\models\forms\VerificationForm();
        $success = false;
        $model = \common\models\Verifications::find()->where(['user_id' => $user['id']])->one();
        if (empty($model)) {
            $model = new Verifications();
            $model->stage = Verifications::STAGE_SEND_EMAIL;
            $model->user_id = Yii::$app->user->id;
            $model->time = time();
        }

        /*Верификация почты*/
        $sendEmailModel = new ConfirmEmail();

        if ($model->stage < Verifications::STAGE_READY_TO_VALID_USER_DATA) {
            if ($model->stage == Verifications::STAGE_SEND_EMAIL || Yii::$app->request->post('typeReq') == 'send') {
                $sendEmailModel->scenario = ConfirmEmail::SCENARIO_SEND_CODE;
            } elseif ($model->stage == Verifications::STAGE_CHECK_EMAIL) {
                $sendEmailModel->scenario = ConfirmEmail::SCENARIO_CHECK_CODE;
            }

            if ($sendEmailModel->load(Yii::$app->request->post())) {

                if ($sendEmailModel->typeReq == 'send' || $model->scenario == ConfirmEmail::SCENARIO_SEND_CODE) {
                    $sendEmailModel->sendValidateCodeToEmail($user);
                    $model->stage = Verifications::STAGE_CHECK_EMAIL;
                    $model->time = time();
                    $model->save();
                } elseif ($model->scenario == ConfirmEmail::SCENARIO_CHECK_CODE || $sendEmailModel->typeReq == 'check') {
                    if ($sendEmailModel->validate()) {
                        Yii::$app->getSession()->setFlash('success', 'Email verified');
                        $model->stage = Verifications::STAGE_READY_TO_VALID_USER_DATA;
                        $model->save();
                    }
                }
            }
        } elseif ($model->stage == Verifications::STAGE_READY_TO_VALID_USER_DATA || $model->stage == Verifications::STAGE_MODIFY_USER_DATA) {
            if (Yii::$app->request->isPost) {
                $model->user_id = Yii::$app->user->id;
                $model->document_type_id = Yii::$app->request->post()["VerificationForm"]['documentType'];
                $model->country_id = Yii::$app->request->post()["VerificationForm"]['country_id'];
                $model->time = time();
                if (Yii::$app->session->has('verification')) {
                    $filesArr = Yii::$app->session->get('verification');
                    if (isset($filesArr['file1'])) {
                        $model->doc1 = $filesArr['file1'];
                    }
                    if (isset($filesArr['file2'])) {
                        $model->doc2 = $filesArr['file2'];
                    }
                    if (isset($filesArr['file3'])) {
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
        } else if ($model->stage == Verifications::STAGE_USER_DATA_WAIT_VALID) {

            if (Yii::$app->request->post('closeInfo')) {
                Yii::$app->session->set('showInfo', false);
            }
        } else if ($model->stage == Verifications::STAGE_READY_TO_VALID_ADDRESS || $model->stage == Verifications::STAGE_ADDRESS_MODIFY) {
            if (Yii::$app->request->isPost) {
                $model->address = Yii::$app->request->post()["VerificationForm"]['address'];
                $model->city = Yii::$app->request->post()["VerificationForm"]['city'];
                $model->post_index = Yii::$app->request->post()["VerificationForm"]['postIndex'];
                $file4 = UploadedFile::getInstance($verificationForm, 'file4');
                $model->time = time();
                if ($file4) {
                    $verificationForm->file4 = $file4;
                    if ($verificationForm->validate(['file4'])) {

                        $rand = rand(900000, 9000000);
                        $dir = Yii::getAlias('@frontend/web/docs/verification/');
                        $dir2 = Yii::getAlias('docs/verification/');
                        $fileName = $rand . '.' . $verificationForm->file4->extension;
                        $verificationForm->file4->saveAs($dir . $fileName);
                        $verificationForm->file4 = $fileName; // без этого ошибка
                        $link4 = '/' . $dir2 . $fileName;
                    }
                    $model->doc4 = $link4;
                }

                $model->stage = Verifications::STAGE_ADDRESS_WAIT_VALID;
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Данные сохранены'));
                    //Yii::$app->session->set('showInfo', true);
                    return $this->redirect(Url::toRoute('/profile/verification'));
                }
            }

        }
        if (Yii::$app->session->has('verification')){
            $filesFromSession = Yii::$app->session->get('verification');
        }else{
            $filesFromSession = ['file1'=>null, 'file2' => null, 'file3' => null];
        }

        return $this->render('verification', [
            'verificationForm' => $verificationForm,
            'sendEmailModel' => $sendEmailModel,
            'model' => $model,
            'documentType' => $documentType,
            'user' => $user,
            'filesFromSession' => $filesFromSession,
        ]);
    }
}