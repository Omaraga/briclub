<?php
namespace frontend\modules\advcash\controllers;

use Yii;
use yii\web\Controller;
use app\modules\advcash\models\AdvcashApi;
use app\modules\advcash\models\AdvcashDeposit;
use app\modules\advcash\models\AdvcashSci;
use app\modules\advcash\models\AdvcashWithdraw;
use app\models\Activities;
use app\models\UserInfo;


/**
 * Контроллер для вывода средств
 */
class AdvcashApiController extends Controller
{
    /**
     * Отключаю проверку csrf потому что на эти адреса будут приходить ответы advcash
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['failure', 'success', 'status'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    /**
     * Страница для админки
     * Можно проверять есть ли такой аккаунт в системе Advcash
     * Можно проверить есть ли деньги для отправки
     * Просмотр всех запросов на вывод
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $helper = new AdvcashApi();
            $balanses = array();
            if ($helper->getAccountData() !== false && $helper->registerClient() !== false) {
                $data = $helper->getBalances();
                if ($data != false) {
                    $balanses = $data;
                }
                else {
                    Yii::$app->session->addFlash('error', 'Ошибка системы - '.$helper->errorMessage);
                }
            }

            $user_account = [];
            $validate = [];

            //Пришел запрос
            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                //Пришел запрос на проверку аккаунта
                if (!empty($post['user_account'])) {
                    $data = $helper->validateAccounts(array($post['user_account']));
                    if ($data != false) $user_account = $data;
                    else Yii::$app->session->addFlash('error', 'Ошибка системы - '.$helper->errorMessage);
                }
                //Проверка возможности отправки денег, не отправляет деньги а только проверяет
                if (!empty($post['amount']) && !empty($post['email'])) {
                    $data = $helper->validateSendMoney($post['amount'], AdvcashApi::CURRENCY_USD, $post['email'], '');
                    if (!isset($data)) Yii::$app->session->addFlash('success', 'Средств для отправки достаточно');
                    else Yii::$app->session->addFlash('error', "Недостаточно средств");
                }
            }
            else $post = [];

            //WithdrawSearch модель для всех выводов, отдельная таблица
            $searchModel = new WithdrawSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, AdvcashWithdraw::getTypeId(), Withdraw::STATUS_PENDING);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'post' => $post,
                'balanses' => $balanses,
                'user_account' => $user_account,
                'validate' => $validate]
            );
        }
    }

    /**
     * Создает вывод в публичной части
     * AdvcashWithdraw наследуется от Withdraw
     */
    public function actionCreateWithdraw()
    {
        $model = new AdvcashWithdraw();
        $result = false;
        $errors = array();

        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate())
            {
		        if ($model->checkBalans()) {
                    if ($model->checkAccount()) { //Проверка регистрации почты на адвакеше
                        $transaction = \Yii::$app->db->beginTransaction();
                        try {
                            //Поиск юзера
                            $user_info = UserInfo::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
                            if ($model->checkBalans()) { //Повторная проверка средств на кошельке
                                $user_info->advcash = number_format($user_info->advcash - $model->sum, 8, '.', '');

                                $activity = new Activities(); //Модель истории
                                $activity->user_id = $user_info->user_id;
                                $activity->date = date('d.m.Y H:i');
                                $activity->type = AdvcashWithdraw::ACTIVITY_TYPE;
                                $activity->type_text = 'Вывод Advcash';
                                $activity->sum = '-'.$model->sum;
                                $activity->status = 2;
                                $activity->info = 'Аккаунт Advcash '.$model->payment_mail;

                                if ($model->save() && $user_info->save()) {
                                    $activity->link_id = $model->id; //Привязывает id вывода к истории, необходимо чтобы найти потом и сменить статус в истории
                                    if ($activity->save()) {
                                        $result = true;
                                    }
                                }
                                if ($result == true) {
                                    $transaction->commit();
                                } else {
                                    $transaction->rollBack();
                                    $errors['save'] = array("Произошла ошибка, пожалуйста попробуйте позже");
                                }
                            }
                            else $errors['sum'] = array('Недостаточно средств');
                        } catch (Exception $e) {
                            $transaction->rollBack();
                            $result = false;
                            $errors['save'] = array("Произошла ошибка сервера, пожалуйста попробуйте позже");
                        }
                    }
                    else {
                        $errors['checkAccount'] = array("Указанная почта не зарегистрирована на Advcash");
                    }
		        } else $errors['sum'] = array('Недостаточно средств');
            }
        }
        //Отображает виджет для публички
        return \app\modules\advcash\widgets\AdvcashWidget::widget(['type' => 'api', 'model' => $model, 'result' => $result, 'errors' => $errors]);
    }

    /**
     * Отправка денег - для админки
     * @var $id - номер в таблице выводов
     */
    public function actionSendMoney($id)
    {
        if (!Yii::$app->user->isGuest && Yii::$app->request->isGet) {
            $helper = new AdvcashApi();
            //Получаем логин пароль
            if ($helper->getAccountData() !== false) {
                //Подключаемся к адвакешу
                if ($helper->registerClient() !== false) {
                    $withdraw = AdvcashWithdraw::find()->where(['id' => $id])->andWhere(['!=', 'status', AdvcashWithdraw::STATUS_COMPLETED])->one();
                    if ($withdraw !== null) {
                        $activity = Activities::find()->where(['link_id' => $withdraw->id, 'type' => AdvcashWithdraw::ACTIVITY_TYPE])->one();
                        if (($withdraw->sum > 0) && !empty($withdraw->payment_mail) && $activity !== null) {
                            //Берем комиссию
                            $withdraw->sum = $withdraw->sum * 0.97;
                            //Проверяем возможность отправки
                            $data = $helper->validateSendMoney($withdraw->sum, AdvcashApi::CURRENCY_USD, $withdraw->payment_mail, '');
                            if ($data === false) {
                                Yii::$app->session->addFlash('error', 'Ошибка системы - '.$helper->errorMessage);
                            }
                            elseif ($data === null) {
                                //Отправляем деньги
                                $send = $helper->sendMoney($withdraw->sum, AdvcashApi::CURRENCY_USD, $withdraw->payment_mail, '');
                                if (isset($send)) {
                                    $withdraw->status = AdvcashWithdraw::STATUS_COMPLETED;
                                    $withdraw->response = $send;
                                    // $activitty->setComplete - проверяет текущий статус и ставит status = 1
                                    if ($activity->setComplete() && $withdraw->save() && $activity->save()) {
                                        Yii::$app->session->addFlash('success', 'Средства отправлены');
                                    }
                                    else {
                                        Yii::$app->session->addFlash('error', 'Ошибка в работе с базой данных, при этом платеж прошел');
                                    }
                                }
                                elseif ($send === false) {
                                    $withdraw->status = AdvcashWithdraw::STATUS_ERROR;
                                    $withdraw->response = 'System error, maybe no connection';
                                    $withdraw->save();
                                    Yii::$app->session->addFlash('error', 'Ошибка системы, попробуйте позже');
                                }
                                else {
                                    $withdraw->status = AdvcashWithdraw::STATUS_ERROR;
                                    $withdraw->response = 'System error, maybe no money';
                                    $withdraw->save();
                                    Yii::$app->session->addFlash('error', 'Ошибка, не удалось отправить');
                                }
                            }
                            else {
                                $withdraw->status = AdvcashWithdraw::STATUS_ERROR;
                                $withdraw->response = 'No money';
                                $withdraw->save();
                                Yii::$app->session->addFlash('error', "Недостаточно средств");
                            }
                        }
                    }
                    else Yii::$app->session->addFlash('error', "Не найден доступны вывод");
                } else Yii::$app->session->addFlash('error', "Не удалось подключиться");
            } else Yii::$app->session->addFlash('error', "Нету доступов");            
        }
        //Выводит список всех запросов
        return $this->redirect('/advcash/advcash-api/index');
    }

    /**
     * Отказ в отправка денег
     */
    public function actionCancelWithdraw($id)
    {
        if (!Yii::$app->user->isGuest && Yii::$app->request->isGet) {
            $withdraw = AdvcashWithdraw::find()->where(['id' => $id])->andWhere(['status' => [AdvcashWithdraw::STATUS_ERROR, AdvcashWithdraw::STATUS_PENDING]])->one();

            if ($withdraw !== null) {
                $withdraw->status = AdvcashWithdraw::STATUS_CANCELED;
                $withdraw->response = 'Отменен оператором';
                $activity = Activities::find()->where(['link_id' => $withdraw->id, 'type' => AdvcashWithdraw::ACTIVITY_TYPE])->one();
                $user_info = UserInfo::find()->where(['user_id' => $withdraw->user_id])->one();
                if ($activity !== null && $user_info !== null)
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    $user_info->advcash = number_format($user_info->advcash + $withdraw->sum, 8, '.', '');
                    // $activity->setCancel('Отменен оператором') - проверяет статус и ставит status = 3, если есть текст, то вставляет в поле info
                    if ($activity->setCancel('Отменен оператором') && $withdraw->save() && $activity->save() && $user_info->save()) {
                        $transaction->commit();
                        Yii::$app->session->addFlash('success', 'Заявка отменена');
                    }
                    else {
                        $transaction->rollBack();
                        Yii::$app->session->addFlash('error', "Ошибка отмены");
                    }
                }
                else Yii::$app->session->addFlash('error', "Ошибка системы, не найдены данные по заявке");
            }
            else Yii::$app->session->addFlash('error', "Ошибка системы, заявка не найдена");
        }
        return $this->redirect('/advcash/advcash-api/index');
    }

}
