<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Login page
 */

// Set page name variable
const PAGE = 'forgot_mail';
$page_title = str_replace('?', '', $language->get('user', 'forgot_email'));
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Ensure user isn't already logged in
if ($user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Get login method
$login_method = Settings::get('login_method');

$captcha = CaptchaBase::isCaptchaEnabled('recaptcha_login');

// Deal with input
if (Input::exists()) {
    // Check form token
    if (Token::check()) {
        // Valid token
        if (!isset($_SESSION['tfa']) && $captcha) {
            $captcha_passed = CaptchaBase::getActiveProvider()->validateToken($_POST);
        } else {
            $captcha_passed = true;
        }

        if ($captcha_passed) {
            if (isset($_SESSION['password'])) {
                if (isset($_SESSION['username'])) {
                    $_POST['username'] = $_SESSION['username'];
                    unset($_SESSION['username']);
                } else {
                    if (isset($_SESSION['email'])) {
                        $_POST['email'] = $_SESSION['email'];
                        unset($_SESSION['email']);
                    }
                }

                $_POST['remember'] = $_SESSION['remember'];
                $_POST['password'] = $_SESSION['password'];

                unset($_SESSION['remember'], $_SESSION['password'], $_SESSION['tfa']);
            }

            $rate_limit = [5, 60]; // 5 attempts in 60 seconds - TODO allow this to be customised?

                $to_validate = [
                    'username' => [
                        Validate::REQUIRED => true,
                        Validate::RATE_LIMIT => $rate_limit,
                    ],
                    'password' => [
                        Validate::REQUIRED => true
                    ],
                    'email' => [
                        Validate::REQUIRED => true,
                        Validate::IS_BANNED => true,
                        Validate::RATE_LIMIT => $rate_limit,
                        Validate::UNIQUE => 'users',
                    ]
                ];
        

            $validation = Validate::check($_POST, $to_validate)->messages([
                'username' => [
                    Validate::REQUIRED => ($login_method == 'username' ? $language->get('user', 'must_input_username') : $language->get('user', 'must_input_email_or_username')),
                    Validate::IS_BANNED => $language->get('user', 'account_banned'),
                    Validate::RATE_LIMIT => static fn($meta) => $language->get('general', 'rate_limit', $meta),
                ],
                'password' => [Validate::REQUIRED => $language->get('user', 'must_input_password')],
                'email' => [
                    Validate::REQUIRED => $language->get('user', 'must_input_email'),
                    Validate::IS_BANNED => $language->get('user', 'account_banned'),
                    Validate::RATE_LIMIT => static fn($meta) => $language->get('general', 'rate_limit', $meta),
                    Validate::UNIQUE => $language->get('user', 'username_mcname_email_exists')
                ]
            ]);

            // Check if validation passed
            if ($validation->passed()) {
                $username = Input::get('username');
                $method_field = 'username';

                $user_query = new User($username, $method_field);
                if ($user_query->exists()) {

                    if (!isset($return_error)) {

                        // Validation passed
                        // Initialise user class
                        $user = new User();
                        $remember = 0;
                        $login = $user->login($username, Input::get('password'), $remember, $method_field);

                        // Successful login?
                        if ($login) {
                            // Yes
                            $user_query2 = $user->data();
                            if (!$code) {
                                $code = SecureRandom::alphanumeric();
                                } else {
                                    $code = $user_query2->reset_code;
                            }
                            $user->update([
                                'email' => Input::get('email'),
                                'active' => 0,
                                'reset_code' => $code,
                            ]);
                            require_once(ROOT_PATH . '/modules/Core/includes/emails/register.php');
                            if (sendRegisterEmail($language, Input::get('email'), $user_query2->username, $user_query2->id, $code)) {
                                Session::flash('edit_user_success', $language->get('admin', 'email_resent_successfully'));
                            } else {
                                Session::flash('edit_user_error', $language->get('admin', 'email_resend_failed'));
                            }
                            $user->logout();
                            Session::flash('home', $language->get('user', 'mail_was_changed'));
                            Redirect::to(URL::build('/'));
                        }

                        // No, output error
                        $return_error = $language->get('user', 'incorrect_details');
                    }
                } else {
                    $return_error = $language->get('user', 'incorrect_details');
                }
            } else {
                // Validation failed
                $return_error = $validation->errors()[0];
            }
        } else {
            // reCAPTCHA failed
            $return_error = $language->get('user', 'invalid_recaptcha');
        }
    } else {
        // Invalid token
        $return_error = $language->get('general', 'invalid_token');
    }
}

if (isset($return_error)) {
    $smarty->assign([
        'ERROR' => $return_error,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

// Sign in template
// Generate content
$smarty->assign([
    'USERNAME_PLACEHOLDER' => $language->get('user', 'username'),
    'USERNAME_INPUT' => Output::getClean(Input::get('username')),
    'FORGOT_EMAIL_INSTRUCTIONS' => $language->get('user', 'forgot_email_instructions'),
    'FORGOT_EMAIL' => $language->get('user', 'forgot_email'),
    'PASSWORD' => $language->get('user', 'password'),
    'EMAIL' => $language->get('user', 'email'),
    'EMAIL_INPUT' => Output::getClean(Input::get('username')),
    'NEW_MAIL_TYPE' => $language->get('general', 'new_mail_type'),
    'FORM_TOKEN' => Token::get(),
    'NOT_REGISTERED_YET' => $language->get('general', 'not_registered_yet'),
    'SUBMIT' => $language->get('general', 'submit')
]);

if ($captcha) {
    $smarty->assign('CAPTCHA', CaptchaBase::getActiveProvider()->getHtml());
    $template->addJSFiles([CaptchaBase::getActiveProvider()->getJavascriptSource() => []]);

    $submitScript = CaptchaBase::getActiveProvider()->getJavascriptSubmit('form-login');
    if ($submitScript) {
        $template->addJSScript('
            $("#form-login").submit(function(e) {
                e.preventDefault();
                ' . $submitScript . '
            });
        ');
    }
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forgot_email.tpl', $smarty);
