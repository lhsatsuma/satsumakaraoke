var isControleOpen = false;
$('#menu_CONTROLE').on('click', () =>{
	getLastVolume();
	isControleOpen = true;
	if(hasToggleMenu){
		$(".page-wrapper").removeClass("toggled");
	}
	$('#ControleRemotoModal').modal('show');
	$('#ControleRemotoModal').draggable(); 
});
$('#show-sidebar').on('click', () =>{
	if(hasToggleMenu){
		isControleOpen = false;
	}
});
$('#volumeRange').val(100);
function getLastVolume(needCheck = false)
{
	handleAjax({
		url: _APP.app_url+'Karaoke_ajax/k_get_thread_copy',
		dontFireError: true,
		beforeSend: (res) => {
			showLoadingIcon($('#ControleRemotoModalLabel'));
		},
		callback: (res) => {
			if(res.detail){
				if(res.detail !== null
					&& res.detail.volume !== null){
					$('#volumeRange').val(res.detail.volume);
					$('#volP').html(res.detail.volume+'%');
				}
			}
		},
		callbackAll: (res) => {
			hideLoadingIcon($('#ControleRemotoModalLabel'));
			if(needCheck){
				checkControleOpen();
			}
		},
		callbackError: (res) => {
			showErrorIcon($('#ControleRemotoModalLabel'));
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
//getLastVolume();
checkControleOpen();


$('.controlbtns').on('click', (e) => {
	let domObj = $(e.currentTarget);
	$(domObj).trigger('focusout');
	handleAjax({
		url: _APP.app_url+'Karaoke_ajax/k_set_thread',
		dontFireError: true,
		data: {
			action: $(domObj).attr('data-val'),
		},
		beforeSend: () => {
			showLoadingIcon($('#ControleRemotoModalLabel'));
		},
		callbackAll: (res) => {
			hideLoadingIcon($('#ControleRemotoModalLabel'));
		},
		callbackError: (res) => {
			showErrorIcon($('#ControleRemotoModalLabel'));
		}
	});
})
document.getElementById('volumeRange').addEventListener('input', function() {
	changedVolumeRange();
});
function changedVolumeRange()
{
	$('#volP').html($('#volumeRange').val()+'%');
	handleAjax({
		url: _APP.app_url+'Karaoke_ajax/k_set_thread',
		dontFireError: true,
		data: {
			action: 'volume',
			valueTo: $('#volumeRange').val(),
		},
		beforeSend: () => {
			showLoadingIcon($('#ControleRemotoModalLabel'));
		},
		callbackAll: (res) => {
			hideLoadingIcon($('#ControleRemotoModalLabel'));
		},
		callbackError: (res) => {
			showErrorIcon($('#ControleRemotoModalLabel'));
		}
	});
}