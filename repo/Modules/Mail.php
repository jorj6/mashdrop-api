<?php //-->

namespace Modules;

use PHPMailer;

 /**
  * Module Mail
  * tool, wrapper, and helper of this class object
  *
  * @category   utility
  * @author     javincX
  */
class Mail
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function send($data)
    {
        $required = array('message', 'subject', 'to');

        // check required
        if($field = Helper::getMissingFields($data, $required)) {
            return Helper::error('MAIL_SEND_REQUIRED',
                $field . ' required, empty given');
        }

        // this is SMTP by default
        return self::smtp($data);
    }

    // smtp
    public static function smtp($data)
    {
        $setting = self::setting('mail')['smtp'];

        $mail = new PHPMailer();

        $mail->IsSMTP();
        // $mail->SMTPDebug  = 2;
        $mail->SMTPAuth   = true;
        $mail->Host       = $setting['host'];
        $mail->Username   = $setting['user'];
        $mail->Password   = $setting['pass'];

        $mail->setFrom($setting['user'], $setting['name']);
        // $mail->addReplyTo($setting['user'], $setting['name']);
        $mail->addAddress($data['to']);
        $mail->Subject = $data['subject'];
        $mail->MsgHTML($data['message']);

        if(!$mail->Send()) {
            Helper::panic('MAIL_ERROR', $mail->ErrorInfo);
        }

        return true;
    }

    // amazoon service
    public static function ses($data)
    {

    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    protected static function setting()
    {
        return Helper::getSetting('mail');
    }
}
