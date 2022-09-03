{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
    <div class="row">
        <div class="col-md-9">
            {if isset($ERRORS)}
                <div class="alert alert-danger" role="alert">
                    {foreach from=$ERRORS item=error}
                        {$error}<br />
                    {/foreach}
                </div>
            {/if}
            <div class="card">
                <div class="card-header header-theme">
                    {$TITLE}
                    <a class="btn btn-sm btn-theme float-right" href="{$BACK_LINK}">{$BACK}</a>
                </div>
                <div class="card-body">
                    {$CONTENT}
                    <hr>
                    <span class="float-right">
                        <a href="{$POSTER_PROFILE}" class="{$POSTER_TAG}" style="{$POSTER_STYLE}" target="_blank"><img
                                src="{$POSTER_AVATAR}" class="avatar-img" style="max-height:25px;max-width:25px;"
                                alt="{$POSTER_USERNAME}" />
                            {$POSTER_USERNAME}</a>:
                        <span data-toggle="tooltip" data-original-title="{$POSTER_DATE}">{$POSTER_DATE_FRIENDLY}</span>
                    </span>
                    <form style="display:inline;" action="" method="post">
                        <input type="hidden" name="action" value="vote">
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="vote" value="1">
                        <a class="grey-link" href="#" onclick="$(this).closest('form').submit();"
                            style="padding:10px;{if {$VOTED} == 1} color:green !important{/if}" rel="tooltip"
                            data-content="Like"><i class="fa fa-thumbs-up"></i> {$LIKES}</a>
                    </form>
                    <form style="display:inline;" action="" method="post">
                        <input type="hidden" name="action" value="vote">
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="vote" value="2">
                        <a class="grey-link" href="#" onclick="$(this).closest('form').submit();"
                            style="padding:10px;{if {$VOTED} == 2} color:red !important{/if}" rel="tooltip"
                            data-content="Dislike"><i class="fa fa-thumbs-down"></i> {$DISLIKES}</a>
                    </form>
                    {if isset($CAN_MODERATE)}
                        <a class="btn btn-secondary btn-sm" href="{$EDIT_LINK}"><i class="fas fa-pencil-alt fa-fw" aria-hidden="true"></i></a>
                        <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash fa-fw" aria-hidden="true"></i></button>
                    {/if}
                </div>
            </div>
            <div class="card">
                <div class="card-header header-theme">
                    {$COMMENTS_TEXT}
                </div>
                <div class="card-body">

                    {if count($COMMENTS_LIST)}
                        <div class="timeline">
                            <div class="line text-muted"></div>
                            {foreach from=$COMMENTS_LIST item=comment}
                                <article class="panel panel-theme" id="post-id-{$post.id}">
                                    <div class="panel-heading icon">
                                        <img class="avatar-img" style="height:40px; width:40px;" src="{$comment.avatar}"
                                            alt="{$comment.username}" />
                                    </div>
                                    <div class="panel-heading">
                                        <a href="{$comment.profile}" class="{$comment_tag}" style="{$comment.style}"
                                            target="_blank">{$comment.username}</a>:
                                        <span data-toggle="tooltip"
                                            data-original-title="{$comment.date}">{$comment.date_friendly}</span>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="forum_post">
                                            {$comment.content}
                                        </div>
                                    </div>
                                </article>
                            {/foreach}
                        </div>
                    {else}
                        {$NO_COMMENTS}
                    {/if}
                    <hr />

                    {if isset($CAN_COMMENT)}
                        <form action="" method="post">
                            {if isset($CAN_MODERATE)}
                                <div class="form-group">
                                    <label for="statusLabel">Status </label>
                                    <select class="form-control" name="status" id="status">
                                        {foreach from=$STATUSES item=item}
                                            <option value="{$item.id}" {if $STATUS == $item.id}selected{/if}>{$item.name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            {/if}
                            <div class="form-group">
                                <textarea class="form-control" name="content" rows="5"
                                    placeholder="{$NEW_COMMENT}"></textarea>
                            </div>
                            <input type="hidden" name="action" value="comment">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type="submit" class="btn btn-theme">{$SUBMIT}</button>
                        </form>
                    {/if}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            {include file='suggestions/search.tpl'}
            {include file='suggestions/recent_activity.tpl'}
        </div>
    </div>
</div>

<!-- Suggestion deletion modal -->

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="deleteModalLabel">Confirm deletion</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this suggestion?</p>
            </div>
			<div class="modal-footer">
                <form action="" method="post">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <input type="hidden" name="action" value="deleteSuggestion">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <button type="submit" class="btn btn-theme">Delete</button>
                </form>
			</div>
        </div>
    </div>
</div>

{include file='footer.tpl'}