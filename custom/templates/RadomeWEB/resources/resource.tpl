{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header header-theme">{$VIEWING_RESOURCE}
                    <div class="float-right">
                        {if isset($CAN_UPDATE)}
                        <a href="{$UPDATE_LINK}" class="btn btn-theme btn-sm">{$UPDATE}</a> {/if}
                        <a href="{$BACK_LINK}" class="btn btn-theme btn-sm">{$RESOURCE_INDEX}</a>
                    </div>
                </div>
                <div class="card-body">
                    {$DESCRIPTION}
                    <br />{if $HAS_CONTRIBUTORS eq 1}{$CONTRIBUTORS} {$RESOURCE_CONTRIBUTORS}{/if}
                    <hr />
                    {if isset($LOG_IN_TO_DOWNLOAD)}
                        <button disabled class="btn btn-theme">{$LOG_IN_TO_DOWNLOAD}</button>
                    {/if}
                    {if isset($DOWNLOAD_URL)}
                        <a href="{$DOWNLOAD_URL}" class="btn btn-theme" target="_blank">{$DOWNLOAD}</a>
                    {elseif isset($PURCHASE_FOR_PRICE)}
                        <a {if isset($PURCHASE_LINK)}href="{$PURCHASE_LINK}" 
                        {else}disabled
                            {/if}class="btn btn-theme">{$PURCHASE_FOR_PRICE}</a>
                    {elseif isset($PAYMENT_PENDING)}
                        <button type="button" disabled class="btn btn-theme">{$PAYMENT_PENDING}</button>
                    {/if}
                    <span class="float-right">
                        {if isset($CAN_EDIT)}<a href="{$EDIT_LINK}" class="btn btn-theme">{$EDIT}</a>{/if}
                        {if isset($MODERATION) && count($MODERATION)}
                            <div class="btn-group dropup">
                                <button type="button" class="btn btn-theme dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">{$MODERATION_TEXT}</button>
                                <div class="dropdown-menu">
                                    {foreach from=$MODERATION item=item}
                                        <a class="dropdown-item" href="{$item.link}">{$item.title}</a>
                                    {/foreach}
                                </div>
                            </div>
                        {/if}
                        <a href="{$OTHER_RELEASES_LINK}" class="btn btn-theme">{$OTHER_RELEASES}</a>
                    </span>
                </div>
            </div>
            <div class="card">
                <div class="card-header header-theme">{$REVIEWS}</div>
                <div class="card-body">
                    {if count($COMMENT_ARRAY)} 
                        {foreach from=$COMMENT_ARRAY item=comment} {$comment.content}
                            {if isset($DELETE_REVIEW)}
                                <br /><br /><span class="pull-right"><a href="{$comment.delete_link}"
                                        onclick="return confirm('{$CONFIRM_DELETE_REVIEW}');">{$DELETE_REVIEW} <i
                                        class="fas fa-trash"></i></a></span><br /> {/if}
                            <hr />
                            <div class="star-rating view inline-form">
                                <span class="far fa-star" data-rating="1"></span>
                                <span class="far fa-star" data-rating="2"></span>
                                <span class="far fa-star" data-rating="3"></span>
                                <span class="far fa-star" data-rating="4"></span>
                                <span class="far fa-star" data-rating="5"></span>
                                <input type="hidden" name="rating" class="rating-value" value="{$comment.rating}">
                            </div> | {$comment.release_tag} | <span data-toggle="tooltip"
                                data-original-title="{$comment.date_full}">{$comment.date}</span>
                            <span class="pull-right">
                                <a href="{$comment.user_profile}"><img class="avatar-img" src="{$comment.user_avatar}"
                                        style="height:20px;width:20px;" alt="{$comment.username}" /></a> <a
                                    href="{$comment.user_profile}" style="{$comment.user_style}">{$comment.username}</a>
                            </span>
                        <br /> {/foreach} {$PAGINATION} 
                        {else}
                            <p>{$NO_REVIEWS}</p>
                            {/if} {if $CAN_REVIEW == true && !isset($PAYMENT_PENDING) && !isset($PURCHASE_FOR_PRICE)}
                            <h4>{$NEW_REVIEW}</h4>
                            <form action="" method="post">
                                <div class="form-group">
                                    <div class="star-rating set">
                                        <span class="far fa-star" data-rating="1"></span>
                                        <span class="far fa-star" data-rating="2"></span>
                                        <span class="far fa-star" data-rating="3"></span>
                                        <span class="far fa-star" data-rating="4"></span>
                                        <span class="far fa-star" data-rating="5"></span>
                                        <input type="hidden" name="rating" class="rating-value" value="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    {if !isset($MARKDOWN)}
                                    <textarea style="width:100%" name="content" id="editor" rows="15"></textarea> {else}
                                        <textarea class="form-control" style="width:100%" id="markdown" name="content"
                                            rows="20"></textarea>
                                        <span class="pull-right"><i data-toggle="popover" data-placement="top" data-html="true"
                                                data-content="{$MARKDOWN_HELP}" class="fa fa-question-circle text-info"
                                            aria-hidden="true"></i></span> {/if}
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <button type="submit" class="btn btn-theme">{$SUBMIT}</button>
                                </div>
                            </form>
                        {/if}
                    </div>
                </div>
            </div>
            <div class="col-md-3">

                <div class="card">
                    <div class="card-header header-theme">{$RESOURCE}</div>
                    <div class="card-body" style="font-size: 0.9rem;">
                        <div class="item">
                            <span class="text">{$VIEWS}</span>
                            <div class="description float-right"><b>{$VIEWS_VALUE}</b></div>
                        </div>
                        <div class="item">
                            <span class="text">{$TOTAL_DOWNLOADS}</span>
                            <div class="description float-right"><b>{$TOTAL_DOWNLOADS_VALUE}</b></div>
                        </div>
                        <div class="item">
                            <span class="text">{$FIRST_RELEASE}</span>
                            <div class="description float-right"><b>{$FIRST_RELEASE_DATE}</b></div>
                        </div>
                        <div class="item">
                            <span class="text">{$LAST_RELEASE}</span>
                            <div class="description float-right"><b>{$LAST_RELEASE_DATE}</b></div>
                        </div>
                        <div class="item">
                            <span class="text">{$CATEGORY}</span>
                            <div class="description float-right"><b>{$CATEGORY_VALUE}</b></div>
                        </div>
                        <div class="item">
                            <span class="text">{$RATING}</span>
                            <div class="description float-right">
                                <div class="star-rating view">
                                    <span class="far fa-star" data-rating="1" style="color:gold;"></span>
                                    <span class="far fa-star" data-rating="2" style="color:gold"></span>
                                    <span class="far fa-star" data-rating="3" style="color:gold;"></span>
                                    <span class="far fa-star" data-rating="4" style="color:gold;"></span>
                                    <span class="far fa-star" data-rating="5" style="color:gold;"></span>
                                    <input type="hidden" name="rating" class="rating-value" value="{$RATING_VALUE}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header header-theme">{$RELEASE_VERSION}</div>
                    <div class="card-body" style="font-size: 0.9rem;">

                        <div class="item">
                            <span class="text">{$DOWNLOADS}</span>
                            <div class="description float-right"><b>{$RELEASE_DOWNLOADS}</b></div>
                        </div>
                        <div class="item">
                            <span class="text">{$RELEASE}</span>
                            <div class="description float-right"><b>{$RELEASE_DATE_FULL}</b></div>
                        </div>
                        <div class="item">
                            <span class="text">{$RATING}</span>
                            <div class="description float-right">
                                <div class="star-rating view">
                                    <span class="far fa-star" data-rating="1" style="color:gold;"></span>
                                    <span class="far fa-star" data-rating="2" style="color:gold"></span>
                                    <span class="far fa-star" data-rating="3" style="color:gold;"></span>
                                    <span class="far fa-star" data-rating="4" style="color:gold;"></span>
                                    <span class="far fa-star" data-rating="5" style="color:gold;"></span>
                                    <input type="hidden" name="rating" class="rating-value" value="{$RELEASE_RATING}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header header-theme">{$AUTHOR}</div>
                    <div class="card-body">
                        <center>
                            <a href="{$AUTHOR_PROFILE}"><img src="{$AUTHOR_AVATAR}" class="avatar-img"
                                    alt="{$AUTHOR_NICKNAME}" style="max-height:80px; max-width:80px;" /></a><br /><br />
                            <a href="{$AUTHOR_PROFILE}" style="{$AUTHOR_STYLE}">{$AUTHOR_NICKNAME}</a>
                            <hr />
                        </center>
                        <a class="grey-link" href="{$AUTHOR_RESOURCES}">&raquo; {$VIEW_OTHER_RESOURCES}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {include file='footer.tpl'}