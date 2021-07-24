{$filter_template}
<table class="table table-striped table-list collapse {if $layout_list.table_id}{$layout_list.table_id}{/if} show" style="margin-top: 10px;" id="{if $layout_list.table_id}{$layout_list.table_id}{/if}">
	<thead>
		<tr>
			{if $layout_list.has_edit}
				<th>&nbsp;</th>
			{/if}
		{foreach from=$layout_list.table_heads item=thead key=thead_name}
			<th scope="col" class="pointer {$thead.class}" dt-h-field="{$thead_name}" onclick="OrderByFiltroSubpanel('{$layout_list.table_id}', '{$thead_name}')">{$thead.label} <span class="icon-order-by"></span></th>
		{/foreach}
		</tr>
	</thead>
	<tbody>
		{if $layout_list.table_tbody.has_records}
			{foreach from=$layout_list.table_tbody.records item=body_record}
				<tr class="row-data-select" dt-r-id="{$body_record.id_value}">
					{if $layout_list.has_edit}
						<td><a href="{$body_record.location_href|replace:"/detalhes":"/editar"}"><i class="fas fa-edit"></i></a></td>
					{/if}
					{foreach from=$body_record.columns item=tbody_col key=tbody_name}
						<td class="{$layout_list.table_heads[$tbody_name].class}" dt-r-{$tbody_col.name}="{$tbody_col.name}" >
							{if $tbody_col.link_record}
								<a href="{$tbody_col.link_record}">{$tbody_col.value}</a>
							{else}
								{$tbody_col.value}
							{/if}
						</td>
					{/foreach}
				</tr>
			{/foreach}
		{else}
		<tr class="row-data-select">
			<td colspan="{if $layout_list.has_edit}{count($layout_list.table_heads)+1}{else}{count($layout_list.table_heads)}{/if}">Nenhum registro encontrado!</td>
		</tr>
		{/if}
	</tbody>
</table>
<table class="table table-striped table-list collapse {if $layout_list.table_id}{$layout_list.table_id}{/if} show table-pagination" id="{if $layout_list.table_id}{$layout_list.table_id}_pagination{/if}">
	<tbody>
		<tr>
			<td>{$pagination}</td>
		</tr>
	</tbody>
</table>