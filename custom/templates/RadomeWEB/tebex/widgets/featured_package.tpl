<div class="card">
    <div class="card-header header-theme">{$FEATURED_PACKAGE}</div>
    <div class="card-body">
        <center>{$PACKAGE.name} &bull; {$CURRENCY}{$PACKAGE.real_price}</center><br />
        {if $PACKAGE.image}
        <center><img src="{$PACKAGE.image}" alt="{$PACKAGE.name}" style="max-width: 100%"></center><br /> {/if}
        <a class="btn btn-theme btn-block" href="{$PACKAGE.link}">{$VIEW}</a>
    </div>
</div>