{if !$bdOnly}
{$filter_template}
{/if}
<div class="table-responsive">
	<table class="table table-striped table-list tb-rst-fltr">
		<thead>
			<tr class="d-flex">
				<th scope="col" class="ptr col-3 col-xl-1" dt-h-field="tipo" onclick="OrderByFiltro('tipo')">{translate l="LBL_LANGUAGE"}</th>
				<th scope="col" class="ptr col-9 col-xl-11" dt-h-field="name" onclick="OrderByFiltro('name')">{translate l="LBL_NAME"}</th>
			</tr>
		</thead>
		<tbody>
			{if empty($records)}
			<tr class="ptr r-dt-slct" dt-r-id="{$campos.id}">
				<td colspan="2">{translate l="LBL_NO_RECORDS_FOUND"}</td>
			</tr>
			{else}
			{foreach from=$records item=campos}
			<tr class="ptr r-dt-slct d-flex" dt-r-id="{$campos.id}" dt-r-fvt="{$campos.favorite}">
				<td class="col-3 col-xl-1" dt-r-tipo="{$campos.raw.tipo}">{$campos.tipo}</td>
				<td class="col-9 col-xl-11" dt-r-name="{$campos.name}"  dt-r-codigo="{$campos.codigo}">[{$campos.codigo}] {$campos.name}</td>
			</tr>
			{/foreach}
			{/if}
		</tbody>
	</table>
</div>
<table class="table table-striped table-list table-pagination">
	<tbody>
		<tr>
			<td>
				<p>Ir para página: <input size="5" type="text" class="form-control QuickGoToPage" inputmode="numeric" pattern="[0-9]*" /><button type="button" class="btn btn-outline-info btn-rounded" onclick="QuickGoToPage(this)">Ir</button></p>
			</td>
		</tr>
		<tr>
			<td>{$pagination}</td>
		</tr>
	</tbody>
</table>
{if !$bdOnly}
<div class="modal fade" id="SelectedRowModal" tabindex="-1" role="dialog" aria-labelledby="SelectedRowModalLabel" aria-hidden="true">
	<input type="hidden" id="IdInsertModal" />
	<input type="hidden" id="itsFavorite" />
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="SelectedRowModalLabel"></h6>
			</div>
			<div class="modal-body">
				<div class="row margin-5">
					<div class="col-12 primary-row center">
						<button type="button" id="InsertFilaBtn" class="btn btn-outline-success btn-rounded btn-bordered col-8">{translate l="LBL_INSERT_TO_WAITLIST"}</button>
					</div>
					<div class="col-12 primary-row center">
						<button type="button" id="InsertFavoriteBtn" class="btn btn-outline-info btn-rounded btn-bordered col-8"><i class="fas fa-star"></i> {translate l="LBL_FAVORITE"}</button>
					</div>
				</div>
				<div class="row margin-5">
					<div class="col-12 primary-row center">
						<button type="button" class="btn btn-outline-danger btn-bordered col-8" data-dismiss="modal">{translate l="LBL_CANCEL"}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="ImportModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="ImportModalLabel">{translate l="LBL_IMPORT_MUSICS_FROM_YT"}</h6>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 margin-b-10">
						<label for="ImportModalLink">{translate l="LBL_LINK_YT"}</label>
						<input class="form-control" type="text" id="ImportModalLink" name="ImportModalLink" autocomplete="off" />
					</div>
					<div class="col-12 margin-b-10" id="ImportModalLinkTitleDiv"></div>
					<div class="col-12">
						<button class="btn btn-outline-success btn-rounded margin-5" id="ImportMusicaButton">{translate l="LBL_IMPORT"}</button>
						<button class="btn btn-outline-info btn-rounded margin-5" id="ImportMusicaAndFilaButton">{translate l="LBL_IMPORT_AND_INSERT_TO_WAITLIST"}</button>
						<button class="btn btn-outline-warning btn-rounded margin-5" data-dismiss="modal">{translate l="LBL_CANCEL"}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="helpSongsModal" tabindex="-1" role="dialog" aria-labelledby="helpSongsModalLabel" aria-hidden="true"></div>
<script type="text/javascript" src="{$app_url}jsManager/Musicas/index.js?v={$ch_ver}"></script>
{/if}
{if !$bdOnly AND $showPopupWizard}
	<script type="text/javascript">showPopupWizard();</script>
{/if}