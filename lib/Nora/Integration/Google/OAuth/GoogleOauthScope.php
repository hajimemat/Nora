<?php
namespace Nora\Integration\Google\OAuth;

abstract class GoogleOauthScope
{
    /** Associate you with your google my business. */
    const BUSINESS_MANAGE = 'https://www.googleapis.com/auth/business.manage';
    const PLUS_LOGIN =
        "https://www.googleapis.com/auth/plus.login";
    /** Associate you with your personal info on Google. */
    const PLUS_ME =
        "https://www.googleapis.com/auth/plus.me";
    /** View your email address. */
    const USERINFO_EMAIL =
        "https://www.googleapis.com/auth/userinfo.email";
    /** See your personal info, including any personal info you've made publicly available. */
    const USERINFO_PROFILE =
        "https://www.googleapis.com/auth/userinfo.profile";
}
