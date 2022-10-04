<div class="card">
    <div class="card-header header-theme">{$STATISTICS}</div>
    <div class="card-body">
      {$USERS_REGISTERED}<b class="float-right">{$USERS_REGISTERED_VALUE}</b><br />
      {if $USERS_ONLINE}{$USERS_ONLINE}<b class="float-right">{$USERS_ONLINE_VALUE}</b><br />{/if}
      {if $GUESTS_ONLINE}{$GUESTS_ONLINE}<b class="float-right">{$GUESTS_ONLINE_VALUE}</b><br />{/if}
      {if $TOTAL_ONLINE}{$TOTAL_ONLINE}<b class="float-right">{$TOTAL_ONLINE_VALUE}</b><br />{/if}
      {$LATEST_MEMBER}<br /><a class="white-link" style="{$LAST_MEMBER_VALUE.style}" href="{$LATEST_MEMBER_VALUE.profile}" data-poload="{$USER_INFO_URL}{$LATEST_MEMBER_VALUE.id}" data-html="true" data-placement="top"><img class="avatar-img" src="{$LATEST_MEMBER_VALUE.avatar}" alt="{$LATEST_MEMBER_VALUE.username}" style="width: 30px; margin-bottom: 5px" /> <b> {$LATEST_MEMBER_VALUE.username}</b></a>
    </div>
  </div>
  