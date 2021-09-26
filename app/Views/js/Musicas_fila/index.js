$('#SearchByAssigned').on('click', () =>{
	if($('#search_usuario_criacao').val() == '|ASSIGNED_ONLY|'){
		$('#search_usuario_criacao').val('');
	}else{
		$('#search_usuario_criacao').val('|ASSIGNED_ONLY|');
	}
	$('#filtroForm').trigger('submit');
});
$('#SearchByPendente').on('click', () =>{
	if($('#search_status').val() == 'pendente'){
		$('#search_status').val('');
	}else{
		$('#search_status').val('pendente');
	}
	$('#filtroForm').trigger('submit');
});
$('#SearchByEncerrados').on('click', () =>{
	if($('#search_status').val() == 'encerrado'){
		$('#search_status').val('');
	}else{
		$('#search_status').val('encerrado');
	}
	$('#filtroForm').trigger('submit');
});

function addEventRowData()
{
	$('.r-dt-slct').each(function(){
		$(this).on('click', (e) =>{
			OpenModalSelected($(e.currentTarget).attr('dt-r-id'));
		});
	});
}
addEventRowData();
function OpenModalSelected(id){
	var row = $('tr[dt-r-id="'+id+'"]');
	const musica_id = row.find('input[dt-r-musica_id]').attr('dt-r-musica_id');
	const nome = row.find('td[dt-r-musica_id_nome]').attr('dt-r-musica_id_nome');
	$('#IdInsertModal').val(musica_id);
	$('#SelectedRowModalLabel').html(nome);
	$('#SelectedRowModal').modal('show');
}
$('#InsertFilaBtn').on('click', () =>{
	$('#SelectedRowModal').modal('hide');
	fireLoading({
		didOpen: () => {
			Swal.showLoading();
			handleAjax({
				url: _app_vars.app_url+'musicas/insert_fila_ajax',
				data: JSON.stringify({
					id: $('#IdInsertModal').val(),
				}),
				callback: (res) => {
					Swal.close();
					Swal.fire({
						title: 'Música inserida na fila com sucesso!',
						text: '',
						icon: 'success',
						width: '400px',
						showConfirmButton: false,
						timer: 1000,
						timerProgressBar: true
					});
				}
			});
		}
	})
});