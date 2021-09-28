<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UsersList;

/**
 * UsersSearch represents the model behind the search form of `common\models\UsersList`.
 */
class UsersSearchFront extends UsersList
{
    public $countryName;
    public $parentName;
    public $regDateFrom;
    public $regDateTo;
    public $platform;
    public $structure;
    public $isOwn;
    public $context;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'confirm', 'status', 'created_at', 'updated_at', 'order', 'parent_id', 'platform_id', 'balans', 'country_id', 'level', 'newmatrix', 'minus_balans', 'activ', 'global', 'start', 'vacant', 'time_start', 'time_personal', 'time_global', 'block', 'overbinar', 'verification', 'canplatform','platform'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'fio', 'phone', 'firstname', 'lastname', 'secondname', 'last_ip' ,'countryName','parentName','regDateFrom','regDateTo','structure','isOwn','context'], 'safe'],
            [['w_balans', 'overdraft', 'b_balans', 'limit'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param $context
     * @return void
     */
    public function translateContext($context){
        $context = strtolower($context);
        $letters = ['zh'=>'ж', 'ch'=>'ч','sh'=>'ш','yu'=>'ю', 'ya'=>'я','iy'=>'и',
            'a' => 'а',   'b' => 'б',   'c' => 'с',
            'd' => 'д',   'e' => 'е',   'f' => 'ф',
            'g' => 'г',   'h' => 'х',  'i' => 'и',
            'j' => 'ж',   'k' => 'к',   'l' => 'л',
            'm' => 'м',   'n' => 'н',   'o' => 'о',
            'p' => 'п',   'q' => 'қ',   'r' => 'р',
            's' => 'с',   't' => 'т',   'u' => 'у',
            'v' => 'в',   'w' => 'в',   'x' => 'х',
            'y' => 'у',  'z' => 'з'];
        if(preg_match('/^[a-zA-Z]/',$context)){
            return strtr($context, $letters);
        }else{
            return $context;
        }

    }
    public function search($params)
    {
        $query = UsersList::find();
        $user_id = \Yii::$app->user->id;
        $this->load($params);
        if ($this->isOwn == 1){
            $referals = \common\models\Referals::find()->where(['parent_id'=>$user_id,'level'=>1,'activ'=>1])->all();
        }else{
            $referals = \common\models\Referals::find()->where(['parent_id'=>$user_id,'activ'=>1])->all();
        }
        //$referals = \common\models\Referals::find()->where(['parent_id'=>$user_id,'activ'=>1])->all();
        if(!empty($referals)){
            $users = [];
            foreach ($referals as $ref) {
                if(!in_array($ref['user_id'],$users)){
                    $users[] = $ref['user_id'];
                }
            }
            $query->andFilterWhere(['id'=>$users]);
        }else{
            $query->andFilterWhere(['id'=>0]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);




        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }



        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'confirm' => $this->confirm,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order' => $this->order,
            'platform_id' => $this->platform_id,
            'country_id' => $this->country_id,
            'balans' => $this->balans,
            'w_balans' => $this->w_balans,
            'level' => $this->level,
            'newmatrix' => $this->newmatrix,
            'minus_balans' => $this->minus_balans,
            //'activ' => $this->activ,
            'global' => $this->global,
            'start' => $this->start,
            'vacant' => $this->vacant,
            'time_start' => $this->time_start,
            'time_personal' => $this->time_personal,
            'time_global' => $this->time_global,
            'block' => $this->block,
            'overdraft' => $this->overdraft,
            'overbinar' => $this->overbinar,
            'verification' => $this->verification,
            'canplatform' => $this->canplatform,
            'b_balans' => $this->b_balans,
            'limit' => $this->limit,
        ]);
        $this->context = trim($this->context);

        $query->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'secondname', $this->secondname])
            ->andFilterWhere(['like', 'last_ip', $this->last_ip])
//            ->andFilterWhere(['like', 'username', $this->username])
//            ->andFilterWhere(['like', 'email', $this->email])
//            ->andFilterWhere(['like', 'fio', $this->fio])
//            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fio', self::translateContext($this->context)])
            ->orFilterWhere(['like', 'email', $this->context])
            ->orFilterWhere(['like', 'username', $this->context])
            ->orFilterWhere(['like', 'phone', $this->context]);

        if(!empty($this->regDateFrom)){
            $query->andFilterWhere(['>', 'created_at', strtotime($this->regDateFrom)]);
        }

        if(!empty($this->regDateTo)){
            $query->andFilterWhere(['<', 'created_at', strtotime($this->regDateTo)]);
        }

        $platform = $this->platform;
        if(!empty($platform)){
            if($platform == 11){
                $query->andFilterWhere(['activ'=>[0,null,2]]);
            }elseif($platform == 12){
                $query->andFilterWhere(['activ'=>1]);
            }else{
                $mats = MatrixRef::find()->where(['platform_id'=>$platform])->all();
                $users = [];
                foreach ($mats as $mat) {
                    if(!in_array($mat['user_id'],$users)){
                        $users[] = $mat['user_id'];
                    }
                }
                $query->andFilterWhere(['id'=>$users]);
            }

        }



        return $dataProvider;
    }
}
