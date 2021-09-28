<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UsersList;

/**
 * UsersSearch represents the model behind the search form of `common\models\UsersList`.
 */
class UsersSearch extends UsersList
{
    public $countryName;
    public $parentName;
    public $regDateFrom;
    public $regDateTo;
    public $platform;
    public $structure;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'confirm', 'status', 'created_at', 'updated_at', 'order', 'parent_id', 'platform_id', 'balans', 'country_id', 'level', 'newmatrix', 'minus_balans', 'activ', 'global', 'start', 'vacant', 'time_start', 'time_personal', 'time_global', 'block', 'overbinar', 'verification', 'canplatform','platform'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'fio', 'phone', 'firstname', 'lastname', 'secondname', 'last_ip' ,'countryName','parentName','regDateFrom','regDateTo','structure'], 'safe'],
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
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UsersList::find();
        //$query->joinWith(['country']);
        //$query->joinWith(['parent']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);


        $this->load($params);

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

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'secondname', $this->secondname])
            ->andFilterWhere(['like', 'last_ip', $this->last_ip]);

        if(!empty($this->regDateFrom)){
            $query->andFilterWhere(['>=', 'time_personal', strtotime($this->regDateFrom)]);
        }

        if(!empty($this->regDateTo)){
            $query->andFilterWhere(['<=', 'time_personal', strtotime($this->regDateTo)]);
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

        $structure = $this->structure;
        if(!empty($structure)){

            $user = \common\models\UsersList::findOne(['username'=>$structure]);

            if(!empty($user)){
                $referals = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'activ'=>1])->all();
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

            }
        }

        return $dataProvider;
    }
}
