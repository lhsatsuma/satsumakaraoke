<table class="table table-responsive-xl table-striped table-list">
	<thead>
		<tr>
			<th scope="col" dt-h-field="rank">Rank</th>
			<th scope="col" dt-h-field="total">Total</th>
			<th scope="col" dt-h-field="musicas_id">Música</th>
		</tr>
	</thead>
	<tbody>
		{if empty($records)}
		<tr>
			<td colspan="2">Nenhum registro encontrado!</td>
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