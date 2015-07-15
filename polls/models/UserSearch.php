<?php
namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['id', 'email', 'created_at', 'updated_at'], 'safe']
        ];
    }

    public function search($loadParams)
    {
        $query = User::find();

        $activeDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'defaultPageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ],
                'attributes' => [
                    'id',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ],
        ]);


        if ($this->load($loadParams) && $this->validate()) {
            $query->andFilterWhere(['like', 'id', $this->id]);
            $query->andFilterWhere(['like', 'email', $this->email]);
            $query->andFilterWhere(['like', 'created_at', $this->created_at]);
            $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        }

        return $activeDataProvider;
    }
}
