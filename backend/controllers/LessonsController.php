<?php

namespace backend\controllers;

use common\models\Parts;
use common\models\User;
use common\models\UserHomeworks;
use common\models\UserLessons;
use frontend\models\forms\HomeworkForm;
//use Vimeo\Vimeo;
use Yii;
use common\models\Lessons;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LessonsController implements the CRUD actions for Lessons model.
 */
class LessonsController extends Controller
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
     * Lists all Lessons models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        //$client = new Vimeo("8e7ab3acec831d6e969df34cc0dfa28421b21de8", "GDHZw32LeyHUbYViE/18IlyEW5G9+8ka/F39dbw6lNQ2lbzFkVquS27th/WbylDgH5yGvoAn7QhdRqSjL0msc34DckPhD16SDhJl2Sb3DQ4aJ2JJTr7PhqlMFQNSVyTH", "26c1baa004a05981aba142f44989b275");

        /*$lessons = Lessons::find()->all();
        foreach ($lessons as $lesson) {
            if(!empty($lesson->vimeo_link)){
                $vi_id = $lesson->vimeo_link;
                $vi_id = explode('/',$vi_id);
                $vi_id = $vi_id[count($vi_id)-1];

                $response = $client->request('/videos/'.$vi_id.'/pictures', array(), 'GET');
                if(!empty($response)){
                    if(isset($response['body']['data'][0]['sizes'][3]['link'])){
                        $icon = $response['body']['data'][0]['sizes'][3]['link'];
                        $lesson->icon = $icon;
                    }
                }
                $lesson->save();
            }

        }
        exit;*/

        $dataProvider = new ActiveDataProvider([
            'query' => Lessons::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lessons model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new HomeworkForm();

        if($model->load(Yii::$app->request->post()) and $model->validate()){


            $user_h = new UserHomeworks();
            $user_h->is_admin = 1;
            $user_h->user_id = User::find()->where(['username'=>$model->user_id])->one()['id'];
            if(!empty($user_h->user_id)){
                $user_h->lesson_id = $id;
                $user_h->text = $model->text;

                $user_h->time = time();
                if($user_h->save()){
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Сообщение отправлено!'));
                }else{
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, попробуйте еще раз!'));
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Пользователь не найден, попробуйте еще раз!'));
            }

        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Lessons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new Lessons();

        if ($model->load(Yii::$app->request->post())) {
            $lesson = Lessons::find()->where(['part_id'=>$model->part_id])->orderBy('position desc')->one();
            if(!empty($lesson)){
                $model->position = $lesson['position'] + 1;
            }else{
                $model->position = 1;
            }
//            if(!empty($model->vimeo_link)){
//                $client = new Vimeo("8e7ab3acec831d6e969df34cc0dfa28421b21de8", "GDHZw32LeyHUbYViE/18IlyEW5G9+8ka/F39dbw6lNQ2lbzFkVquS27th/WbylDgH5yGvoAn7QhdRqSjL0msc34DckPhD16SDhJl2Sb3DQ4aJ2JJTr7PhqlMFQNSVyTH", "26c1baa004a05981aba142f44989b275");
//
//                $vi_id = $model->vimeo_link;
//                $vi_id = explode('/',$vi_id);
//                $vi_id = $vi_id[count($vi_id)-1];
//                $response = $client->request('/videos/'.$vi_id.'/pictures', array(), 'GET');
//                if(!empty($response)){
//                    if(isset($response['body']['data'][0]['sizes'][3]['link'])){
//                        $icon = $response['body']['data'][0]['sizes'][3]['link'];
//                        $model->icon = $icon;
//                    }
//                }
//            }

            if(!empty($model->youtube_link)){

                $vi_id = $model->youtube_link;
                $vi_id = explode('/',$vi_id);
                $vi_id = $vi_id[count($vi_id)-1];
                $icon = "https://img.youtube.com/vi/$vi_id/0.jpg";
                $model->icon = $icon;
            }

            $model->save();
            $this->saveImage($model);
            return $this->redirect(['/parts/view', 'id' => $model->part_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    /**
     * Updates an existing Lessons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

//            if(!empty($model->vimeo_link)){
//                $client = new Vimeo("8e7ab3acec831d6e969df34cc0dfa28421b21de8", "GDHZw32LeyHUbYViE/18IlyEW5G9+8ka/F39dbw6lNQ2lbzFkVquS27th/WbylDgH5yGvoAn7QhdRqSjL0msc34DckPhD16SDhJl2Sb3DQ4aJ2JJTr7PhqlMFQNSVyTH", "26c1baa004a05981aba142f44989b275");
//                $vi_id = $model->vimeo_link;
//                $vi_id = explode('/',$vi_id);
//                $vi_id = $vi_id[count($vi_id)-1];
//
//                $response = $client->request('/videos/'.$vi_id.'/pictures', array(), 'GET');
//                if(!empty($response)){
//                    if(isset($response['body']['data'][0]['sizes'][3]['link'])){
//                        $icon = $response['body']['data'][0]['sizes'][3]['link'];
//                        $model->icon = $icon;
//                    }
//                }
//            }

            if(!empty($model->youtube_link)){

                $vi_id = $model->youtube_link;
                $vi_id = explode('/',$vi_id);
                $vi_id = $vi_id[count($vi_id)-1];
                $icon = "https://img.youtube.com/vi/$vi_id/0.jpg";
                $model->icon = $icon;
            }
            $model->save();
            $this->saveImage($model);
            return $this->redirect(['/parts/view', 'id' => $model->part_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    public function saveImage(Lessons $model){

        $file = UploadedFile::getInstance($model, 'image');
        $link = null;
        if ($file && $file->tempName) {
            $model->image = $file;
            $rand = rand(900000,9000000);
            $dir = Yii::getAlias('@frontend/web/img/lesson_images/');
            $dir2 = Yii::getAlias('img/lesson_images/');
            $fileName = $rand. '.' . $model->image->extension;
            $model->image->saveAs($dir . $fileName);
            $model->image = $fileName; // без этого ошибка
            $link = '/'.$dir2 . $fileName;
            $model->image_url = $link;
            $model->save(false);
        }

    }
    /**
     * Deletes an existing Lessons model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = $this->findModel($id);
        $part_id = $model->part_id;
        $model->delete();

        return $this->redirect(['/parts/view', 'id' => $part_id]);
    }

    public function actionPositionUp($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $lesson = Lessons::findOne($id);
        $pred_lesson = Lessons::find()->where(['part_id'=>$lesson['part_id']])->andWhere(['<','position',$lesson['position']])->orderBy('position desc')->one();
        $this_pos = $pred_lesson['position'];
        $pred_lesson = Lessons::findOne($pred_lesson['id']);
        $pred_lesson->position = $lesson['position'];
        $pred_lesson->save();

        $lesson->position = $this_pos;
        $lesson->save();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionPositionDown($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $lesson = Lessons::findOne($id);
        $next_lesson = Lessons::find()->where(['part_id'=>$lesson['part_id']])->andWhere(['>','position',$lesson['position']])->orderBy('position asc')->one();
        $this_pos = $next_lesson['position'];
        $next_lesson = Lessons::findOne($next_lesson['id']);
        $next_lesson->position = $lesson['position'];
        $next_lesson->save();

        $lesson->position = $this_pos;
        $lesson->save();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Finds the Lessons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lessons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lessons::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
