<?php namespace Octobro\FirebaseAuth\Classes;

use Firebase;
use Auth as BaseAuth;
use ApplicationException;
use RainLab\User\Models\User;

class Auth
{
    /**
     * Login using firebase
     *
     * @param string $uid
     * @return \RainLab\User\Models\User
     */
    static function login(string $uid)
    {
        $auth = Firebase::auth();

        $userRecord = $auth->getUser($uid);

        $userData = $userRecord->providerData[0];

        if (!$userData) {
            throw new ApplicationException('Login failed.');
        }

        if ($userData->email) {
            $user = User::whereEmail($userData->email)->first();
        }

        if (!isset($user) || !$user) {
            $password = str_random();
    
            $user = BaseAuth::register([
                'name' => $userData->displayName,
                'email' => $userData->email,
                'username' => $userData->email,
                'password' => $password,
                'password_confirmation' => $password,
                'phone' => $userData->phoneNumber,
            ], true);
        }

        return $user;
    }
}