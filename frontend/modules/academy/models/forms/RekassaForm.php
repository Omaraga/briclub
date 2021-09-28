<?php
namespace frontend\modules\academy\models\forms;
use yii\web\UploadedFile;
use yii\base\Model;
use Yii;
class RekassaForm extends Model
{
    /**
     * @var UploadedFile
    */
    public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'safe'],
            [['file'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'pdf'], 'maxSize' => 1024 * 1024 * 10]
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Файл',
        ];
    }


}