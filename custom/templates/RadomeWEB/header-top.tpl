<!DOCTYPE html>
<html {if "HTML_CLASS"|defined}{$smarty.const.HTML_CLASS} {/if}lang="{if "HTML_LANG"|defined}{$smarty.const.HTML_LANG}{else}en{/if}"{if "HTML_RTL"|defined && $smarty.const.HTML_RTL eq true} dir="rtl"{/if}{if isset($THEME_DARK) && $THEME_DARK|count_characters > 2} class="dark"{/if} id="html">
	<head>
	
		{literal}
		<script>
			var dark = '{/literal}{$THEME_DARK}{literal}';
		</script>
		{/literal}
		<link rel="preload" href="{$THEME_TS_PATH}" as="script">
		<link rel="preload" href="{$THEME_MOD_PATH}" as="script">
		<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" as="style">
		{if isset($THEME_FONT) && $THEME_FONT !== "Verdana"}<link rel="preconnect" href="https://fonts.gstatic.com">{/if}
		<link rel="preconnect" href="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous">
		<script src="{$THEME_TS_PATH}"></script>
		<script src="{$THEME_MOD_PATH}"></script>

        <meta charset="{if "LANG_CHARSET"|defined}{$smarty.const.LANG_CHARSET}{else}utf-8{/if}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
		<title>{$TITLE} &bull; {$smarty.const.SITE_NAME}</title>
		<meta property="og:title" content="{$TITLE} &bull; {$smarty.const.SITE_NAME}"/>
		{if isset($PAGE_DESCRIPTION) && $PAGE_DESCRIPTION|count_characters > 0}
			<meta name="description" content="{$PAGE_DESCRIPTION}"/>
			<meta property="og:description" content="{$PAGE_DESCRIPTION}"/>
		{/if} 
		{if isset($PAGE_KEYWORDS) && $PAGE_KEYWORDS|count_characters > 0}
			<meta name="keywords" content="{$PAGE_KEYWORDS}"/>
		{/if}
		{if isset($FAVICON)}
			<link rel="shortcut icon" href="{$FAVICON}" type="image/x-icon" />
			<meta property="og:image" content="{$FAVICON}"/>
		{/if}
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="{$OG_URL}"/>
		<meta property="og:site_name" content="{$smarty.const.SITE_NAME}"/>
		{foreach from=$TEMPLATE_CSS item=css}
			{$css}
		{/foreach}
		{if isset($ANALYTICS_ID)}
			{literal}
			  <script async src="https://www.googletagmanager.com/gtag/js?id={/literal}{$ANALYTICS_ID}{literal}"></script>
			  <script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
	  
				gtag('config', '{/literal}{$ANALYTICS_ID}{literal}');
			  </script>
			{/literal}
		{/if}