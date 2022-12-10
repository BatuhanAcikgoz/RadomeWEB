{include file='header.tpl'}
{include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
{if isset($SUCCESS)}
    <div class="btn btn-success btn-lg">
        <i class="check icon"></i>
        <div class="card-body">
            {$SUCCESS}
        </div>
    </div>
{/if}

{if isset($ERRORS)}
    <div class="ui negative icon message">
        <i class="x icon"></i>
        <div class="card-body">
            {foreach from=$ERRORS item=error}
                {$error}<br />
            {/foreach}
        </div>
    </div>
{/if}

<div class="container" id="user">
    <div class="row">
        <div class="col-md-3">
            {include file='user/navigation.tpl'}
        </div>
        <div class="col-md-9">
            <div class="card">
                <h3 class="card-header header-theme">{$STORE}
                    {if isset($CAN_SEND_CREDITS)}<div class="res right floated"><a class="ui mini green button" data-toggle="modal" data-target="#modal-send-credits">{$SEND_CREDITS}</a></div>{/if}
                </h3>
                
                <div class="card-body">{$CREDITS}: {$CREDITS_VALUE}{$CURRENCY_SYMBOL}</div>
            </div>
            
            <div class="card">
                <h3 class="card-header header-theme">{$MY_TRANSACTIONS}</h3>
                {nocache}
                    {if count($TRANSACTIONS_LIST)}
                        <table class="ui fixed single line selectable unstackable small padded res table">
                            <thead>
                                <tr>
                                    <th>{$TRANSACTION}</th>
                                    <th>{$AMOUNT}</th>
                                    <th>{$DATE}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$TRANSACTIONS_LIST item=transaction}
                                    <tr>
                                        <td>{$transaction.transaction}</td>
                                        <td>{$transaction.currency_symbol}{$transaction.amount} {$transaction.currency}</td>
                                        <td><span data-toggle="tooltip" data-content="{$transaction.date_full}">{$transaction.date_friendly}</span></td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    {else}
                        <div class="ui info message">
                            <div class="card-body">
                                {$NO_TRANSACTIONS}
                            </div>
                        </div>
                    {/if}
                {/nocache}
            </div>
            <div class="card">
            <h3 class="card-header header-theme">{$URUNLER}</h3>
            {nocache}
                {if count($PURCHASES_LIST)}
                    <table class="ui fixed single line selectable unstackable small padded res table">
                        <thead>
                            <tr>
                                <th>{$URUN}</th>
                                <th>{$AMOUNT}</th>
                                <th>{$DATE}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$PURCHASES_LIST item=purchase}
                                <tr>
                                    <td>{$purchase.name}</td>
                                    <td>{$purchase.currency_symbol}{$purchase.amount} {$purchase.currency}</td>
                                    <td><span data-toggle="tooltip" data-content="{$purchase.date_full}">{$purchase.date_friendly}</span></td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="ui info message">
                        <div class="card-body">
                            {$NO_TRANSACTIONS}
                        </div>
                    </div>
                {/if}
            {/nocache}
        </div>
        </div>
    </div>
</div>

{if isset($CAN_SEND_CREDITS)}
<div class="ui small modal" id="modal-send-credits">
    <div class="header">
        {$SEND_CREDITS}
    </div>
    <div class="card-body">
        <form class="ui form" action="" method="post" id="sendCredits">
            <div class="field">
                <label for="InputTo">{$TO}</label>
                <div class="ui fluid search selection dropdown">
                    <input name="to" id="InputTo" type="hidden">
                    <i class="dropdown icon"></i>
                    <div class="default text">{$TO}</div>
                    <div class="menu">
                        {if count($ALL_USERS) > 0}
                            {foreach from=$ALL_USERS item="username"}
                                <div class="item" data-value="{$username}">{$username}</div>
                            {/foreach}
                        {/if}
                    </div>
                </div>
            </div>
            <div class="field">
                <label for="inputCredits">{$AMOUNT} {$YOU_HAVE_X_CREDITS}</label>
                <input type="number" id="InputCredits" name="credits" step="0.01" min="0.01" max="{$CREDITS_VALUE}" value="0.00">
            </div>
            <input type="hidden" value="{$TOKEN}" name="token" />
        </form>
    </div>
    <div class="actions">
        <a class="ui negative button">{$CANCEL}</a>

        <a type="submit" class="ui positive button" onclick="document.getElementById('sendCredits').submit()">{$SEND_CREDITS}</a>
    </div>
</div>
{/if}

{include file='footer.tpl'}