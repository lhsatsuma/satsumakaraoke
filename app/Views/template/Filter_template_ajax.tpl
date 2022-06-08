<form class="mr-auto" method="post" id="filtroForm{$id_filter}" action="{$action}">
	<span class="form-inline">
	{if $generic_filter}
		<input name="search_generic_filter" class="form-control col-9 col-sm-8 col-md-7 col-lg-6 col-xl-4 margin" type="text" placeholder="{translate l="LBL_SEARCH"}..." aria-label="Search" value="{$search_generic_filter}" autocomplete="off">
		<button class="btn btn-outline-success btn-rounded" type="submit"><i class="fas fa-search"></i> {translate l="LBL_SEARCH"}</button>
		{if !empty($filters)}
		<button class="btn btn-outline-success btn-rounded" type="button" data-toggle="modal" data-target="#filtroFormAdvancedModal"><i class="fas fa-filter"></i></button>
		{/if}
	{/if}
	{if !empty($ext_buttons)}
		{foreach from=$ext_buttons item=btn key=name}
			<span class="extra-btns" filter-extra-btn="{$name}">{$btn}</span>
		{/foreach}
	{/if}
	</span>
	<input type="hidden" name="order_by_field_{$id_filter}" value="{$order_by.field}" />
	<input type="hidden" name="order_by_order_{$id_filter}" value="{$order_by.order}" />
	<input type="hidden" name="page_{$id_filter}" value="{$page}" />
	{if !empty($filters)}
		<div class="modal fade" id="filtroFormAdvancedModal" tabindex="-1" role="dialog" aria-labelledby="filtroFormAdvancedModalTitle" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="filtroFormAdvancedModalTitle">{translate l="LBL_ADVANCED_SEARCH"}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			<div class="modal-body">
				{$modal_filter_advanced}
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary btn-rounded" data-dismiss="modal"><i class="fas fa-times"></i> {translate l="LBL_CLOSE"}</button>
				<button type="submit" class="btn btn-outline-primary btn-rounded"><i class="fas fa-search"></i> {translate l="LBL_SEARCH"}</button>
			  </div>
			</div>
		  </div>
		</div>
	{/if}
</form>