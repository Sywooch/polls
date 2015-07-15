<?php
namespace app\models;

use yii\helpers\ArrayHelper;

class PollVoteRadio extends PollVote
{
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = ['voteResults', 'validateExisting'];
        return $rules;
    }

    public function validateExisting($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $optionsIds = ArrayHelper::getColumn($this->poll->pollOptions, 'id');

            if (!in_array($this->$attribute, $optionsIds)) {
                $this->addError($attribute, 'Такого варианта не существует.');
            }
        }
    }

    protected function doVote()
    {
        PollOption::findOne($this->voteResults)->updateCounters(['people_count' => 1]);
    }
}
