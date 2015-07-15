<?php
namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

class PollSearch extends Poll
{
    public $pollOptionName;
    public $pollOptionNames;
    public $userEmail;

    public static function tableName()
    {
        return 'poll';
    }

    public function rules()
    {
        return [
            ['title', 'trim'],
            ['title', 'string', 'max' => 255],
            ['people_count', 'integer'],
            ['pollOptionName', 'trim'],
            ['pollOptionName', 'string', 'max' => 60],
            ['created_at', 'safe']
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['admin-panel'] = ['id', 'title', 'people_count', 'userEmail', 'pollOptionName', 'type', 'is_results_visible', 'created_at'];

        return $scenarios;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        $labels['pollOptionName'] = 'Текст варианта ответа';
        $labels['userEmail'] = 'Email автора';

        return $labels;
    }

    public function search($loadParams, $whereStmt = [])
    {
        $query = PollSearch::find()->andFilterWhere($whereStmt);

        if ($this->scenario === 'admin-panel') {
            $query->select(['poll.*', 'user.email', '(SELECT GROUP_CONCAT(name ORDER BY name ASC SEPARATOR ", ") FROM poll_option WHERE poll_id = poll.id) AS `pollOptionNames`'])
                ->joinWith('user', true, 'LEFT JOIN');
        }

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
                'attributes' => $this->scenario === 'admin-panel' ?
                    [
                        'id' => [
                            'asc' => ['poll.id' => SORT_ASC],
                            'desc' => ['poll.id' => SORT_DESC]
                        ],
                        'type',
                        'is_results_visible',
                        'pollOptionName' => [
                            'asc' => ['pollOptionNames' => SORT_ASC],
                            'desc' => ['pollOptionNames' => SORT_DESC]
                        ],
                        'title',
                        'people_count' => [
                            'asc' => ['poll.people_count' => SORT_ASC],
                            'desc' => ['poll.people_count' => SORT_DESC]
                        ],
                        'created_at' => [
                            'asc' => ['poll.created_at' => SORT_ASC],
                            'desc' => ['poll.created_at' => SORT_DESC]
                        ],
                        'userEmail' => [
                            'asc' => ['user.email' => SORT_ASC],
                            'desc' => ['user.email' => SORT_DESC]
                        ],
                    ] :
                    [
                        'title',
                        'people_count',
                        'created_at'
                    ]
            ],
        ]);


        if ($this->load($loadParams) && $this->validate()) {
            if ($this->scenario === 'admin-panel') {
                $query->andFilterWhere(['like', 'poll.id', $this->id]);

                if ($this->type === 'Выбор из нескольких вариантов') {
                    $this->type = Poll::TYPE_CHECKBOX;
                } else if ($this->type === 'Выбор из одного варианта') {
                    $this->type = Poll::TYPE_RADIO;
                }
                $query->andFilterWhere(['type' => $this->type]);
                if ($this->type === Poll::TYPE_CHECKBOX) {
                    $this->type = 'Выбор из нескольких вариантов';
                } else if ($this->type === Poll::TYPE_RADIO) {
                    $this->type = 'Выбор из одного варианта';
                }

                if ($this->is_results_visible === 'Видны только вам') {
                    $this->is_results_visible = 0;
                } else if ($this->is_results_visible === 'Видны всем') {
                    $this->is_results_visible = 1;
                }
                $query->andFilterWhere(['is_results_visible' => $this->is_results_visible]);
                if ($this->is_results_visible === 0) {
                    $this->is_results_visible = 'Видны только вам';
                } else if ($this->is_results_visible === 1) {
                    $this->is_results_visible = 'Видны всем';
                }

                $query->andFilterWhere(['like', 'user.email', $this->userEmail]);
            }


            $query->andFilterWhere(['like', 'title', $this->title]);
            $query->andFilterWhere(['like', 'poll.people_count', $this->people_count]);
            $query->andFilterWhere(['like', 'poll.created_at', $this->created_at]);

            if ($this->pollOptionName !== '' && $this->pollOptionName !== null) {
                $query->innerJoinWith('pollOptions')->andWhere(['like', 'poll_option.name', $this->pollOptionName]);
            }
        }

        return $activeDataProvider;
    }
}
