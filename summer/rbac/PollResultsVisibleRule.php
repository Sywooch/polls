<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;

class PollResultsVisibleRule extends Rule
{
    public $name = 'isVisiblePollResults';

    /**
     * @param string|integer $userId the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($userId, $item, $params)
    {
        if (Yii::$app->user->isGuest) {
            return isset($params['poll']) ? $params['poll']->is_results_visible === 1 : false;
        } else {
            return isset($params['poll']) ? ($params['poll']->is_results_visible === 1 || $params['poll']->user_id === $userId) : false;
        }
    }
}
