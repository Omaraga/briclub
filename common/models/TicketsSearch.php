<?php


namespace common\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;

class TicketsSearch extends TicketsList
{
    public $title;
    public $dateFrom;
    public $dateTo;
    public $username;
    public $status;

    public function rules()
    {
        return [
            [['time', 'status'], 'integer'],
            [['title', 'username'], 'string', 'max' => 255],
            [['dateFrom', 'dateTo'], 'safe'],
        ];
    }

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
        $query = TicketsList::find();
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
            'status' => $this->status
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        $user = User::findOne(['username' => $this->username]);
        if($user != null){
            $query->andFilterWhere(['user_id' => $user->id]);
        }

        $query->andFilterWhere(['like', 'title', $this->title]);

        if(!empty($this->dateFrom)){
            $query->andFilterWhere(['>', 'time', strtotime($this->dateFrom)]);
        }

        if(!empty($this->dateTo)){
            $query->andFilterWhere(['<', 'time', strtotime($this->dateTo)]);
        }

        return $dataProvider;
    }
}