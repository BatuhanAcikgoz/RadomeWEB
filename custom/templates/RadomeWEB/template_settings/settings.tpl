<style>
.dark .card {
    border-color: #585858 !important
}
</style>

<form class='form-group' method='post' enctype='multipart/form-data' id="option-form" action="">
    <div class="row">
        <div class="col-md-3">
            <div class="card" style="border: 1px solid #e3e6f0">
                <div class="card-body">
                    <ul class="nav nav-pills mb-3 flex-column" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><i class="fas fa-home"></i> {$HOME}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-update-tab" data-toggle="pill" href="#pills-update" role="tab" aria-controls="pills-update" aria-selected="false"><i class="fas fa-cloud-upload-alt"></i> {$UPDATE}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-general-tab" data-toggle="pill" href="#pills-general" role="tab" aria-controls="pills-general" aria-selected="false"><i class="fas fa-cogs"></i> {$GENERAL}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-styling-tab" data-toggle="pill" href="#pills-styling" role="tab" aria-controls="pills-styling" aria-selected="false"><i class="fas fa-palette"></i> {$STYLING}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-optimization-tab" data-toggle="pill" href="#pills-optimization" role="tab" aria-controls="pills-optimization" aria-selected="false"><i class="fas fa-tachometer-alt"></i> {$OPTIMIZE_TAB}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-header-tab" data-toggle="pill" href="#pills-header" role="tab" aria-controls="pills-header" aria-selected="false"><i class="fas fa-arrow-circle-up"></i> {$HEADER}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-welcome-tab" data-toggle="pill" href="#pills-welcome" role="tab" aria-controls="pills-welcome" aria-selected="false"><i class="fas fa-smile"></i> {$WB}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-news-tab" data-toggle="pill" href="#pills-news" role="tab" aria-controls="pills-news" aria-selected="false"><i class="fas fa-newspaper"></i> {$NEWS}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-slider-tab" data-toggle="pill" href="#pills-slider" role="tab" aria-controls="pills-slider" aria-selected="false"><i class="fas fa-sliders-h"></i> {$SLIDER}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-elr-tab" data-toggle="pill" href="#pills-elr" role="tab" aria-controls="pills-elr" aria-selected="false"><i class="fas fa-sign-in-alt"></i> {$ELR}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-footer-tab" data-toggle="pill" href="#pills-footer" role="tab" aria-controls="pills-footer" aria-selected="false"><i class="fas fa-arrow-circle-down"></i> {$FOOTER}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card" style="border: 1px solid #e3e6f0">
                <div class="card-body">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <h1><b>{$HOME}</b></h1>
                            <hr />
                            <span>{$HOME_1}</span><br /><br />
                            <span>{$HOME_2} <a href="{$SUPPORT_URL}" target="_blank">{$SUPPORT_URL}</a></span><br /><br />
                            <span>{$HOME_3} <a href="{$SUPPORT_URL}" target="_blank">{$SUPPORT_URL}</a></span><br /><br />
                        </div>
                        <div class="tab-pane fade" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">
                            <h1><b>{$GENERAL}</b></h1>
                            <hr />
                            <br /><br />
                            <input type="hidden" name="al" value="no">
                            <input type="checkbox" name="al" class="js-switch js-check-change" id="al" value="yes" {if $AL_VALUE eq 'yes' }checked{/if}>
                            <label>{$AL}</label>
                            <br /><br />
                            <label>{$TS_PANEL}</label>
                            <select id="ts" class='form-control' name='ts'>
                                <option value='icon' {if $TS_VALUE eq 'icon' } selected{/if}>{$TS_ICON}</option>
                                <option value='footer' {if $TS_VALUE eq 'footer' } selected{/if}>{$FOOTER}</option>
                                <option value='user_settings' {if $TS_VALUE eq 'user_settings' } selected{/if}>{$TS_US}</option>
                                <option value='nodisplay' {if $TS_VALUE eq 'nodisplay' } selected{/if}>{$TS_NODISPLAY}</option>
                            </select>
                            <br />
                            <label>{$PBG}</label><span class="float-right">{$BLANK}</span>
                            <input type='text' name='pbg' value='{$PBG_VALUE}' class='form-control'>
                            <hr>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type='submit' class='btn btn-primary'>{$SUBMIT}</button>
                        </div>
                        <div class="tab-pane fade" id="pills-styling" role="tabpanel" aria-labelledby="pills-styling-tab">
                            <h1><b>{$STYLING}</b></h1>
                            <hr />
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-generalst-tab" data-toggle="pill" href="#pills-generalst" role="tab" aria-controls="pills-generalst" aria-selected="true">{$GENERAL}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-colors-tab" data-toggle="pill" href="#pills-colors" role="tab" aria-controls="pills-colors" aria-selected="false">{$COLORS}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-generalst" role="tabpanel" aria-labelledby="pills-generalst-tab">
                                    <input type="hidden" name="card_rounded" value="no">
                                    <input type="checkbox" name="card_rounded" class="js-switch js-check-change" id="card_rounded" value="yes" {if $CARD_ROUNDED_VALUE eq 'yes' }checked{/if}>
                                    <label>{$CARD_ROUNDED}</label>
                                    <br /><br />
                                    <label>{$FONT}</label>
                                    <select id="font" class='form-control' name='font'>
                                        <option value='Montserrat' {if $FONT_VALUE eq 'Montserrat' } selected{/if}>{$FONT_1}</option>
                                        <option value='Coda' {if $FONT_VALUE eq 'Coda' } selected{/if}>{$FONT_2}</option>
                                        <option value='Verdana' {if $FONT_VALUE eq 'Verdana' } selected{/if}>{$FONT_3}</option>
                                    </select>
                                    <br />
                                    <label>{$CUSTOM_CSS}</label><span class="float-right">{$BLANK}</span>
                                    <textarea name="custom_css" rows="3" id="custom_css" class="form-control">{$CUSTOM_CSS_VALUE}</textarea>
                                </div>
                                <div class="tab-pane fade" id="pills-colors" role="tabpanel" aria-labelledby="pills-colors-tab">
                                    <input type="hidden" name="dark" value="no">
                                    <input type="checkbox" name="dark" class="js-switch js-check-change" id="dark" value="yes" {if $DARK_VALUE eq 'yes' }checked{/if}>
                                    <label>{$DARK}</label>
                                    <br /><br />
                                    <label>{$LINKS}</label>
                                    <select id="links" class='form-control' name='links'>
                                        <option value='wgh' {if $LINKS_VALUE eq 'wgh' } selected{/if}>{$LINKS_WGH}</option>
                                        <option value='gwh' {if $LINKS_VALUE eq 'gwh' } selected{/if}>{$LINKS_GWH}</option>
                                    </select>
                                    <br />
                                    <label>{$P_COLOR}</label>
                                    <div class="input-group color">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                        <input type='text' name='p_color' value='{$P_COLOR_VALUE}' class='form-control'>
                                    </div>
                                    <label>{$S_COLOR} | {$S_COLOR_VALUE}</label>
                                    <br />
                                    <div class="input-group s-color">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                        <input type='text' name='s_color' value='{$S_COLOR_VALUE}' class='form-control'>
                                    </div>                                    
                                </div>
                            </div>
                            <input type='hidden' name='view' value='update'>
                            <hr>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type='submit' class='btn btn-primary'>{$SUBMIT}</button>
                        </div>

                        <div class="tab-pane fade" id="pills-optimization" role="tabpanel" aria-labelledby="pills-optimization-tab">
                            <h1><b>{$OPTIMIZE_TAB}</b></h1>
                            <hr />
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-o-general-tab" data-toggle="pill" href="#pills-o-general" role="tab" aria-controls="pills-o-general" aria-selected="true">{$GENERAL}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-o-elr-tab" data-toggle="pill" href="#pills-o-elr" role="tab" aria-controls="pills-o-elr" aria-selected="false">{$ELR}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-o-general" role="tabpanel" aria-labelledby="pills-o-general-tab">
                                    <p>{$OPTIMIZE_INFO} <a href="https://cloudconvert.com/webp-converter" target="_blank" rel="nofollow noopener">CloudConvert</a></p>
                                    <label>{$LOGO_WEBP}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='logo_webp' value='{$LOGO_WEBP_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$HEADER_BG_WEBP}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='header_bg_webp' value='{$HEADER_BG_WEBP_VALUE}' class='form-control'>
                                </div>
                                <div class="tab-pane fade" id="pills-o-elr" role="tabpanel" aria-labelledby="pills-o-elr-tab">
                                    <p>{$OPTIMIZE_INFO} <a href="https://cloudconvert.com/webp-converter" target="_blank" rel="nofollow noopener">CloudConvert</a></p>
                                    <label>{$LBG_WEBP}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='lbg_webp' value='{$LBG_WEBP_VALUE}' class='form-control'><br />
                                    <label>{$RBG_WEBP}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='rbg_webp' value='{$RBG_WEBP_VALUE}' class='form-control'><br />
                                    <label>{$EBG_WEBP}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='ebg_webp' value='{$EBG_WEBP_VALUE}' class='form-control'>
                                </div>
                            </div>
                            <input type='hidden' name='view' value='update'>
                            <hr>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type='submit' class='btn btn-primary'>{$SUBMIT}</button>
                        </div>

                        <div class="tab-pane fade" id="pills-header" role="tabpanel" aria-labelledby="pills-header-tab">
                            <h1><b>{$HEADER}</b></h1>
                            <hr />
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-bgh-tab" data-toggle="pill" href="#pills-bgh" role="tab" aria-controls="pills-bgh" aria-selected="true">{$BG_TAB}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-logoh-tab" data-toggle="pill" href="#pills-logoh" role="tab" aria-controls="pills-logoh" aria-selected="false">{$LOGO_TAB}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-navbarh-tab" data-toggle="pill" href="#pills-navbarh" role="tab" aria-controls="pills-navbarh" aria-selected="false">{$NAVBAR_TAB}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-dsh-tab" data-toggle="pill" href="#pills-dsh" role="tab" aria-controls="pills-dsh" aria-selected="false">{$DS_TEXTS_TAB}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-bgh" role="tabpanel" aria-labelledby="pills-bgh-tab">
                                    <input type="hidden" name="particles" value="no">
                                    <input type="checkbox" name="particles" class="js-switch js-check-change" id="particles" value="yes" {if $PARTICLES_VALUE eq 'yes' }checked{/if}>
                                    <label>{$PARTICLES}</label>
                                    <br /><br />
                                    <input type="hidden" name="coverlay" value="no">
                                    <input type="checkbox" name="coverlay" class="js-switch js-check-change" id="coverlay" value="yes" {if $COVERLAY_VALUE eq 'yes' }checked{/if}>
                                    <label>{$COVERLAY}</label><br /><span>{$COVERLAY_1}</span><br /><br />
                                    <label>{$HEADER_BG}</label>
                                    <input type='text' name='header_bg' value='{$HEADER_BG_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$BG_HEIGHT}</label>
                                    <input type='text' name='bg_height' value='{$BG_HEIGHT_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$BG_HEIGHT_M}</label>
                                    <input type='text' name='bg_height_m' value='{$BG_HEIGHT_M_VALUE}' class='form-control'>
                                </div>
                                <div class="tab-pane fade" id="pills-logoh" role="tabpanel" aria-labelledby="pills-logoh-tab">
                                    <label>{$LOGO}</label>
                                    <input type='text' name='logo' value='{$LOGO_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$LOGO_SIZE}</label>
                                    <input type='text' name='logo_size' value='{$LOGO_SIZE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$LOGO_SIZE_M}</label>
                                    <input type='text' name='logo_size_m' value='{$LOGO_SIZE_M_VALUE}' class='form-control'>
                                </div>
                                <div class="tab-pane fade" id="pills-navbarh" role="tabpanel" aria-labelledby="pills-navbarh-tab">
                                    <label>{$NAVBAR}</label>
                                    <select id="navbar" class='form-control' name='navbar'>
                                        <option value='double' {if $NAVBAR_VALUE eq 'double' } selected{/if}>{$NAVBAR_1}</option>
                                        <option value='top' {if $NAVBAR_VALUE eq 'top' } selected{/if}>{$NAVBAR_2}</option>
                                        <option value='bottom' {if $NAVBAR_VALUE eq 'bottom' } selected{/if}>{$NAVBAR_3}</option>
                                    </select><br />
                                    <label>{$NAVBAR_SIZE}</label><span class="float-right">{$NAVBAR_SIZE_1}</span>
                                    <select id="navbar_size" class='form-control' name='navbar_size'>
                                        <option value='small' {if $NAVBAR_SIZE_VALUE eq 'small' } selected{/if}>{$SMALL}</option>
                                        <option value='medium' {if $NAVBAR_SIZE_VALUE eq 'medium' } selected{/if}>{$MEDIUM}</option>
                                        <option value='large' {if $NAVBAR_SIZE_VALUE eq 'large' } selected{/if}>{$LARGE}</option>
                                    </select>
                                </div>
                                <div class="tab-pane fade" id="pills-dsh" role="tabpanel" aria-labelledby="pills-dsh-tab">
                                    <input type="hidden" name="ds_box" value="no">
                                    <input type="checkbox" name="ds_box" class="js-switch js-check-change" id="ds_box" value="yes" {if $DS_BOX_VALUE eq 'yes' }checked{/if}>
                                    <label>{$DS_BOX}</label>
                                    <br /><br />
                                    <label>{$DISCORD_SERVER}</label><span class="float-right" style="color: #dc3545"><b>{$DISCORD_SERVER_1}</b></span>
                                    <input type='text' name='discord_server' value='{$DISCORD_SERVER_VALUE}' class='form-control'>
                                </div>
                            </div>
                            <input type='hidden' name='view' value='update'>
                            <hr>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type='submit' class='btn btn-primary'>{$SUBMIT}</button>
                        </div>
                        <div class="tab-pane fade" id="pills-welcome" role="tabpanel" aria-labelledby="pills-welcome-tab">
                            <h1><b>{$WB}</b></h1>
                            <hr />
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-generalwb-tab" data-toggle="pill" href="#pills-generalwb" role="tab" aria-controls="pills-generalwb" aria-selected="true">{$GENERAL}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-buttonswb-tab" data-toggle="pill" href="#pills-buttonswb" role="tab" aria-controls="pills-buttonswb" aria-selected="false">{$BUTTONS_TAB}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-skinswb-tab" data-toggle="pill" href="#pills-skinswb" role="tab" aria-controls="pills-skinswb" aria-selected="false">{$SKINS_TAB}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-generalwb" role="tabpanel" aria-labelledby="pills-generalwb-tab">
                                    <label>{$WB_T}</label><span class="float-right">{$WB_T_1}</span>
                                    <input type='text' name='wb_t' value='{$WB_T_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$WB_D}</label>
                                    <input type='text' name='wb_d' value='{$WB_D_VALUE}' class='form-control'>
                                </div>
                                <div class="tab-pane fade" id="pills-buttonswb" role="tabpanel" aria-labelledby="pills-buttonswb-tab">
                                    <label>{$WB_1N}</label>
                                    <input type='text' name='wb_1n' value='{$WB_1N_VALUE}' class='form-control'><br />
                                    <label>{$WB_1L}</label>
                                    <input type='text' name='wb_1l' value='{$WB_1L_VALUE}' class='form-control'><br />
                                    <label>{$WB_2N}</label>
                                    <input type='text' name='wb_2n' value='{$WB_2N_VALUE}' class='form-control'><br />
                                    <label>{$WB_2L}</label>
                                    <input type='text' name='wb_2l' value='{$WB_2L_VALUE}' class='form-control'>
                                </div>
                                <div class="tab-pane fade" id="pills-skinswb" role="tabpanel" aria-labelledby="pills-skinswb-tab">
                                    <label>{$WB_S1}</label>
                                    <input type='text' name='wb_s1' value='{$WB_S1_VALUE}' class='form-control'><br />
                                    <label>{$WB_S2}</label>
                                    <input type='text' name='wb_s2' value='{$WB_S2_VALUE}' class='form-control'><br />
                                    <label>{$WB_S3}</label>
                                    <input type='text' name='wb_s3' value='{$WB_S3_VALUE}' class='form-control'><br />
                                    <label>{$WB_S4}</label>
                                    <input type='text' name='wb_s4' value='{$WB_S4_VALUE}' class='form-control'><br />
                                    <label>{$WB_S5}</label>
                                    <input type='text' name='wb_s5' value='{$WB_S5_VALUE}' class='form-control'><br />
                                    <label>{$WB_S6}</label>
                                    <input type='text' name='wb_s6' value='{$WB_S6_VALUE}' class='form-control'>
                                </div>
                            </div>
                            <input type='hidden' name='view' value='update'>
                            <hr>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type='submit' class='btn btn-primary'>{$SUBMIT}</button>
                        </div>
                        <div class="tab-pane fade" id="pills-slider" role="tabpanel" aria-labelledby="pills-slider-tab">
                            <h1><b>{$SLIDER}</b></h1>
                            <hr />
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-slider1-tab" data-toggle="pill" href="#pills-slider1" role="tab" aria-controls="pills-slider1" aria-selected="true">{$SLIDER1}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-slider2-tab" data-toggle="pill" href="#pills-slider2" role="tab" aria-controls="pills-slider2" aria-selected="false">{$SLIDER2}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-slider3-tab" data-toggle="pill" href="#pills-slider3" role="tab" aria-controls="pills-slider3" aria-selected="false">{$SLIDER3}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-slider4-tab" data-toggle="pill" href="#pills-slider4" role="tab" aria-controls="pills-slider4" aria-selected="false">{$SLIDER4}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-slider5-tab" data-toggle="pill" href="#pills-slider5" role="tab" aria-controls="pills-slider5" aria-selected="false">{$SLIDER5}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-slider1" role="tabpanel" aria-labelledby="pills-slider1-tab">
                                    <label>{$SLIDER1_TITLE}</label><span class="float-right">{$SLIDER1_1}</span>
                                    <input type='text' name='slider1_title' value='{$SLIDER1_TITLE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER1_DESC}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider1_desc' value='{$SLIDER1_DESC_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER1_IMAGE}</label>
                                    <input type='text' name='slider1_image' value='{$SLIDER1_IMAGE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER1_LINK}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider1_link' value='{$SLIDER1_LINK_VALUE}' class='form-control'>
                                    <br />
                                    <input type="hidden" name="slider1_link_open" value="no">
                                    <input type="checkbox" name="slider1_link_open" class="js-switch js-check-change" id="slider1_link_open" value="yes" {if $SLIDER1_LINK_OPEN_VALUE eq 'yes' }checked{/if}>
                                    <label>{$SLIDER_LINK_OPEN}</label>
                                </div>
                                <div class="tab-pane fade" id="pills-slider2" role="tabpanel" aria-labelledby="pills-slider2-tab">
                                    <label>{$SLIDER2_TITLE}</label><span class="float-right">{$SLIDER2_1}</span>
                                    <input type='text' name='slider2_title' value='{$SLIDER2_TITLE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER2_DESC}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider2_desc' value='{$SLIDER2_DESC_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER2_IMAGE}</label>
                                    <input type='text' name='slider2_image' value='{$SLIDER2_IMAGE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER2_LINK}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider2_link' value='{$SLIDER2_LINK_VALUE}' class='form-control'>
                                    <br />
                                    <input type="hidden" name="slider2_link_open" value="no">
                                    <input type="checkbox" name="slider2_link_open" class="js-switch js-check-change" id="slider2_link_open" value="yes" {if $SLIDER2_LINK_OPEN_VALUE eq 'yes' }checked{/if}>
                                    <label>{$SLIDER_LINK_OPEN}</label>
                                </div>
                                <div class="tab-pane fade" id="pills-slider3" role="tabpanel" aria-labelledby="pills-slider3-tab">
                                    <label>{$SLIDER3_TITLE}</label><span class="float-right">{$SLIDER3_1}</span>
                                    <input type='text' name='slider3_title' value='{$SLIDER3_TITLE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER3_DESC}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider3_desc' value='{$SLIDER3_DESC_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER3_IMAGE}</label>
                                    <input type='text' name='slider3_image' value='{$SLIDER3_IMAGE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER3_LINK}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider3_link' value='{$SLIDER3_LINK_VALUE}' class='form-control'>
                                    <br />
                                    <input type="hidden" name="slider3_link_open" value="no">
                                    <input type="checkbox" name="slider3_link_open" class="js-switch js-check-change" id="slider3_link_open" value="yes" {if $SLIDER3_LINK_OPEN_VALUE eq 'yes' }checked{/if}>
                                    <label>{$SLIDER_LINK_OPEN}</label>
                                </div>
                                <div class="tab-pane fade" id="pills-slider4" role="tabpanel" aria-labelledby="pills-slider4-tab">
                                    <label>{$SLIDER4_TITLE}</label><span class="float-right">{$SLIDER4_1}</span>
                                    <input type='text' name='slider4_title' value='{$SLIDER4_TITLE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER4_DESC}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider4_desc' value='{$SLIDER4_DESC_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER4_IMAGE}</label>
                                    <input type='text' name='slider4_image' value='{$SLIDER4_IMAGE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER4_LINK}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider4_link' value='{$SLIDER4_LINK_VALUE}' class='form-control'>
                                    <br />
                                    <input type="hidden" name="slider4_link_open" value="no">
                                    <input type="checkbox" name="slider4_link_open" class="js-switch js-check-change" id="slider4_link_open" value="yes" {if $SLIDER4_LINK_OPEN_VALUE eq 'yes' }checked{/if}>
                                    <label>{$SLIDER_LINK_OPEN}</label>
                                </div>
                                <div class="tab-pane fade" id="pills-slider5" role="tabpanel" aria-labelledby="pills-slider5-tab">
                                    <label>{$SLIDER5_TITLE}</label><span class="float-right">{$SLIDER5_1}</span>
                                    <input type='text' name='slider5_title' value='{$SLIDER5_TITLE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER5_DESC}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider5_desc' value='{$SLIDER5_DESC_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER5_IMAGE}</label>
                                    <input type='text' name='slider5_image' value='{$SLIDER5_IMAGE_VALUE}' class='form-control'>
                                    <br />
                                    <label>{$SLIDER5_LINK}</label><span class="float-right">{$BLANK}</span>
                                    <input type='text' name='slider5_link' value='{$SLIDER5_LINK_VALUE}' class='form-control'>
                                    <br />
                                    <input type="hidden" name="slider5_link_open" value="no">
                                    <input type="checkbox" name="slider5_link_open" class="js-switch js-check-change" id="slider5_link_open" value="yes" {if $SLIDER5_LINK_OPEN_VALUE eq 'yes' }checked{/if}>
                                    <label>{$SLIDER_LINK_OPEN}</label>
                                </div>
                            </div>
                            <input type='hidden' name='view' value='update'>
                            <hr>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type='submit' class='btn btn-primary'>{$SUBMIT}</button>
                        </div>
                        <div class="tab-pane fade" id="pills-elr" role="tabpanel" aria-labelledby="pills-elr-tab">
                            <h1><b>{$ELR}</b></h1>
                            <hr />
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-bgelr-tab" data-toggle="pill" href="#pills-bgelr" role="tab" aria-controls="pills-bgelr" aria-selected="true">{$BACKGROUNDS_TAB}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-logoelr-tab" data-toggle="pill" href="#pills-logoelr" role="tab" aria-controls="pills-logoelr" aria-selected="false">{$LOGO_TAB}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-bgelr" role="tabpanel" aria-labelledby="pills-bgelr-tab">
                                    <label>{$LBG}</label>
                                    <input type='text' name='lbg' value='{$LBG_VALUE}' class='form-control'><br />
                                    <label>{$RBG}</label>
                                    <input type='text' name='rbg' value='{$RBG_VALUE}' class='form-control'><br />
                                    <label>{$EBG}</label>
                                    <input type='text' name='ebg' value='{$EBG_VALUE}' class='form-control'>
                                </div>
                                <div class="tab-pane fade" id="pills-logoelr" role="tabpanel" aria-labelledby="pills-logoelr-tab">
                                    <label>{$ELR_LOGO}</label>
                                    <input type='text' name='elr_logo' value='{$ELR_LOGO_VALUE}' class='form-control'><br />
                                    <label>{$ELR_MARGIN}</label>
                                    <input type='text' name='elr_margin' value='{$ELR_MARGIN_VALUE}' class='form-control'>
                                </div>
                            </div>
                            <input type='hidden' name='view' value='update'>
                            <hr>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type='submit' class='btn btn-primary'>{$SUBMIT}</button>
                        </div>
                        <div class="tab-pane fade" id="pills-footer" role="tabpanel" aria-labelledby="pills-footer-tab">
                            <h1><b>{$FOOTER}</b></h1>
                            <hr />
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-aboutf-tab" data-toggle="pill" href="#pills-aboutf" role="tab" aria-controls="pills-aboutf" aria-selected="true">{$ABOUT_SECTION_TAB}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-otherf-tab" data-toggle="pill" href="#pills-otherf" role="tab" aria-controls="pills-otherf" aria-selected="false">{$OTHER_SECTION_TAB}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-aboutf" role="tabpanel" aria-labelledby="pills-aboutf-tab">
                                    <label>{$ABOUT}</label>
                                    <input type='text' name='about' value='{$ABOUT_VALUE}' class='form-control'>
                                </div>
                                <div class="tab-pane fade" id="pills-otherf" role="tabpanel" aria-labelledby="pills-otherf-tab">
                                    <label>{$OTHER_T}</label>
                                    <input type='text' name='other_t' value='{$OTHER_T_VALUE}' class='form-control'><br />
                                    <label>{$OTHER_D}</label>
                                    <input type='text' name='other_d' value='{$OTHER_D_VALUE}' class='form-control'><br />
                                    <label>{$OTHER_BT}</label>
                                    <input type='text' name='other_bt' value='{$OTHER_BT_VALUE}' class='form-control'><br />
                                    <label>{$OTHER_BL}</label>
                                    <input type='text' name='other_bl' value='{$OTHER_BL_VALUE}' class='form-control'>
                                </div>
                            </div>
                            <input type='hidden' name='view' value='update'>
                            <hr>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type='submit' class='btn btn-primary'>{$SUBMIT}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>