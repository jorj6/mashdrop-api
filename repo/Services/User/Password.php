<?php //-->

namespace Services\User;

use Modules\Auth;
use Modules\Helper;
use Modules\Mail;
use Services\User;

/**
 * Service User Password
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Password
{
    /* Constants
    --------------------------------------------*/
    const HASH_DATE_PATTERN = 'jyn';
    const HASH_SEPERATOR = '.';

    const URL_PREFIX = 'password/reset';
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function baseChange($data)
    {
        $required = array(
            'id',
            'password',
            'confirm_password');

        // check required
        if($field = Helper::getMissingFields($data, $required)) {
            return Helper::error('USER_PASS_REQUIRED',
                $field . ' required, empty given');
        }

        // validation
        if($data['password'] != $data['confirm_password']) {
            return Helper::error('USER_PASS_NOT_MATCHED',
                'password not matched');
        }

        return User::update(array(
            'password' => $data['password']
        ), $data['id']);
    }

    public static function change($data)
    {

        $required = array('old_password');

        // check required
        if($field = Helper::getMissingFields($data, $required)) {
            return Helper::error('USER_PASS_REQUIRED',
                $field . ' required, empty given');
        }

        // get user
        $userId = Auth::getUser()['id'];
        $data['id'] = $userId;

        $user = User::baseGet($userId);
        if(sha1($data['old_password']) != $user['password']) {
            return Helper::error('USER_OLD_PASS_NOT_MATCHED',
                'old password does not match');
        }

        // call base change
        return self::baseChange($data);
    }

    public static function requestReset($email)
    {
        if(empty($email)) {
            return Helper::error('USER_PASS_RESET_REQUIRED',
                'email required, empty given');
        }

        // check email
        $user = User::get(array('filters' => array(
            'email' => $email)));

        if(empty($user)) {
            return Helper::error('USER_PASS_EMAIL_ERROR',
                'requested password reset for ' . $email . ' not registered');
        }

        // generate hash
        $hash = self::getHash($user);

        // email link to confirm
        if(!self::sendResetEmail($user, $hash)) {
            return Helper::panic('USER_PASS_EMAIL_FAILED',
                'unable to send mail');
        }

        return array(
            'hash' => $hash,
            'msg' =>
                'email successfully sent. this token is valid only for the day;'
        );
    }

    public static function confirmResetHash($hash)
    {
        // decode hash
        $data = explode(self::HASH_SEPERATOR, $hash);
        $day = (string) date(self::HASH_DATE_PATTERN);
        $head = sha1($data[1] . $day);

        // validate hash
        if(count($data) != 3 || $data[0] != $head) {
            return Helper::error('USER_PASS_TOKEN_ERROR',
                'invalid reset token');
        }

        // search id
        $user = User::getClientById($data[1]);
        if(empty($user)) {
            return Helper::error('USER_PASS_TOKEN_ERROR',
                'user not found');
        }

        // success
        return $user;
    }

    public static function updateByHash($hash, $data = array())
    {
        $user = self::confirmResetHash($hash);
        if(isset($user['error'])) {
            return $user;
        }

        // inject id
        $data['id'] = $user['id'];
        return self::baseChange($data);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function getHash($user)
    {
        // NOTE this is bad this might produce collission
        // 1st and 2nd part is token validator
        // 3rd part is our user validator
        // hash is valid only for the day
        $head = sha1($user['id'] . (string) date(self::HASH_DATE_PATTERN));
        $body = sha1($user['password']. $user['updated_at']);

        return implode(self::HASH_SEPERATOR, [$head, $user['id'], $body]);
    }

    private static function sendResetEmail($user, $hash)
    {
        $url = implode('/', [
            Helper::getSetting('app_root'),
            self::URL_PREFIX,
            $hash]);

        $message = '
        <html>
            <head>
                <title>Reset Email Request</title>
            </head>
            <body>
                <p>Hi <strong>' . ucfirst($user['first_name']) . '</strong></p>
                <br />
                <p>You have requested for password reset</p>
                <a href="' . $url . '">Please click this link to continue</a>
                <br />
                <p><em>Mashdrop.com</em></p>
            </body>
        </html>';

        return Mail::send(array(
            'to' => $user['email'],
            'subject' => 'Reset email request',
            'message' => $message));
    }
}
