$('.row-data-select').each(function(){
	$(this).click(function(){
		OpenModalSelected($(this).attr('dt-r-id'));
	});
	
});
function OpenModalSelected(id){
	var row = $('tr[dt-r-id="'+id+'"]');
	const codigo = row.find('th[dt-r-codigo]').attr('dt-r-codigo');
	const nome = row.find('td[dt-r-nome]').attr('dt-r-nome');
	$('#IdInsertModal').val(id);
	$('#SelectedRowModalLabel').html('['+codigo+'] '+nome);
	$('#SelectedRowModal').modal('show');
}
$('#InsertFilaBtn').click(function(){
	$('#SelectedRowModal').modal('hide');
	fireLoading({
		didOpen: () => {
			Swal.showLoading();
			handleAjax({
				url: app_url+'musicas/insert_fila_ajax',
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
lastImportLink = '';
$('#ImportModalLink').keyup(function(){

	if($(this).val().indexOf('http://') == -1
	&& $(this).val().indexOf('https://') == -1
	&& $(this).val().indexOf('youtube') == -1
	&& $(this).val().indexOf('youtu.be') == -1
	&& $(this).val().indexOf('youtu.be') == -1
	&& lastImportLink !== $(this).val()
	){
		return;
	}
	lastImportLink = $(this).val();
	$('#ImportModalLink').removeClass('invalid-value');
	$('#ImportModalLink').parent().find('.validation-error').remove();
	$('#ImportMusicaButton').prop('disabled', true);
	$('#ImportMusicaAndFilaButton').prop('disabled', true);
	
	if($(this).val() !== ''){
		swal.fire({
			title: 'Procurando...',
			onBeforeOpen: () => {
				swal.showLoading();
				$.ajax({
					'url': app_url+'musicas/CheckImportVideo',
					dataType: 'json',
					method: 'POST',
					headers: {
					  "Content-Type": "application/json",
					  "X-Requested-With": "XMLHttpRequest"
					},
					data: JSON.stringify({ 
						'link': $('#ImportModalLink').val(),
					}),
					success: function(d){
						swal.close();
					},
					complete: function(d){
						var r = d.responseJSON;
						if(!!r.status){
							$('#ImportModalLinkTitleDiv').html('<p><label>Nome</label><input type="hidden" id="ImportModalLinkMD5" name="ImportModalLinkMD5" value="'+r.detail.md5+'"/><input class="form-control" type="text" id="ImportModalLinkTitle" name="ImportModalLinkTitle" value="'+fixNameUtf8(r.detail.title)+'"/></p><p><button type="button" class="btn btn-info" onclick="changeByTraco(this)">Inverter Titulo/cantor</button></p><p><label>Tipo</label><select class="form-control" id="ImportModalLinkTipo" name="ImportModalLinkTipo"><option value="N/A">N/A</option><option value="INT">INT</option><option value="BRL">BRL</option><option value="ESP">ESP</option><option value="JPN">JPN</option><option value="OTR">OTR</option></select></p>');
							$('#ImportMusicaButton').prop('disabled', false);
							$('#ImportMusicaAndFilaButton').prop('disabled', false);
						}else{
							$('#ImportModalLink').addClass('invalid-value').after('<span class="validation-error required">'+r.detail+'</span>');
						}
						swal.close();
						
					}
				});
			},
		});
	}
}).change();
$('#ImportMusicaButton, #ImportMusicaAndFilaButton').click(function(){
	if($(this).prop('disabled') || !$('#ImportModalLinkTitle').length || !$('#ImportModalLinkMD5').length){
		return;
	}
	$('#ImportModal').modal('hide');
	swal.fire({
		title: 'Importando...',
		didOpen: () => {
			swal.showLoading();
			$.ajax({
				'url': app_url+'musicas/ImportVideoUrl',
				dataType: 'json',
				method: 'POST',
				headers: {
				  "Content-Type": "application/json",
				  "X-Requested-With": "XMLHttpRequest"
				},
				data: JSON.stringify({ 
					'link': $('#ImportModalLink').val(),
					'md5': $('#ImportModalLinkMD5').val(),
					'title': $('#ImportModalLinkTitle').val(),
					'tipo': $('#ImportModalLinkTipo').val(),
					'auto_fila': ($(this).attr('id') == 'ImportMusicaAndFilaButton') ? true : false,
				}),
				success: function(d){
					swal.close();
				},
				complete: function(d){
					var r = d.responseJSON;
					swal.close();
					if(!!r){
						if(r.status){
							let title_sa_fire = 'Música importada';
							if(r.detail.auto_fila){
								title_sa_fire += " e colocada na fila";
							}
							Swal.fire({
								title: title_sa_fire,
								text: r.detail.saved_record.nome,
								icon: 'success',
								width: '400px',
								showConfirmButton: false,
								timer: 1000,
								timerProgressBar: true,
								didClose: () => {
									$('#filtroForm').submit();
								}
							});
						}else{
							Swal.fire({
								title: 'Erro ao importar a música!',
								text: r.error_msg,
								icon: 'error',
								width: '400px',
							});
						}
					}else{
						Swal.fire({
							title: 'Erro ao importar a música',
							text: '',
							icon: 'error',
							width: '400px',
						});
					}
				}
			});
		},
	});
});
function changeByTraco(){
    let oldVal = $('#ImportModalLinkTitle').val();
    let exploded = [];
    
    exploded = oldVal.split(' - ');
    if(exploded.length > 1){
        $('#ImportModalLinkTitle').val(exploded[1]+' - '+exploded[0]);
        return;
    }
    
    exploded = oldVal.split(' : ');
    if(exploded.length > 1){
        $('#ImportModalLinkTitle').val(exploded[1]+' - '+exploded[0]);
        return;
    }
    
    exploded = oldVal.split(' in the Style of ');
    if(exploded.length > 1){
        $('#ImportModalLinkTitle').val(exploded[1]+' - '+exploded[0]);
        return;
    }
};