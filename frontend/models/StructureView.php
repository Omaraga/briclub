<?php


namespace frontend\models;
use yii\base\Model;
use Yii;
use \common\models\Matblocks;
use \common\models\MatParents;
use \common\models\MatrixRef;
use \common\models\Referals;

class StructureView extends Model
{
    public $parents;
    public $user;
    public $matrixRef;
    public $ownRefsSize;
    public $refSize;
    public $matrixBlock;
    public $shoulder1;
    public $shoulder1_1;
    public $shoulder1_2;
    public $shoulder2;
    public $shoulder2_1;
    public $shoulder2_2;
    public $avatar;

    public function __construct($matrixRefId = null, $platformId = null, $isShoulder = false)
    {
        $this->user = Yii::$app->user->identity;
        $this->matrixRef = $matrixRefId;
        if (!empty($matrixRefId)){
            $this->parents = MatParents::getParents($matrixRefId);
            $this->matrixRef = MatrixRef::findOne($matrixRefId);
        }else{
            $this->matrixRef = MatrixRef::find()->where(['user_id'=>$this->user['id'],'platform_id'=>$platformId])->orderBy('id asc')->one();
        }
        $this->refSize = MatParents::find()->where(['parent_id'=>$this->matrixRef['id']])->count();
        $this->ownRefsSize = Referals::find()->where(['parent_id'=>$this->user['id'],'level'=>1,'activ'=>1])->count();
        $this->matrixBlock = Matblocks::find()->where(['user_id'=>$this->user['id'],'mat_id'=>$this->matrixRef['id']])->one();
        if ($isShoulder == true){
            $this->shoulder1 = null;
            $this->shoulder1_1 = null;
            $this->shoulder1_2 = null;
            $this->shoulder2 = null;
            $this->shoulder2_1 = null;
            $this->shoulder2_2 = null;
        }else{
            $this->shoulder1 = new StructureView($this->matrixRef['shoulder1'], $platformId, true);
            $this->shoulder1_1 = new StructureView($this->matrixRef['shoulder1_1'], $platformId, true);
            $this->shoulder1_2 = new StructureView($this->matrixRef['shoulder1_2'], $platformId, true);
            $this->shoulder2 = new StructureView($this->matrixRef['shoulder2'], $platformId, true);
            $this->shoulder2_1 = new StructureView($this->matrixRef['shoulder2_1'], $platformId, true);
            $this->shoulder2_1 = new StructureView($this->matrixRef['shoulder2_2'], $platformId, true);
        }

    }

    public function getStructure($matrixRefId){

    }
}