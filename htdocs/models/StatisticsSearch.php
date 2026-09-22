<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Statistics;

/**
 * StatisticsSearch represents the model behind the search form about `app\models\Attendance`.
 */
class StatisticsSearch extends Statistics
{
    /**
     * @inheritdoc
     */

    public $Clients;

    public function rules()
    {
        return [
          [['DropinDate', 'MainEntrance', 'A-L', 'M-Z', 'SeenDoctor', "SeenLawyer", 'Total'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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

        $query = Statistics::find();
        $query->all();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        return $dataProvider;
    }







}
