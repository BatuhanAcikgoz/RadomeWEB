{include file='header.tpl'}
{include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
  <div class="row">
  
    {if count($WIDGETS_LEFT)}
      <div class="col-md-3">
        {foreach from=$WIDGETS_LEFT item=widget}
          {$widget}
        {/foreach}
      </div>
    {/if}
    
       <div class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">
      <div class="card">

        <h2 class="card-header header-theme" style="display:inline;">{$STORE} &raquo; {$ACTIVE_CATEGORY}</h2>
        {include file='store/navbar.tpl'}
                
        <div class="ui bottom attached segment">
          {$CONTENT}
            
          {if isset($NO_PRODUCTS)}
            <div class="ui icon message">
              <i class="info icon"></i>
              <div class="content">
                <p>{$NO_PRODUCTS}</p>
              </div>
            </div>
          {else}
            <div class="ui centered stackable grid">
              {foreach from=$PRODUCTS item=product}
                <div class="col-md-4">
                  <div class="ui card" style="height: 300px">
                    {if $product.image}
                      <div class="image">
                        {if $product.sale_active}
                          <span class="ui right ribbon red label">
                            {$SALE}
                          </span>
                        {/if}
                        <img src="{$product.image}" style="height: 225px" alt="{$product.name}">
                      </div>
                    {/if}
                      
                    <div class="center aligned content">
                      <span class="header">{$product.name}</span>
                      <div class="ui divider"></div>
                      {if $product.sale_active}
                        <span style="color: #dc3545;text-decoration:line-through;">{$CURRENCY_SYMBOL}{$product.price}{$CURRENCY}</span>
                      {/if}
                      {$CURRENCY_SYMBOL}{$product.real_price} {$CURRENCY}
                    </div>
                    <div class="ui bottom attached blue button" onClick="$('#modal{$product.id}').modal('show');">
                      {$BUY} &raquo;
                    </div>
                  </div>
                </div>

                <div class="modal" id="modal{$product.id}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-header">
                    {$product.name} | {$CURRENCY_SYMBOL}{$product.price} {$CURRENCY}
                  </div>
                  <div class="modal-content">
                    {if $product.image}
                      <div class="ui small image">
                        <img src="{$product.image}" alt="{$product.name}">
                      </div>
                    {/if}
                    <div class="modal-body">
                      {$product.description}
                    </div>
                  </div>
                  <div class="modal-footer" style="pointer-events: auto;">
                    <div class="btn btn-secondary">
                      {$CLOSE}
                    </div>
                    <a class="btn btn-primary" href="{$product.link}">
                      {$BUY}
                      <i class="fas fa-shopping-cart"></i>
                    </a>
                  </div>
                </div>
                </div>
              {/foreach}
            </div>
        {/if}
        </div>
                
      </div>
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