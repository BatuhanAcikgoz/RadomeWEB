<div class="card">
	<div class="card-header header-theme">
		{$LAST_BUY_LABEL}
	</div>
	<div class="card-body">
		<div class="list">

			{foreach from=$MC_LOGS_LIST item=log}
				<div class="list-item">
					<a href="{$log.profile}">
						<div class="list-icon">
							<img src="{$log.avatar}" alt="{$log.username}">
						</div>
					</a>
					<div class="list-content">
						<a href="{$log.profile}" style="{$log.style}" data-poload="/queries/user/?id={$log.id}">{$log.username}</a>
						<span class="meta">
							{$log.product_name}
						</span>
					</div>

				</div>
			{/foreach}
		</div>
	</div>
</div>