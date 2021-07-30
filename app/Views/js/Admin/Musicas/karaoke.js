var video = document.getElementById('video');
video.onloadeddata = function() {
	resizeVideo();
};
video.onpause = (event) => {
	if(video.paused && video.src !== ""){
		$('#pausedDiv').show();
	}else{
		$('#pausedDiv').hide();
	}
};
var videoSrc = document.getElementById('videoSrc');
var its_running_thread = false;
var its_running_thread_list = false;
video.addEventListener('ended',endedVideo,false);
var last_volume = 1;
var musicsLine = [];
var keyListSong = null;
var songNow = [];
$(window).resize(() => {
	resizeVideo();
});
$(document).ready(function(){
	$('#InitialModal').modal('show');
});
function resizeVideo()
{
	let windowTotalHeight = window.innerHeight;

	let window70Height = (windowTotalHeight * 65) / 100;
	let videoHeight = video.videoHeight;

	let videoHeightPerc = (videoHeight * 100) / windowTotalHeight;
	$('#video').css('height', window70Height);
}
function removeInitial()
{
	$('#InitialModal').modal('hide');

	handleAjax({
		url: app_url+'Karaoke_ajax/k_get_thread_copy',
		callback: (res) => {
			if(res.detail !== null
				&& res.detail.volume !== null){
				video.volume = res.detail.volume / 100;
				last_volume = res.detail.volume / 100;
				$('#volumeSpan').html(res.detail.volume + '%');
			}
		},
		callbackAll: (res) => {
			getListSongs();

			getThread();

			getNextVideo();
		}
	});
}

function getListSongs(dontRefresh)
{
	if(its_running_thread_list){
		return;
	}
	its_running_thread_list = true;
	$.ajax({
		url: app_url+'admin/Karaoke_ajax/k_musics_list',
		method: 'post',
		dataType: 'json',
		headers: {
		  "Content-Type": "application/json",
		  "X-Requested-With": "XMLHttpRequest"
		},
		success: function(d){
			
		},
		complete: function(d){
			var r = d.responseJSON;
			if(!!r){
				if(r.status){
					musicsLine = [];
					songNow = [];
					$.each(r.detail, (idx, ipt) =>{
						if(idx == 0){
							songNow = ipt;
						}else{
							musicsLine.push(ipt);
						}
					});
					mountWaitList(dontRefresh);
				}else{
					console.log('Não foi possível buscar a lista de músicas na fila!');
				}
			}else{
				console.log('Não foi possível buscar a lista de músicas na fila!');
			}
		},
		error: function(d){
			console.log(d);
			musicsLine = [];
			songNow = [];
			mountWaitList(dontRefresh);
		}
	});
}

function mountWaitList(dontRefresh)
{
	if(typeof dontRefresh == 'undefined'){ dontRefresh = false; }

	$('#SongListsDiv').html('');
	$.each(musicsLine, (idx, ipt) => {
		if(idx < 7){
			turn = idx + 1;
			$('#SongListsDiv').append('<p>'+turn+'. ' + ipt[1]+' | ['+ipt[2]+']'+ ipt[3]+'</p>');
		}
	});
	if(musicsLine.length > 7){
		let leftSongs = musicsLine.length - 7;
		$('#SongListsDiv').append('<p>...Mais '+leftSongs+' música(s) na fila....</p>');
	}
	if(!!songNow[1]){
		$('#playingNow').html('<p>'+songNow[1]+' | ['+songNow[2]+'] '+ songNow[3]+'</p>');
		$('#songNowId').val(songNow[0]);
		if(video.src == app_url + 'musicas/karaoke' || video.src == "" || video.src !== karaokeURL + 'uploads/VIDEOSKARAOKE/' + songNow[4]+'.mp4'){
			getNextVideo();
		}
	}
	its_running_thread_list = false;
	if(!dontRefresh){
		setTimeout(() => {
			getListSongs();
		}, 5000);
	}
}

function getThread()
{
	if(its_running_thread){
		return;
	}
	its_running_thread = true;
	var wait_mil = 1500;
	$.ajax({
		'url': app_url+'admin/Karaoke_ajax/k_get_thread',
		'method': 'post',
		'dataType': 'json',
		headers: {
		  "Content-Type": "application/json",
		  "X-Requested-With": "XMLHttpRequest"
		},
		success: function(d){
			
		},
		complete: function(d){
			var r = d.responseJSON;
			if(!!r.detail){
				let action = r.detail.action;
				switch(action){
					case 'play':
						video.play();
						break;
					case 'pause':
						video.pause();
						break;
					case 'next':
						endedVideo();
						break;
					case 'repeat':
						video.currentTime = 0;
						break;
					case 'volume':
						volSpan = parseFloat(r.detail.valueTo);
						let volumeAllFloat = volSpan / 100;
						video.volume = volumeAllFloat;
						last_volume = volumeAllFloat;
						wait_mil = 200;
						$('#volumeSpan').html(volSpan + '%');
						break;
					case 'vol_down':
						let volumeFloat = parseFloat(video.volume.toFixed(2));
						if(volumeFloat > 0){
							volumeFloat -= 0.1;
							volSpan = parseFloat((volumeFloat * 100).toFixed(2));
							video.volume = volumeFloat;
						}else{
							volSpan = 0;
						}
						video.volume = volumeFloat;
						last_volume = volumeFloat;
						wait_mil = 200;
						$('#volumeSpan').html(volSpan + '%');
						break;
					case 'vol_up':
						let volumeUpFloat = parseFloat(video.volume.toFixed(2));
						if(volumeUpFloat < 1){
							volumeUpFloat += 0.1;
							volSpan = parseFloat((volumeUpFloat * 100).toFixed(2));
							video.volume = volumeUpFloat;
						}else{
							volSpan = 100;
						}
						video.volume = volumeUpFloat;
						last_volume = volumeUpFloat;
						wait_mil = 200;
						$('#volumeSpan').html(volSpan + '%');
						break;
					case 'mute':
						let volumeMuteFloat = parseFloat(video.volume.toFixed(2));
						if(volumeMuteFloat > 0){
							volSpan = 0;
							video.volume = 0;
						}else{
							video.volume = last_volume;
							volSpan = last_volume * 100;
						}
						$('#volumeSpan').html(volSpan + '%');
						break;
					default:
						break;
				}
				handleAjax({
					url: app_url+'admin/Karaoke_ajax/k_reset_thread',
					callback: (res) => {
						its_running_thread = false;
						setTimeout(function(){
							getThread();
						}, wait_mil);
					}
				});
			}else{
				its_running_thread = false;
				setTimeout(function(){
					getThread();
				}, wait_mil);
			}
		},
		error: function(d){
			console.log(d);
		}
	});
}

function getNextVideo()
{
	if(!!songNow[4]){
		video.src = karaokeURL + 'uploads/VIDEOSKARAOKE/' + songNow[4]+'.mp4';
		$('#pausedDiv').hide();
	}
}

function endedVideo(e)
{
	video.src = "";
	$('#playingNow').html('<p>&nbsp</p>');
	$.ajax({
		'url': app_url+'admin/Karaoke_ajax/k_ended_video',
		'method': 'post',
		'dataType': 'json',
		headers: {
		  "Content-Type": "application/json",
		  "X-Requested-With": "XMLHttpRequest"
		},
		data: JSON.stringify({
			'id': $('#songNowId').val()
		}),
		success: function(d){
			
		},
		complete: function(d){
			getListSongs(true);
		},
		error: function(d){
			console.log(d);
		}
	});
}