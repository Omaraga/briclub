<?php
namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AvatarForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function upload($userId, $data)
    {
        $name = md5($userId + time()) . '.png';
        $image_name = Yii::$app->basePath . '/web/docs/avatars/' . $name;
        file_put_contents($image_name, $data);

        $premium = \common\models\Premiums::findOne(['user_id' => $userId]);
        if ($premium){
            if($premium->img_source != null){
                unlink(Yii::$app->basePath . '/web/' . $premium->img_source);
            }
            $premium->img_source = '/docs/avatars/' . $name;
            $premium->save();
            return true;
        }
        else{
            return false;
        }
    }
}