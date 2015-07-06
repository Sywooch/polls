<?php
namespace app\rbac;

use yii\rbac\Item;
use yii\rbac\Rule;

class OwnProfileRule extends Rule
{
    public $name = 'isOwnProfile';

    /**
     * @param string|integer $userId the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($userId, $item, $params)
    {
        return isset($params['id']) ? $params['id'] === $userId : false;
    }
}
