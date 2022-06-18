<table class="table table-responsive-xl table-striped table-list">
	<thead>
		<tr>
			<th scope="col" dt-h-field="rank">{translate l="LBL_RANK"}</th>
			<th scope="col" dt-h-field="total">{translate l="LBL_TOTAL"}</th>
			<th scope="col" dt-h-field="musicas_id">{translate l="LBL_MUSIC_ID"}</th>
		</tr>
	</thead>
	<tbody>
		{if empty($records)}
		<tr>
			<td colspan="2">{translate l="LBL_NO_RECORDS_FOUND"}</td>
		</tr>
		{else}
		{assign var="rankTop" value="1"}
		{foreach from=$records item=campos}
		<tr class="ptr">
			<th>#{$rankTop}</th>
			<th>{$campos.total}</th>
			<td dt-r-musica_id_name="{$campos.musica_id_name}">{$campos.musica_id_name}</td>
		</tr>
		{assign var="rankTop" value=$rankTop+1}
		{/foreach}
		{/if}
	</tbody>
</table>