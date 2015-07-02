<?php
namespace app\models;

use yii\base\Model;
use yii\web\ServerErrorHttpException;

abstract class PollVote extends Model
{
    /**
     * Result or results of the poll
     * @var array|string
     */
    public $voteResults;
    public $pollInfo;

    protected $poll;


    public function __construct($poll, $config = [])
    {
        parent::__construct($config);

        $this->poll = $poll;
    }

    public static function getInstance($poll)
    {
        if ($poll->type === Poll::TYPE_RADIO) {
            return new PollVoteRadio($poll);
        } else if ($poll->type === Poll::TYPE_CHECKBOX) {
            return new PollVoteCheckbox($poll);
        } else {
            throw new ServerErrorHttpException('Запрос с таким типом не может быть обработан.');
        }
    }

    public function rules()
    {
        return [
            ['voteResults', 'required', 'message' => 'Выберите хотя бы один вариант.'],
            ['pollInfo', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'voteResults' => 'Варианты ответа'
        ];
    }

    public function getTitle()
    {
        return $this->poll->title;
    }

    public function getId()
    {
        return $this->poll->id;
    }

    public function getFormattedOptions()
    {
        $options = $this->poll->pollOptions;

        $formattedOptions = [];
        foreach ($options as $option) {
            $formattedOptions[$option->id] = $option->name;
        }


        return $formattedOptions;
    }

    public function validateUser()
    {
        return $this->pollInfo === '0';
    }

    abstract public function vote();
}
