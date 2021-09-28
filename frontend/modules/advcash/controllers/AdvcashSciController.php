<?php
namespace frontend\modules\advcash\controllers;

use common\models\Actions;
use Yii;
use yii\web\Controller;
use frontend\modules\advcash\models\AdvcashDeposit;
use frontend\modules\advcash\models\AdvcashSci;
use frontend\models\Activities;
use frontend\models\Deposit;
use frontend\models\UserInfo;

/**
 * Контроллер для пополнения
 */
class AdvcashSciController extends Controller
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
    
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $dataProvider = AdvcashDeposit::find();
            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Создает пополнение
     */
    public function actionCreate()
    {
        $model = new AdvcashSci();
               
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->getPaymentData())
            {
                $activity = new Actions();
                $activity->user_id = Yii::$app->user->id;
                $activity->time = time();
                $activity->type = AdvcashDeposit::ACTIVITY_TYPE;
                $activity->title = 'Пополнение баланса в Advcash';
                $activity->sum = $model->ac_amount;
                $activity->status = 3;
                $activity->comment = $model->ac_order_id;
                $activity->save();
                return \frontend\modules\advcash\widgets\AdvcashWidget::widget(['model' => $model, 'result' => true]);
            }
            else {
                return \frontend\modules\advcash\widgets\AdvcashWidget::widget(['model' => $model, 'result' => false]);
            }
        }
        else {
            return \frontend\modules\advcash\widgets\AdvcashWidget::widget(['model' => $model, 'result' => false]);
        }
    }

    /**
     * Метод который получает ответ от адвакеша, нужен только чтобы вывести страничку
     * Реальные данные приходят на метод actionStatus
     */
    public function actionSuccess()
    {
        if (Yii::$app->request->isPost) {
            $payment = AdvcashDeposit::getById(Yii::$app->request->post('ac_order_id'));
            if ($payment !== null && $payment->status == AdvcashDeposit::STATUS_COMPLETED) {
                /*$payment->success_response = json_encode(Yii::$app->request->post());
                $payment->save();
                $payment_helper = new AdvcashSci();*/
                Yii::$app->session->setFlash('success', 'Платеж успешно прошел');
            }
            else {
                Yii::$app->session->setFlash('error', 'Ошибка сервера, если средства были отправлены, обратитесь в администрацию портала');
            }
        }
        $this->redirect(['/user/user/account']);
    }

    /**
     * Метод который получает ответ от адвакеша, также выводит публичную страничку
     * Дополнительные данные приходят на метод actionStatus
     */
    public function actionFailure()
    {
        if (Yii::$app->request->isPost) {
            $payment = AdvcashDeposit::getById(Yii::$app->request->post('ac_order_id'));
            if ($payment !== null) {
                $payment->failure_response = json_encode(Yii::$app->request->post());
                $payment->status = AdvcashDeposit::STATUS_ERROR;
                $payment->save();
                $activity = Activities::find()->where(['link_id' => $payment->id])->andWhere(['type' => AdvcashDeposit::ACTIVITY_TYPE])->one();
                //Ставит статус = 1
                if ($activity !== null) {
                    $activity->setCanceled();
                    $activity->save();
                }
                Yii::$app->session->setFlash('error', 'Ошибка, платеж не прошел');
            }
            else {
                Yii::$app->session->setFlash('error', 'Ошибка, попробуйте позже');
            }
        }        
        $this->redirect(['/user/user/account']);
    }

    /**
     * Сюда приходят все данные от адвакеша
     */
    public function actionStatus()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $payment = AdvcashDeposit::getById($post['ac_order_id']);
            if ($payment !== null) {
                $payment->response = json_encode($post);
                $payment->payment_id = $post['ac_transfer'];
                $payment->save();
                $payment_helper = new AdvcashSci();
                //Проверяем хеш ответа
                if ($payment_helper->checkHash($post)) {
                    if ($post['ac_transaction_status'] == 'COMPLETED') {
                        $payment->status = AdvcashDeposit::STATUS_COMPLETED;
                        //Делаешь вещи о том что платеж пришел
                        $user = UserInfo::find()->where(['user_id' => $payment->user_id])->one();
                        $user->advcash += $payment->sum;
                        $user->save();
                        $activity = Activities::find()->where(['link_id' => $payment->id])->andWhere(['type' => AdvcashDeposit::ACTIVITY_TYPE])->one();
                        $activity->setComplete();
                        $activity->save();
                        Yii::$app->session->setFlash('success', 'Платеж успешно прошел');
                    }
                    $payment->save();                    
                }
            }
        }
    }
}
