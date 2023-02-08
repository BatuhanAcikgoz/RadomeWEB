{include file='header.tpl'}
{include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">

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

        <h2 class="card-header header-theme" style="display:inline;">{$STORE} &raquo; {$CHECKOUT}</h2>
        <br style="margin-bottom: 10px;">
        {include file='store/navbar.tpl'}
        
        <br style="margin-bottom: 35px;">
        
        {if isset($SUCCESS)}
          <div class="btn btn-success btn-lg">
            <i class="fas fa-check"></i>
            <div class="col-md-9 alert alert-danger ">
             {$SUCCESS}
            </div>
            <br>
          </div>
        {/if}
                    
        {if isset($ERRORS)}
          <div class="col-md-12 rounded justify-content-center">
            <div class="col-md-9 alert alert-danger ">
            <i class="fas fa-times"></i>
              {foreach from=$ERRORS item=error}
                {$error}<br />
              {/foreach}
            </div>
            <br>
          </div>
        {/if}
        
        <form class="col-md-12" action="" method="post" id="forms">
        <h3>{$SHOPPING_CART}</h3>
        <br>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>{$NAME}</th>
              <th>{$OPTIONS}</th>
              <th>{$QUANTITY}</th>
              <th>{$PRICE}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {foreach from=$SHOPPING_CART_LIST item=item}
              <tr>
              <td>{$item.name}</td>
            <td>{if count($item.fields)} {foreach from=$item.fields item=field name=fields}<strong>{$field.description}</strong>: {$field.value}{if not $smarty.foreach.fields.last}</br>{/if}{/foreach} {/if}</td>
            <td>{$item.quantity}</td>
            <td>{if $item.sale_active}<span style="color: #dc3545;text-decoration:line-through;">{$item.price_format}</span>{/if} {$item.real_price_format}</td>
            <td><a href="{$item.remove_link}" class="ui icon remove red tiny button right floated"><i class="icon remove"></i></a></td>
              </tr>
            {/foreach}
            </tbody>
          </table>
        
          <table class="ui collapsing table">
          <tbody>
          {if $TOTAL_DISCOUNT_VALUE > 0}
            <tr>
              <td>{$TOTAL_PRICE}</td>
              <td>{$TOTAL_PRICE_FORMAT_VALUE}</td>
            </tr>
            <tr>
              <td>{$TOTAL_DISCOUNT}</td>
              <td>{$TOTAL_DISCOUNT_FORMAT_VALUE}</td>
            </tr>
          {/if}
            <tr>
              <td>{$PRICE_TO_PAY}</td>
              <td>{$TOTAL_REAL_PRICE_FORMAT_VALUE}</td>
            </tr>
          </tbody>
        </table>

        <h3>{$REDEEM_COUPON}</h3>
        <div class="ui divider"></div>
        <form class="ui form" action="{$REDEEM_COUPON_URL}" method="post" id="coupon">
          <div class="field">
              <div class="ui action input">
                  <input type="text" name="coupon" id="coupon" value="{$REDEEM_COUPON_VALUE}" placeholder="{$REDEEM_COUPON_HERE}"/>
                  <input type="hidden" name="token" value="{$TOKEN}">
                  <button class="ui green button">{$REDEEM} &raquo;</button>
              </div>
          </div>
        </form>
          <br style="margin-bottom: 15px;">
          
          <h3>{$PAYMENT_METHOD}</h3>
          <hr />
          {foreach from=$PAYMENT_METHODS item=gateway}
            <div class="field">
              <div class="form-group custom-control custom-switch">
                <input type="radio" name="payment_method" value="{$gateway.name}" required>
                <label>{$gateway.displayname}</label>
              </div>
            </div>
          {/foreach}
          <br>        
        
          <h3>{$PURCHASE}</h3>
          <hr />
          <div class="field">
            <div class="form-group custom-control custom-switch" style="display:inline;">
              <input type="hidden" name="token" value="{$TOKEN}">
              <input type="checkbox" name="t_and_c" value="1" required> <label>{$AGREE_T_AND_C_PURCHASE}</label>
              <span class="left floated"><input type="submit" class="btn btn-theme" value="{$PURCHASE} &raquo;"></span>
            </div>
          </div>
          </br style="margin-bottom: 15px;">
        </form>
        
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