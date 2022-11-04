{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <div class="container">
        <div class="row">

            {if count($WIDGETS_LEFT)}
                <div class="col-md-3">
                    {foreach from=$WIDGETS_LEFT item=widget}
                        {$widget}
                    {/foreach}
                </div>
            {/if}

            <div
                class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">
                <div class="card">
                    <div class="card-header header-theme">{$TITLE}</div>
                    <div class="card-body text-center">
                        {if isset($MESSAGE_ENABLED)}
                            {$MESSAGE}<br /><br />
                        {/if}
                        <div class="row d-flex justify-content-center">
                            {foreach from=$SITES item=site}
                                <div class="col-md-4 mb-2">
                                    <a class="btn btn-block btn-theme" href="{$site.url}" target="_blank" role="button"
                                        rel="noopener nofollow">{$site.name}</a>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
                {if isset($MCMP_KEY) }
                    <div class="row">
                        {if isset($SEARCH_RESULTS)}
                            {if $SEARCH_RESULTS eq '1'}
                                <div class="col-md-12">
                                    <div class="alert alert-success" role="alert">
                                        {$VOTE_SORGU1}
                                    </div>
                                </div>
                            {elseif $SEARCH_RESULTS eq '0'}
                                <div class="col-md-12">
                                    <div class="alert alert-warning" role="alert">
                                        {$VOTE_SORGU0}
                                    </div>
                                </div>
                            {elseif $SEARCH_RESULTS eq '2'}
                                <div class="col-md-12">
                                    <div class="alert alert-success" role="alert">
                                        {$VOTE_SORGU1}
                                    </div>
                                </div>
                            {else}
                                <div class="col-md-12">
                                    <div class="alert alert-secondary" role="alert">
                                        {$VOTE_SORGU_NULL}
                                    </div>
                                </div>
                            {/if}
                        {/if}
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header header-theme">Vote Sorgu</div>
                                <form action='' method='GET'>
                                    <div class="input-group mb-2">
                                        <input class="form-control input-sm" type="text" name="vote_search" id="vote_search"
                                            value="{$SEARCH_RESULT}" placeholder="{$SEARCH_PLACEHOLDER}"
                                            style="margin: 20px;">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-theme"
                                                style="margin: 20px;margin-left: 0px;">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header header-theme">{$TOP_VOTERS}</div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{$USERNAME}</th>
                                            <th>{$VOTES}</th>
                                    <tbody>
                                        {foreach from=$MCMP_TOP_VOTERS item=voters}
                                            <tr>
                                                <td>{$voters.nickname}</td>
                                                <td>{$voters.votes}</td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header header-theme">{$LAST_VOTERS}</div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{$USERNAME}</th>
                                            <th>{$DATE}</th>
                                    <tbody>
                                        {foreach from=$MCMP_VOTES item=votes}
                                            <tr>
                                                <td>{$votes.nickname}</td>
                                                <td>{$votes.date_friendly}</td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                {/if}
                </div>
                {if count($WIDGETS_RIGHT)}
                    <div class="col-md-3">
                        {foreach from=$WIDGETS_RIGHT item=widget}
                            {$widget}
                        {/foreach}
                    </div>
                {/if}

            </div>
        </div>
    {include file='footer.tpl'}