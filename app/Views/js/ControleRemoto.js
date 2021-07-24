$('#ControleRemotoButton').click(function(){
	if(hasToggleMenu){
		$(".page-wrapper").removeClass("toggled");
	}
	$('#ControleRemotoModal').modal('show');
	$('#ControleRemotoModal').draggable(); 
});
$('#volumeRange').val(100);
function getLastVolume()
{
	handleAjax({
		url: app_url+'Musicas_fila_ajax/k_get_thread_copy',
		dontfireError: true,
		callback: (res) => {
			if(res.detail !== null
				&& res.detail.volume !== null){
				$('#volumeRange').val(res.detail.volume);
				$('#volP').html(res.detail.volume+'%');
			}
			setTimeout(function(){
				getLastVolume();
			}, 5000);
		}
	});
}
getLastVolume();
$('.controlbtns').click(function(){
	let domObj = $(this);
	
	$.ajax({
		'url': app_url+'Musicas_fila_ajax/k_set_thread',
		'method': 'post',
		'dataType': 'json',
		headers: {
		  "Content-Type": "application/json",
		  "X-Requested-With": "XMLHttpRequest"
		},
		data: JSON.stringify({
			'action': domObj.attr('data-val'),
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
})
$('#volumeRange').change(function(){
	let domObj = $(this);
	$('#volP').html($(this).val()+'%');
	$.ajax({
		'url': app_url+'Musicas_fila_ajax/k_set_thread',
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
})