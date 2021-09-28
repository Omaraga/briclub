<?

use common\models\MatrixRef;
use common\models\User;
use common\models\UserPlatforms;
if(Yii::$app->user->isGuest){
    $link = '/profile';
}else{
    //$user_lesson = \common\models\UserLessons::find()->where(['user_id'=>Yii::$app->user->id,'status'=>3])->orderBy('id desc')->one();
    $link = '/courses';
}
$menu = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;

$user = Yii::$app->user->identity;


?>
