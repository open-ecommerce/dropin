<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Customers;

/**
 * CustomersSearch represents the model behind the search form about `app\models\Customers`.
 */
class CustomersSearch extends Customers
{
    /**
     * @inheritdoc
     */
    
    public $Dropin;
    public $Doctor;
    public $Lawyer;
    public $DropinTime;
    public $DropinDate;
    public $Language;
    
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Name', 'Gender', 'Eligible', 'Comments', 'CommentsOld', 'ConfirmationDate', 'Interpreter'], 'safe'],
            [['Doctor', 'Lawyer', 'Dropin', 'DropinTime', 'DropinDate'], 'safe'],
            [['Language'], 'safe'],
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
        $query = Customers::find()->distinct();
        $query->joinWith(['attendance']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['Name' => SORT_ASC]],
            'pagination' => ['pageSize' => 50],
        ]);

        // Require at least 3 chars on text fields, or an exact match on ID/Gender/Eligible
        $search = $params['CustomersSearch'] ?? [];
        $exactFields = ['ID', 'Gender', 'Eligible'];
        $hasExact = (bool) array_filter(array_intersect_key($search, array_flip($exactFields)));
        $hasText = (bool) array_filter($search, fn($v) => mb_strlen((string) $v) >= 3);
        if (!$hasExact && !$hasText) {
            $query->where('0=1');
            return $dataProvider;
        }

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        
        // order by doctor
        $dataProvider->sort->attributes['doctor'] = [
            'asc' => ['Attribute.Doctor' => SORT_ASC],
            'desc' => ['Attribute.Doctor' => SORT_DESC],
        ];                
        
        
        $query->andFilterWhere([
            'ID' => $this->ID,
            'ConfirmationDate' => $this->ConfirmationDate,
            'Doctor' => $this->Doctor,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Gender', $this->Gender])
            ->andFilterWhere(['like', 'Eligible', $this->Eligible])
            ->andFilterWhere(['like', 'Eligible', $this->Doctor])
            ->andFilterWhere(['like', 'Comments', $this->Comments])
            ->andFilterWhere(['like', 'CommentsOld', $this->CommentsOld])
            ->andFilterWhere(['like', 'Interpreter', $this->Interpreter]);

        return $dataProvider;
    }
}
