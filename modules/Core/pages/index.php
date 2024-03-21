<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Display either homepage (with news or custom content) or portal
 */

// Home page or portal?
if (Settings::get('home_type') === 'portal') {
    require('portal.php');
} else {
    require('home.php');
}
