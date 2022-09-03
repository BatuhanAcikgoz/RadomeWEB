<div class="card">
    <div class="card-header header-theme">{$LATEST_PROFILE_POSTS}</div>
    <div class="card-body">
        {if isset($PROFILE_POSTS_ARRAY)}
            {foreach from=$PROFILE_POSTS_ARRAY name=profile_posts item=post}
                <a class="white-link" href="{$post.link}">{$post.content}</a><br />
                <a href="{$post.user_profile_link}" style="{$post.username_style}" data-poload="{$USER_INFO_URL}{$post.user_id}" data-html="true" data-placement="top">{$post.username}</a>
                <br /><span data-toggle="tooltip" data-trigger="hover" data-original-title="{$post.date_ago}">{$post.ago}</span>
                {if not $smarty.foreach.profile_posts.last}<br /><br />{/if}
            {/foreach}
        {else}
            {$NO_PROFILE_POSTS}
        {/if}
    </div>
</div>