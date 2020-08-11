<?php

namespace App\Security\Voter;

use App\Util\SettingUtil;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BoardCreateVoter extends Voter
{
    private $settingUtil;

    public function __construct(SettingUtil $settingUtil)
    {
        $this->settingUtil = $settingUtil;
    }
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CAN_CREATE_BOARD']);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        switch ($attribute) {
            case 'CAN_CREATE_BOARD':
                if (!$user instanceof UserInterface &&
                    !$this->settingUtil->setting('anon_can_create_board')) {
                    return false;
                }
                return true;
                break;
        }

        return false;
    }
}
