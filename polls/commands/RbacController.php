<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\rbac\PollAuthorRule;
use app\rbac\PollResultsVisibleRule;
use app\rbac\VoteRule;
use app\rbac\OwnProfileRule;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;


        $pollAuthorRule = new PollAuthorRule();
        $auth->add($pollAuthorRule);

        $pollResultsVisibleRule = new PollResultsVisibleRule();
        $auth->add($pollResultsVisibleRule);

        $voteRule = new VoteRule();
        $auth->add($voteRule);

        $ownProfileRule = new OwnProfileRule();
        $auth->add($ownProfileRule);



        $createPoll = $auth->createPermission('createPoll');
        $createPoll->description = 'Create a poll';
        $auth->add($createPoll);

        $deletePoll = $auth->createPermission('deletePoll');
        $deletePoll->description = 'Delete any poll';
        $auth->add($deletePoll);

        $deleteOwnPoll = $auth->createPermission('deleteOwnPoll');
        $deleteOwnPoll->description = 'Delete own poll';
        $deleteOwnPoll->ruleName = $pollAuthorRule->name;
        $auth->add($deleteOwnPoll);
        $auth->addChild($deleteOwnPoll, $deletePoll);

        $changePollVisibility = $auth->createPermission('changePollVisibility');
        $changePollVisibility->description = 'Change any poll\'s visibility';
        $auth->add($changePollVisibility);

        $changeOwnPollVisibility = $auth->createPermission('changeOwnPollVisibility');
        $changeOwnPollVisibility->description = 'Change own poll\'s visibility';
        $changeOwnPollVisibility->ruleName = $pollAuthorRule->name;
        $auth->add($changeOwnPollVisibility);
        $auth->addChild($changeOwnPollVisibility, $changePollVisibility);

        $votePoll = $auth->createPermission('votePoll');
        $votePoll->description = 'Vote the poll';
        $votePoll->ruleName = $voteRule->name;
        $auth->add($votePoll);

        $viewPollResults = $auth->createPermission('viewPollResults');
        $viewPollResults->description = 'View the poll\'s results';
        $auth->add($viewPollResults);

        $viewVisiblePollResults = $auth->createPermission('viewVisiblePollResults');
        $viewVisiblePollResults->description = 'View visible poll\'s results';
        $viewVisiblePollResults->ruleName = $pollResultsVisibleRule->name;
        $auth->add($viewVisiblePollResults);
        $auth->addChild($viewVisiblePollResults, $viewPollResults);

        $viewAdminPanel = $auth->createPermission('viewAdminPanel');
        $viewAdminPanel->description = 'View administrator panel';
        $auth->add($viewAdminPanel);

        $useUsersControlPanel = $auth->createPermission('useUsersControlPanel');
        $useUsersControlPanel->description = 'Use users control panel';
        $auth->add($useUsersControlPanel);

        $usePollsControlPanel = $auth->createPermission('usePollsControlPanel');
        $usePollsControlPanel->description = 'Use polls control panel';
        $auth->add($usePollsControlPanel);

        $viewProfile = $auth->createPermission('viewProfile');
        $viewProfile->description = 'View any profile';
        $auth->add($viewProfile);

        $viewOwnProfile = $auth->createPermission('viewOwnProfile');
        $viewOwnProfile->description = 'View own profile';
        $viewOwnProfile->ruleName = $ownProfileRule->name;
        $auth->add($viewOwnProfile);
        $auth->addChild($viewOwnProfile, $viewProfile);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete any user';
        $auth->add($deleteUser);


        $guest = $auth->createRole('guest');
        $auth->add($guest);
        $auth->addChild($guest, $createPoll);
        $auth->addChild($guest, $votePoll);
        $auth->addChild($guest, $viewVisiblePollResults);
        $auth->addChild($guest, $deleteOwnPoll);
        $auth->addChild($guest, $changeOwnPollVisibility);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $guest);
        $auth->addChild($user, $viewOwnProfile);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $viewPollResults);
        $auth->addChild($admin, $deletePoll);
        $auth->addChild($admin, $changePollVisibility);
        $auth->addChild($admin, $viewAdminPanel);
        $auth->addChild($admin, $useUsersControlPanel);
        $auth->addChild($admin, $usePollsControlPanel);
        $auth->addChild($admin, $viewProfile);
        $auth->addChild($admin, $deleteUser);
    }
}
