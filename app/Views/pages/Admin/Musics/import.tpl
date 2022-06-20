<div class="row">
	<div class="col-12">
		<p>LINK|^|NOME|^|TIPO</p>
		<textarea class="form-control" rows="10" id="links">
		https://www.youtube.com/watch?v=_SJV7NWxzus&ab_channel=MuramatsuKaraoke&#10;
		</textarea>
	</div>
	<div class="col-12 mt-2">
		<button type="button" class="btn btn-outline-info btn-rounded searchLinks">Procurar</button>
	</div>
</div>

<table class="table table-striped mt-5">
	<thead>
		<tr class="d-flex">
			<th class="col-2 col-xl-2">Link</th>
			<th class="col-2 col-xl-1">Tipo</th>
			<th class="col-6 col-xl-6">Nome</th>
			<th class="col-2 col-xl-2">&nbsp;</th>
			<th class="col-2 col-xl-1">&nbsp;<span class="counterSeconds">0</span> segundos</th>
		</tr>
	</thead>
	<tbody id="searchedLinks"></tbody>
	<tfoot>
		<tr>
			<td colspan="4"><button type="button" class="btn btn-outline-success btn-rounded" onclick="finallyImport()">Importar Músicas</button></td>
		</tr>
	</tfoot>
</table>

<script type="text/javascript" src="{$app_url}jsManager/Admin/Musicas/import.js?v={$ch_ver}"></script>