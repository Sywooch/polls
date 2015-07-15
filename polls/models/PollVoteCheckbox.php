<?php
namespace app\models;

use yii\helpers\ArrayHelper;

class PollVoteCheckbox extends PollVote
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

            foreach ($this->$attribute as $option) {
                if (!in_array($option, $optionsIds)) {
                    $this->addError($attribute, 'Такого варианта не существует.');
                    break;
                }
            }
        }
    }

    protected function doVote()
    {
        PollOption::updateAllCounters(['people_count' => 1], ['in', 'id', $this->voteResults]);
    }
}
