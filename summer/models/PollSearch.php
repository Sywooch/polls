<?php
namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

class PollSearch extends Poll
{
    public static function tableName()
    {
        return 'poll';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['poll_option.name']);
    }

    public function rules()
    {
        return [
            ['title', 'trim'],
            ['title', 'string', 'max' => 255],
            ['poll_option.name', 'trim'],
            ['poll_option.name', 'string', 'max' => 60],
            ['created_at', 'safe']
        ];
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        $labels['poll_option.name'] = 'Текст ответа';

        return $labels;
    }

    public function search($loadParams, $whereStmt = [])
    {
        $query = Poll::find()->andFilterWhere($whereStmt);

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
                    'title',
                    'people_count',
                    'created_at'
                ]
            ],
        ]);


        if ($this->load($loadParams) && $this->validate()) {
            $query->andFilterWhere(['like', 'title', $this->title]);
            $query->andFilterWhere(['like', 'created_at', $this->created_at]);

            if ($this['poll_option.name'] !== '' && $this['poll_option.name'] !== null) {
                $query->joinWith('pollOptions')->andWhere(['like', 'poll_option.name', $this['poll_option.name']]);
            }
        }

        return $activeDataProvider;
    }
}
