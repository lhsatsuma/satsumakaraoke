<form class="mr-auto" method="post" id="filtroForm" action="{$action}">
	<span class="form-inline">
	{if $generic_filter}
		<input name="search_generic_filter" class="form-control col-12 col-sm-9 col-md-7 col-lg-6 col-xl-4 margin" type="search" placeholder="{translate l="LBL_SEARCH"}..." aria-label="Search" value="{$search_generic_filter}" autocomplete="off">
		<button class="btn btn-outline-success btn-rounded" type="button" onclick="submitSearch(this, 'filtroForm')"><i class="fas fa-search"></i> {translate l="LBL_SEARCH"}</button>
		{if !empty($filters)}
		<button class="btn btn-outline-success btn-rounded" type="button" data-toggle="modal" data-target="#filtroFormAdvancedModal"><i class="fas fa-filter"></i> {if $icon_filter_advanced}<i class="far fa-times-circle"></i>{/if}</button>
		{/if}
	{/if}
	{if !empty($ext_buttons)}
		{foreach from=$ext_buttons item=btn key=name}
			<span class="extra-btns" filter-extra-btn="{$name}">{$btn}</span>
		{/foreach}
	{/if}
	</span>
	<input type="hidden" name="order_by_field" value="{$order_by.field}" />
	<input type="hidden" name="order_by_order" value="{$order_by.order}" />
	{if !empty($filters)}
		<div class="modal fade" id="filtroFormAdvancedModal" tabindex="-1" role="dialog" aria-labelledby="filtroFormAdvancedModalTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
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
				<button type="button" class="btn btn-outline-warning btn-rounded" onclick="clearFiltroForm(this)"><i class="fas fa-undo"></i> {translate l="LBL_RESET"}</button>
				<button type="button" class="btn btn-outline-primary btn-rounded" onclick="ValidateForm('filtroForm', this)"><i class="fas fa-search"></i> {translate l="LBL_SEARCH"}</button>
			  </div>
			</div>
		  </div>
		</div>
	{/if}
</form>