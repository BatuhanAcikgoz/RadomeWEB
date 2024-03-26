{include file='header.tpl'}

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">{$PLEASE_REAUTHENTICATE}</h1>
                            </div>
                            {if isset($ERROR)}
                            <div class="alert bg-danger text-white">
                                {$ERROR}
                            </div>
                            {/if}
                            <form class="user" action="" method="post">
                                {if isset($EMAIL)}
                                <div class="form-group has-feedback">
                                    <input type="email" name="email" id="email" autocomplete="off"
                                        class="form-control form-control-user" placeholder="{$EMAIL}"
                                        value="{$EMAIL_VALUE}">
                                </div>
                                {else}
                                <div class="form-group has-feedback">
                                    <input type="text" name="username" id="username" autocomplete="off"
                                        class="form-control form-control-user" placeholder="{$USERNAME}"
                                        value="{$USERNAME_VALUE}">
                                </div>
                                {/if}
                                <div class="form-group has-feedback">
                                    <input type="password" name="password" id $smarty->assign([
                                    'DEFAULT_DESCRIPTION' => $language->get('admin', 'default_description'),
                                    'DEFAULT_DESCRIPTION_VALUE' => Settings::get('default_meta_description'),
                                    'DEFAULT_KEYWORDS' => $language->get('admin', 'default_keywords'),
                                    'DEFAULT_KEYWORDS_VALUE' => Settings::get('default_meta_keywords'),
                                    ]);

                                    ="password"
                                        class="form-control form-control-user" placeholder="{$PASSWORD}">
                                </div>
                                {if isset($TWO_FACTOR_AUTH)}
                                    <div class="form-group has-feedback">
                                        <input type="text" name="tfa_code" id="tfa"
                                               class="form-control form-control-user" placeholder="{$TFA_ENTER_CODE}">
                                    </div>
                                {/if}
                                <div class="row">
                                    <div class="col-6">
                                        <input type="hidden" name="token" value="{$TOKEN}">
                                        <button type="submit"
                                            class="btn btn-primary btn-block btn-user">{$SUBMIT}</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{$SITE_HOME}" class="btn btn-danger btn-block btn-user">{$CANCEL}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {include file='scripts.tpl'}

</body>

</html>