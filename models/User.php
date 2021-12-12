<?php

namespace app\models;

use app\entity\Account;
use app\repository\AccountRepository;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $last_name;
    public $first_name;
    public $middle_name;
    public $username;
    public $password;
    public $email;
    public $avatar_id;

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return new static(AccountRepository::findOneAccount(['id' => $id]));
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return new static(AccountRepository::findOneAccount(['username' => $username]));
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return new static(AccountRepository::findOneAccount(['email' => $email]));
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function isAvailable($access){
        $result = false;
        $user = AccountRepository::findOneAccount(['id' => $this->id]);
        foreach ($user->roles as $role){
            foreach ($role->accesses as $one_access){
                if ($one_access->access === $access){
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }
}
