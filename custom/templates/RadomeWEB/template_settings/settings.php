<?php
/*
 *
 *  RadomeWEB Template Settings
 */

$radomeweb_language = new Language(ROOT_PATH . '/custom/templates/RadomeWEB/template_settings/language', LANGUAGE);

require(ROOT_PATH . '/custom/templates/RadomeWEB/template_settings/vars.php');

if (Input::exists()) {
    if (Token::check()) {
        $cache->setCache('radomeweb_template');

        foreach ($radomeweb_settings_array as $value) {
            if (isset($_POST[$value[0]])) {
                $cache->store($value[0], $_POST[$value[0]]);
            }
        }

        Session::flash('admin_templates', $language->get('admin', 'successfully_updated'));
    } else {
        $errors = array($language->get('general', 'invalid_token'));
    }
}

$cache->setCache('radomeweb_template');

foreach ($radomeweb_settings_array as $value) {
    $setting_name = $value[0];
    if ($cache->isCached($value[0])) {
        $$setting_name = $cache->retrieve($value[0]);
    } else {  
        $$setting_name = $value[1];
        $cache->store($value[0], $value[1]);
    }
}

$current_template->addJSFiles(array(
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.js' => array()
));

$current_template->addCSSFiles(array(
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.css' => array()
));

$current_template->addJSScript('
    $(\'.color\').colorpicker({ format: \'hex\', autoInputFallback: false, color: "'. $p_color .'" });
    $(\'.s-color\').colorpicker({ format: \'hex\', autoInputFallback: false, color: "'. $s_color .'" });
	var elems = Array.prototype.slice.call(document.querySelectorAll(\'.js-switch\'));
	elems.forEach(function(elem) {
		var switchery = new Switchery(elem, {color: \'#23923d\', secondaryColor: \'#e56464\'});
    });
    
    function switchToSubmit() {
        if ($("#radomeweb-update").val()) {
            $("#radomeweb-update-label").addClass("d-none");
            $("#radomeweb-submit").removeClass("d-none");
        }
    }

    function switchToSubmitUploadSettings() {
        if ($("#radomeweb-upload-settings").val()) {
            $("#radomeweb-upload-settings-label").addClass("d-none");
            $("#radomeweb-upload-settings-submit").removeClass("d-none");
        }
    }   
');

foreach ($radomeweb_settings_array as $value) {
    $first_value = strtoupper($value[0]) . "_VALUE";
    $output_value = $value[0];
    
    $smarty->assign($first_value, $$output_value);
    
    $output_lang = strtoupper($value[0]);
    if ($value[0] == 'ts') {
        $smarty->assign('TS_PANEL', $radomeweb_language->get('language', 'ts_panel'));

    } elseif ($value[0] == 'slider1_link_open' || $value[0] == 'slider2_link_open' || $value[0] == 'slider3_link_open' || $value[0] == 'slider4_link_open' || $value[0] == 'slider5_link_open') {

    } else {
        $smarty->assign($output_lang, $radomeweb_language->get('language', $value[0]));
    }
}

$smarty->assign(array(
    'SUPPORT_URL' => $support_url,
    'LOCAL_VERSION' => $radomeweb_local_version,
    'OPTIMIZE_TAB' => $radomeweb_language->get('language', 'optimize_tab'),
    'OPTIMIZE_INFO' => $radomeweb_language->get('language', 'optimize_info'),
    'FONT_3' => $radomeweb_language->get('language', 'font_3'),
    'HOME' => $radomeweb_language->get('language', 'home'),
    'GENERAL' => $radomeweb_language->get('language', 'general'),
    'COLORS' => $radomeweb_language->get('language', 'colors'),
    'STYLING' => $radomeweb_language->get('language', 'styling'),
    'HEADER' => $radomeweb_language->get('language', 'header'),
    'WB' => $radomeweb_language->get('language', 'wb'),
    'NEWS' => $radomeweb_language->get('language', 'news'),
    'SLIDER' => $radomeweb_language->get('language', 'slider'),
    'ELR' => $radomeweb_language->get('language', 'elr'),
    'FOOTER' => $radomeweb_language->get('language', 'footer'),
    'PORTAL' => $radomeweb_language->get('language', 'portal'),
    'UPDATE' => $radomeweb_language->get('language', 'update'),
    'NAVBAR_TAB' => $radomeweb_language->get('language', 'navbar_tab'),
    'TS_WIDGET' => $radomeweb_language->get('language', 'ts_widget'),
    'TS_BOTTOM_WIDGET' => $radomeweb_language->get('language', 'ts_bottom_widget'),
    'TS_US' => $radomeweb_language->get('language', 'ts_us'),
    'TS_ICON' => $radomeweb_language->get('language', 'ts_icon'),
    'TS_NODISPLAY' => $radomeweb_language->get('language', 'ts_nodisplay'),
    'BG_TAB' => $radomeweb_language->get('language', 'bg_tab'),
    'LOGO_TAB' => $radomeweb_language->get('language', 'logo_tab'),
    'HOME_1' => $radomeweb_language->get('language', 'home_1'),
    'HOME_2' => $radomeweb_language->get('language', 'home_2'),
    'HOME_3' => $radomeweb_language->get('language', 'home_3'),
    'UPDATE_1' => $radomeweb_language->get('language', 'update_1'),
    'UPDATE_2' => $radomeweb_language->get('language', 'update_2'),
    'UPDATE_3' => $radomeweb_language->get('language', 'update_3'),
    'UPDATE_4' => $radomeweb_language->get('language', 'update_4'),
    'UPDATE_5' => $radomeweb_language->get('language', 'update_5'),
    'UPDATE_6' => $radomeweb_language->get('language', 'update_6'),
    'UPDATE_7' => $radomeweb_language->get('language', 'update_7'),
    'UPDATE_8' => $radomeweb_language->get('language', 'update_8'),
    'UPDATE_9' => $radomeweb_language->get('language', 'update_9'),
    'FONT_1' => $radomeweb_language->get('language', 'font_1'),
    'FONT_2' => $radomeweb_language->get('language', 'font_2'),
    'DS_TEXTS_TAB' => $radomeweb_language->get('language', 'ds_texts_tab'),
    'NAVBAR_1' => $radomeweb_language->get('language', 'navbar_1'),
    'NAVBAR_2' => $radomeweb_language->get('language', 'navbar_2'),
    'NAVBAR_3' => $radomeweb_language->get('language', 'navbar_3'),
    'COVERLAY_1' => $radomeweb_language->get('language', 'coverlay_1'),
    'DISCORD_SERVER_1' => $radomeweb_language->get('language', 'discord_server_1'),
    'BUTTONS_TAB' => $radomeweb_language->get('language', 'buttons_tab'),
    'SKINS_TAB' => $radomeweb_language->get('language', 'skins_tab'),
    'WB_T_1' => $radomeweb_language->get('language', 'wb_t_1'),
    'SLIDER1' => $radomeweb_language->get('language', 'slider1'),
    'SLIDER2' => $radomeweb_language->get('language', 'slider2'),
    'SLIDER3' => $radomeweb_language->get('language', 'slider3'),
    'SLIDER4' => $radomeweb_language->get('language', 'slider4'),
    'SLIDER5' => $radomeweb_language->get('language', 'slider5'),
    'BLANK' => $radomeweb_language->get('language', 'blank'),
    'SLIDER1_1' => $radomeweb_language->get('language', 'slider1_1'),
    'SLIDER2_1' => $radomeweb_language->get('language', 'slider2_1'),
    'SLIDER3_1' => $radomeweb_language->get('language', 'slider3_1'),
    'SLIDER4_1' => $radomeweb_language->get('language', 'slider4_1'),
    'SLIDER5_1' => $radomeweb_language->get('language', 'slider5_1'),
    'SLIDER_LINK_OPEN' => $radomeweb_language->get('language', 'slider_link_open'),
    'BACKGROUNDS_TAB' => $radomeweb_language->get('language', 'backgrounds_tab'),
    'ABOUT_SECTION_TAB' => $radomeweb_language->get('language', 'about_section_tab'),
    'OTHER_SECTION_TAB' => $radomeweb_language->get('language', 'other_section_tab'),
    'IMAGE1_TAB' => $radomeweb_language->get('language', 'image1_tab'),
    'IMAGE2_TAB' => $radomeweb_language->get('language', 'image2_tab'),
    'IMAGE3_TAB' => $radomeweb_language->get('language', 'image3_tab'),
    'LINKS_WGH' => $radomeweb_language->get('language', 'links_wgh'),
    'LINKS_GWH' => $radomeweb_language->get('language', 'links_gwh'),
    'RADOMEWEB' => $radomeweb_language->get('language', 'radomeweb_title'),
    'YES' => $radomeweb_language->get('language', 'yes'),
    'NO' => $radomeweb_language->get('language', 'no'),
    'NAVBAR_SIZE_1' => $radomeweb_language->get('language', 'navbar_size_1'),
    'SMALL' => $radomeweb_language->get('language', 'small'),
    'MEDIUM' => $radomeweb_language->get('language', 'medium'),
    'LARGE' => $radomeweb_language->get('language', 'large'),
    'SUBMIT' => $language->get('general', 'submit'),
    'SETTINGS_TEMPLATE' => ROOT_PATH . '/custom/templates/RadomeWEB/template_settings/settings.tpl'
));

// External Updater

// RadomeWEB Easy Updater
// Made by Skyrowl for RadomeWEB Theme

function chmod_r($path) {
    $dir = new DirectoryIterator($path);
    foreach ($dir as $item) {
        chmod($item->getPathname(), 0777);
        if ($item->isDir() && !$item->isDot()) {
            chmod_r($item->getPathname());
        }
    }
}

function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}