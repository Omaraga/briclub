<?php
namespace backend\controllers;

use backend\models\UploadImage;
use common\models\Actions;
use common\models\Data;
use common\models\Data2;
use common\models\MatrixRef;
use common\models\MatrixStart;
use common\models\Referals;
use common\models\TelegramAnswersQuestions;
use common\models\TelegramMessages;
use common\models\TelegramQuestionnaires;
use common\models\TelegramQuestions;
use common\models\TelegramSteps;
use common\models\TelegramUsers;
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
class TelegaController extends Controller
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
                        'actions' => ['index', 'save-step'],
                        'allow' => true,
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['post']
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */

    public $enableCsrfValidation = false;


    public function actionIndex()
    {
        $startMessage =
        '
            Здравствуйте! Вас приветствует информационный чат–бот «Дом твоей мечты».

Покупка своей квартиры или дома - это всегда большое событие, которое определяет комфорт и благополучие на многие годы! 

А теперь представьте себе… Чтобы вам приобрести жилье, вам нужно сделать первый и единственный взнос всего 103$

«Такого не бывает» - скажете вы!
Бывает!

Далее вы узнаете, как благодаря легкой, простой и понятной системе вы сможете стать собственником любой недвижимости на ваш вкус!
Причем, вам не нужно для этого никуда ехать или идти. Наш электронный помощник будет отправлять нужный контент по вашей команде и поэтапно знакомить с нашим предложением.
Ну что, начинаем?

Чтобы начать процесс нажмите команду ✅ Далее
        ';
        $telegram = Yii::$app->telegram;
        $message = $telegram->input->message->text;
        $tg_user = $telegram->input->message->getFrom();
        $text = '';
        $step = 1;

        $user = TelegramUsers::find()->where(['username' => $tg_user->username])->one();
        $questionnaire = TelegramQuestionnaires::find()->where(['user_id' => $user->id])->one();

        if($user == null){
            // создание нового пользователя

            $newUser = new TelegramUsers();
            $newUser->first_name = $tg_user->first_name;
            $newUser->last_name = $tg_user->last_name;
            $newUser->username = $tg_user->username;
            $newUser->language_code = $tg_user->language_code;
            $newUser->created_at = time();
            $newUser->save(false);

            if($questionnaire == null){
                // создание его анкеты
                $questionnaire = new TelegramQuestionnaires();
                $questionnaire->user_id = $newUser->id;
                $questionnaire->save(false);
            }
        }

        $keyboard = json_encode($keyboard = [
            'keyboard' => [
                [['text' => '✅ Далее', 'callback_data' => '/next']],[['text' => '❓Задать вопрос', 'callback_data' => '/attention']]
            ] ,

            'resize_keyboard' => true,
            'one_time_keyboard' => true,
            'selective' => true
        ],true);

        switch ($message){
            case '/start':
                $text = $startMessage;
                break;
            case '✅ Далее':
                if($user != null){
                    $step = TelegramMessages::find()->where(['user_id' => $tg_user->id, 'message' => '✅ Далее'])->count('*') + 1; // +1 потому что нужно получить следующий шаг
                    if($step > 26){
                        $text = 'Спасибо за ознакомление с нашей программой! Если у Вас остались вопросы, то Вы можете задать их нашей технической поддержке. 
                        Нажмите ❓Задать вопрос';
                    }
                    else{
                        $text = TelegramSteps::find()->where(['step' => $step])->one()['step_text'];
                    }
                }
                break;
            case '❓Задать вопрос':
                $text = 'Введите свой вопрос';
                break;
            default:
                    if(TelegramMessages::find()->where(['user_id' => $tg_user->id])->orderBy(['id' => SORT_DESC])->one()['message'] == '❓Задать вопрос'){
                    $question = new TelegramQuestions();
                    $question->text = $message;
                    $question->chat_id = $telegram->input->message->chat->id;
                    $question->created_at = time();
                    $question->save(false);
                    $text = 'Спасибо за обращение, ожидайте ответ от нашей технической поддержки!
        Для продолжения нажмите ✅ Далее';
                }
                else{
                    $step = TelegramMessages::find()->where(['user_id' => $tg_user->id, 'message' => '✅ Далее'])->count('*');
                    if($step >= 9 && $step <= 11){
                        if($step == 9){
                            $questionnaire->full_name = $message;
                        }
                        else if($step == 10){
                            $questionnaire->city = $message;
                        }
                        else if($step == 11){
                            $questionnaire->programm_source = $message;
                        }
                        $step++;
                        $text = TelegramSteps::find()->where(['step' => $step])->one()['step_text'];

                        $questionnaire->save(false);
                        $newMessage = new TelegramMessages();
                        $newMessage->message = '✅ Далее';
                        $newMessage->created_at = time();
                        $newMessage->chat_id = $telegram->input->message->chat->id;
                        $newMessage->user_id = $tg_user->id;
                        $newMessage->save(false);
                    }
                    else{
                        $text = 'Введена неизвестная команда. Используйте команды ниже:
                    ✅ Далее — следующий шаг
                    ❓Задать вопрос — задайте вопрос';
                    }
                }
                break;
        }

        $newMessage = new TelegramMessages();
        $newMessage->message = $message;
        $newMessage->created_at = time();
        $newMessage->chat_id = $telegram->input->message->chat->id;
        $newMessage->user_id = $tg_user->id;
        $newMessage->save(false);


        $telegram->sendMessage([
            'chat_id' => $telegram->input->message->chat->id,
            'text' => $text,
            'reply_markup' => $keyboard
        ]);
    }


    public function actionSaveStep(){
        $tg_step = new TelegramSteps();
        $tg_step->created_at = time();
        $tg_step->step = 12;
        $tg_step->step_text = 'Приветствуем вас в заключительном видео. Я думаю, что, пройдя весь наш интерактив, вы уже примерно понимаете, откликается ли тебе наша партнерская программа «SHANYRAK+»
Если вам интересно то, о чем мы здесь говорили, то обязательно свяжитесь со своим наставником. Нажав кнопку «получить личную консультацию». Он ответит на все вопросы и поможет зарегистрироваться. На этом все. Я надеюсь, что совсем скоро мы увидимся снова. Я желаю вам всего самого наилучшего и до встречи.

Пожалуйста, свяжитесь с человеком, пригласившим вас пройти этот интерактив или нажмите ссылку ниже для связи в WhatsApp с нашим консультантом.  Поделитесь своим мнением об этом интерактиве, задайте оставшиеся вопросы. Вам порекомендуют дальнейшие шаги и помогут активировать контракт.
Для более полной информации у нас ежедневно проводятся живые вебинары. Там вы сможете увидеть действующих партнеров компании и получить более полную информацию. Расписание вебинаров вы также можете получить у нашего консультанта по данной ссылке (ссылка на чат WhatsApp с консультантом)

Спасибо вам за время, которые мы провели вместе. Надеюсь, это было полезно!
Всего наилучшего и...  остаемся на связи!
';
        if($tg_step->validate()){
            $tg_step->save();
            echo "<pre>" . $tg_step->step;
        }
    }
}