{include file='header-top.tpl'}
        <style>
        @media (min-width: 768px) {
		{if $THEME_E_BG_WEBP !== ""}
			.webp body {
            	background: url('{$THEME_R_BG_WEBP}') no-repeat center;
        	}
        	.no-webp body {
          		background: url('{$THEME_R_BG}') no-repeat center;
        	}
		{else}
        	body {
        	    background: url('{$THEME_R_BG}') no-repeat center;
        	}
		{/if}
		.logo {
			margin-top: {$THEME_ELR_MARGIN} !important;
            height: {$THEME_ELR_LOGO} !important;
		}
        }
		html, body, .container-fluid, .row-h, .elr-col-right {
			height: 100vh;
		}
		.elr-title {
			font-size: 40px;
			font-weight: bold;
		}
        </style>
    </head>
    <body>
        <div class="container-fluid h-100">
            <div class="row row-h">
                <div class="col-md-8 col-inv">
                    <picture>
                        <source srcset="none"  media="(max-width: 767px)">
                        {if isset($THEME_LOGO_WEBP) && $THEME_LOGO_WEBP|count_characters > 4}<source srcset="{$THEME_LOGO_WEBP}" type="image/webp">{/if}
                        <source srcset="{$THEME_LOGO}"> 
                        <img class="logo{if isset($THEME_AL) && $THEME_AL|count_characters > 2} animated-logo{/if}" alt="logo" src='{$THEME_LOGO}'>
                    </picture>
                </div>
                <div class="col-md-4 elr-col-right" style="overflow-y: scroll">
                    <div class="container elr-container">
                        <span class="elr-title">{$CREATE_AN_ACCOUNT}</span>
			            <br /><br />
			            <form action="" method="post">
                        {if isset($REGISTRATION_ERROR)}
                        <div class="alert alert-danger">
                            {foreach from=$REGISTRATION_ERROR item=error} {$error}
                            <br /> {/foreach}
                        </div> {/if}
                        {assign var=counter value=1}
                    {foreach $FIELDS as $field_key => $field}
                    <div class="form-control form-control-lg">
                        {if $field.type eq 1}
                        <input type="text" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}
                            required{/if}>
                        {else if $field.type eq 2}
                        <textarea name="{$field_key}" id="{$field_key}" placeholder="{$field.placeholder}"
                            tabindex="{$counter++}"></textarea>
                        {else if $field.type eq 3}
                        <input type="date" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            tabindex="{$counter++}">
                        {else if $field.type eq 4}
                        <input type="password" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}
                            required{/if}>
                        {else if $field.type eq 5}
                        <select class="ui fluid dropdown" name="{$field_key}" id="{$field_key}" {if
                            $field.required}required{/if}>
                            {foreach from=$field.options item=option}
                            <option value="{$option.value}" {if $option.value eq $field.value} selected{/if}>
                                {$option.option}</option>
                            {/foreach}
                        </select>
                        {else if $field.type eq 6}
                        <input type="number" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            placeholder="{$field.name}" tabindex="{$counter++}" {if $field.required} required{/if}>
                        {else if $field.type eq 7}
                        <input type="email" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}
                            required{/if}>
                        {else if $field.type eq 8}
                        {foreach from=$field.options item=option}
                        <div class="field">
                            <div class="ui radio checkbox" tabindex="{$counter++}">
                                <input type="radio" name="{$field_key}" value="{$option.value}" {if $field.value eq
                                    $option.value}checked{/if} {if $field.required}required{/if}>
                                <label>{$option.option}</label>
                            </div>
                        </div>
                        {/foreach}
                        {else if $field.type eq 9}
                        {foreach from=$field.options item=option}
                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" name="{$field_key}[]" value="{$option.value}" {if
                                    is_array($field.value) && in_array($option.value, $field.value)}checked{/if}
                                    tabindex="{$counter++}">
                                <label>{$option.option}</label>
                            </div>
                        </div>
                        {/foreach}
                        {/if}
                    </div>
                    {/foreach}



                        <div class="row">
                            <div class="col-4 col-md-5 col-lg-4">
                                <span class="button-checkbox">
				                    <button type="button" class="btn float-left" data-color="info" tabindex="7"> {$I_AGREE}</button>
				                    <input type="checkbox" name="t_and_c" id="t_and_c" style="display:none;" value="1">
				                </span>
                            </div>
                            <div class="col-8 col-md-7 col-lg-8">
                                <span class="agree-terms">{$AGREE_TO_TERMS}</span>
                            </div>
                        </div>
                        <br /> 

                        {if $CAPTCHA}
                        <div class="form-group" style="width: 100%; justify-content: center; display: flex;">
                            {$CAPTCHA}
                        </div>
                        {/if}
                        <div class="row mb-4">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input id="timezone" type="hidden" name="timezone" value=''>
                            <div class="col-6"><button type="submit" class="btn btn-theme btn-block">{$REGISTER}</button></div>
                            <div class="col-6"><a href="{$LOGIN_URL}" class="btn btn-theme btn-block">{$LOG_IN}</a></div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
	{include file='scripts.tpl'}
{if !isset($EXCLUDE_END_BODY)}
  </body>

  </html>
{/if}
