<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use common\models\Countries;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CountriesController implements the CRUD actions for Countries model.
 */
class CountriesController extends Controller
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

    /**
     * Lists all Countries models.
     * @return mixed
     */
    public function actionIndex($title=null)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        /*$text = "Абхазия,Австралия,Австрия,Азербайджан,Албания,Алжир,Ангола,Андорра,Антигуа и Барбуда,Аргентина,Армения,Афганистан,Багамские Острова,Бангладеш,Барбадос,Бахрейн,Белиз,Белоруссия,Бельгия,Бенин,Болгария,Боливия,Босния и Герцеговина,Ботсвана,Бразилия,Бруней,Буркина-Фасо,Бурунди,Бутан,Вануату,Ватикан,Великобритания,Венгрия,Венесуэла,Восточный Тимор,Вьетнам,Габон,Гаити,Гайана,Гамбия,Гана,Гватемала,Гвинея,Гвинея-Бисау,Германия,Гондурас,Государство Палестина,Гренада,Греция,Грузия,Дания,Джибути,Доминика,Доминиканская Республика,ДР Конго,Египет,Замбия,Зимбабве,Израиль,Индия,Индонезия,Иордания,Ирак,Иран,Ирландия,Исландия,Испания,Италия,Йемен,Кабо-Верде,Камбоджа,Камерун,Канада,Катар,Кения,Кипр,Кирибати,Китай,КНДР,Колумбия,Коморские Острова,Коста-Рика,Кот-д'Ивуар,Куба,Кувейт,Лаос,Латвия,Лесото,Либерия,Ливан,Ливия,Литва,Лихтенштейн,Люксембург,Маврикий,Мавритания,Мадагаскар,Малави,Малайзия,Мали,Мальдивские Острова,Мальта,Марокко,Маршалловы Острова,Мексика,Мозамбик,Молдавия,Монако,Монголия,Мьянма,Намибия,Науру,Непал,Нигер,Нигерия,Нидерланды,Никарагуа,Новая Зеландия,Норвегия,ОАЭ,Оман,Пакистан,Палау,Панама,Папуа - Новая Гвинея,Парагвай,Перу,Польша,Португалия,Республика Конго,Республика Корея,Руанда,Румыния,Сальвадор,Самоа,Сан-Марино,Сан-Томе и Принсипи,Саудовская Аравия,Северная Македония,Сейшельские Острова,Сенегал,Сент-Винсент и Гренадины,Сент-Китс и Невис,Сент-Люсия,Сербия,Сингапур,Сирия,Словакия,Словения,Соломоновы Острова,Сомали,Судан,Суринам,США,Сьерра-Леоне,Таджикистан,Таиланд,Танзания,Того,Тонга,Тринидад и Тобаго,Тувалу,Тунис,Туркмения,Турция,Уганда,Узбекистан,Украина,Уругвай,Федеративные Штаты Микронезии,Фиджи,Филиппины,Финляндия,Франция,Хорватия,ЦАР,Чад,Черногория,Чехия,Чили,Швейцария,Швеция Шри-Ланка,Эквадор,Экваториальная Гвинея,Эритрея,Эсватини,Эстония,Эфиопия,ЮАР,Южная Осетия,Южный Судан,Ямайка,Япония";
        $text_array = explode(',',$text);
        foreach ($text_array as $item) {
            $country = new Countries();
            $country->title = $item;
            $country->save();
        }
        exit;*/
        $error = null;
        if(!empty($title)){
            $dataProvider = new ActiveDataProvider([
                'query' => Countries::find()->where(['title'=>$title]),
            ]);
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => Countries::find(),
                'pagination' => [
                    'pageSize' => 200,
                ],
            ]);
        }


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'error' => $error,
            'title' => $title,
        ]);
    }

    /**
     * Displays a single Countries model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
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

    /**
     * Creates a new Countries model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new Countries();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Countries model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('/countries');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Countries model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Countries model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Countries the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Countries::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
