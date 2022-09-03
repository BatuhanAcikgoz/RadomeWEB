<div class="card">
	<div class="card-header header-theme">
		<a href="{$MC_ACCOUNT_LINK}">{$WG_TITLE}</a>
	</div>
	{if $VALIDATE != 1}
		<div class="card-body">
			<div class="pairs">
				<dl>
					<dt>{$MC_ACCOUNT}</dt>
					<dd>{$MC_NAME}</dd>
				</dl>
				<dl>
					<dl>
						<dt>{$BALANCE}</dt>
						<dd>{$BAL_VALUE} {$CURRENCY}</dd>
					</dl>
					<dl>
			</div>
			<div class="text-center">
				<a href="{$MAKE_BALANCE_LINK}" class="btn btn-sm btn-theme" role="button"
					style="width: 100%;">{$MAKE_BALANCE}</a>
			</div>
		</div>
	{else}
		<div class="card-body">
			<div class="pairs">
				<span>{$ACTIVATE_CONTENT}</span>
			</div>
		</div>
		<div class="card-footer text-center">
			<a href="{$VALIDATE_URL}" class="btn btn-theme" role="button" aria-pressed="true">{$ACTIVATE_ACCOUNT}</a>
		</div>
	{/if}
</div>