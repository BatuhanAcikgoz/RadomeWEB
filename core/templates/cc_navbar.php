<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  UserCP navbar generation
 */

$smarty->assign([
    'CC_NAV_LINKS' => $cc_nav->returnNav('top')
]);