<?php
/**
 * Created by PhpStorm.
 * User: psa
 * Date: 16/09/17
 * Time: 09:00
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha;

/**
 * Class ReCaptchaService
 * @package AppBundle\Service
 */
class ReCaptchaService
{

    private $recaptcha_secret = null;

    public function __construct($recaptcha_secret)
    {
        $this->recaptcha_secret = $recaptcha_secret;
    }


    /**
     * Check Captcha
     *
     * @param Request $request
     * @return bool
     */
    public function verify(Request $request)
    {
        $recaptcha = new ReCaptcha($this->recaptcha_secret);

        $response = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
        if ($response->isSuccess()) {
            return true;
        }
        return false;
    }
}

