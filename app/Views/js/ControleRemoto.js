var isControleOpen = false;
$('#ControleRemotoButton').on('click', () =>{
	getLastVolume();
	isControleOpen = true;
	if(hasToggleMenu){
		$(".page-wrapper").removeClass("toggled");
	}
	$('#ControleRemotoModal').modal('show');
	$('#ControleRemotoModal').draggable(); 
});
$('#volumeRange').val(100);
function getLastVolume(needCheck = false)
{
	handleAjax({
		url: _app_vars.app_url+'Karaoke_ajax/k_get_thread_copy',
		dontfireError: true,
		beforeSend: (res) => {
			showLoadingIcon($('#ControleRemotoModalLabel'));
		},
		callback: (res) => {
			if(res.detail !== null
				&& res.detail.volume !== null){
				$('#volumeRange').val(res.detail.volume);
				$('#volP').html(res.detail.volume+'%');
				hideLoadingIcon($('#ControleRemotoModalLabel'));
				if(needCheck){
					checkControleOpen();
				}
			}
		}
	});
}
$('#ControleRemotoModal').on('hidden.bs.modal', (e) => {
	isControleOpen = false;
});
function checkControleOpen()
{
	setTimeout(() => {
		if(isControleOpen){
			getLastVolume(true);
		}else{
			checkControleOpen();
		}
	}, 5000);
}
getLastVolume();
checkControleOpen();


$('.controlbtns').on('click', (e) => {
	let domObj = $(e.currentTarget);
	$(domObj).trigger('focusout');

	handleAjax({
		url: _app_vars.app_url+'Karaoke_ajax/k_set_thread',
		data: {
			action: $(domObj).attr('data-val'),
		},
		beforeSend: () => {
			showLoadingIcon($('#ControleRemotoModalLabel'));
		},
		callback: (res) =>{
			hideLoadingIcon($('#ControleRemotoModalLabel'));
		},
	});
})
document.getElementById('volumeRange').addEventListener('input', function() {
	$('#volP').html($(this).val()+'%');
	$.ajax({
		'url': _app_vars.app_url+'Karaoke_ajax/k_set_thread',
		'method': 'post',
		'dataType': 'json',
		headers: {
		  "Content-Type": "application/json",
		  "X-Requested-With": "XMLHttpRequest"
		},
		data: JSON.stringify({
			'action': 'volume',
			'valueTo': $(this).val(),
		}),
		success: function(d){
			
		},
		complete: function(d){
			var r = d.responseJSON;
			if(!!r){
				if(r.status){
					//Everything ok with Thread
				}else{
					console.log('Não foi possível buscar a lista de músicas na fila!');
				}
			}else{
				console.log('Não foi possível buscar a lista de músicas na fila!');
			}
		},
		error: function(d){
			console.log(d);
		}
	});
});