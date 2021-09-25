
var video = null;
var its_running_thread = false;
var its_running_thread_list = false;
var last_volume = 1;
var musicsLine = [];
var keyListSong = null;
var songNow = [];
var allScreen = 1;
var numSongsList = 7;
var shortName = true;
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
	$('#bdKaraoke').html(`<div class="col-4">
	<div class="row">
		<div class="col-12 mt-2 mb-2 center karaokeLogo">
			<img src="${_app_vars.app_url}images/logo.png" style="width: 65%"/>
		</div>
	</div>
	<div class="row" id="SongLists">
		<div class="col-12 center">
			<p><strong>Músicas na Fila:</strong></p>
		</div>
		<div class="col-12 h-75" id="SongListsDiv"></div>
	</div>
</div>
<div class="col-8">
	<div class="row">
		<div class="col-12 center playingNowDiv" style="padding: 30px 0px 15px 0px">
			<h4><span id="playingNow"></span></h4>
			<div id="pausedDiv" class="center" style="display: none">
				<h3>VIDEO PAUSADO!</h3>
			</div>
		</div>
		<div class="col-12 center videoDiv">
			<input type="hidden" id="songNowId" value=""/>
			<video id="video" autoplay>
				<source id="videoSrc" src="" type="video/mp4" />
			</video>
		</div>
		<div class="col-12 center volumeDiv">
			<h3>Volume: <span id="volumeSpan">100%</span></h3>
		</div>
	</div>
</div>
<div id="joinUsKaraoke" class="col-12 center">
	<h1>Cante com nós! Acesse <span class="b800">${_app_vars.host_fila}</span></h1>
</div>`);
	video = document.getElementById('video');
	$('#InitialModal').modal('hide');
	video.onloadeddata = function() {
		resizeVideo();
	};
	video.onpause = (event) => {
		if(video.paused && video.src !== "" && video.duration != video.currentTime){
			$('#pausedDiv').show();
		}else{
			$('#pausedDiv').hide();
		}
	};
	$(window).resize(() => {
		resizeVideo();
	});
	video.addEventListener('ended',endedVideo,false);
	handleAjax({
		url: _app_vars.app_url+'Karaoke_ajax/k_get_thread_copy',
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

function removeInitialOnlyFila()
{
	shortName = false;
	numSongsList = 10;
	$('#bdKaraoke').html(`
	<div class="col-12">
		<div class="row">
			<div class="col-4 mt-2 mb-2 center karaokeLogo">
				<img src="${_app_vars.app_url}images/logo.png" style="width: 65%"/>
			</div>
			<div class="col-8 mt-4 mb-2 left">
				<h1>Cante com nós! Acesse <span class="b800">${host_fila}</span></h1>
			</div>
		</div>
		<div class="row" id="SongLists">
			<div class="col-12 center">
				<h2><strong>Músicas na Fila:</strong></h2>
			</div>
			<div class="col-12 h-75 center" id="SongListsDiv"></div>
		</div>
	</div>`);
	allScreen = 0;
	$('#InitialModal').modal('hide');
	getListSongs(false);
}

function getListSongs(dontRefresh)
{
	if(its_running_thread_list){
		return;
	}
	its_running_thread_list = true;
	$.ajax({
		url: _app_vars.app_url+'admin/Karaoke_ajax/k_musics_list',
		method: 'post',
		dataType: 'json',
		headers: {
		  "Content-Type": "application/json",
		  "X-Requested-With": "XMLHttpRequest"
		},
		data: JSON.stringify({
			sh: shortName,
			ct: numSongsList
		}),
		success: function(d){
			
		},
		complete: function(d){
			var r = d.responseJSON;
			if(!!r){
				if(r.status){
					musicsLine = [];
					songNow = [];
					$.each(r.detail.s, (idx, ipt) =>{
						if(idx == 0){
							if(allScreen){
								songNow = ipt;
							}
						}else{
							musicsLine.push(ipt);
						}
					});
					mountWaitList(dontRefresh, r.detail.t);
				}else{
					console.log('Não foi possível buscar a lista de músicas na fila!');
				}
			}else{
				console.log('Não foi possível buscar a lista de músicas na fila!');
			}
		},
		error: function(d){
			musicsLine = [];
			songNow = [];
			mountWaitList(dontRefresh);
		}
	});
}

function mountWaitList(dontRefresh, totalFound = 0)
{
	if(typeof dontRefresh == 'undefined'){ dontRefresh = false; }

	$('#SongListsDiv').html('');
	$.each(musicsLine, (idx, ipt) => {
		if(idx < numSongsList){
			turn = idx + 1;
			$('#SongListsDiv').append('<p>'+turn+'. ' + ipt[1]+' | ['+ipt[2]+']'+ ipt[3]+'</p>');
		}
	});
	if(totalFound - 1 > musicsLine.length){
		let leftSongs = totalFound - musicsLine.length - 1;
		$('#SongListsDiv').append('<p>....Mais '+leftSongs+' música(s) na fila....</p>');
	}
	if(!!songNow[1]){
		$('#playingNow').html('<p>'+songNow[1]+' | ['+songNow[2]+'] '+ songNow[3]+'</p>');
		if(allScreen){
			$('#songNowId').val(songNow[0]);
			if(video.src == _app_vars.app_url + 'musicas/karaoke' || video.src == "" || video.src !== _app_vars.karaokeURL + 'uploads/VIDEOSKARAOKE/' + songNow[4]+'.mp4'){
				getNextVideo();
			}
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
	var wait_mil = 3000;
	$.ajax({
		'url': _app_vars.app_url+'admin/Karaoke_ajax/k_get_thread',
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
						wait_mil = 1000;
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
						wait_mil = 1000;
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
						wait_mil = 1000;
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
					url: _app_vars.app_url+'admin/Karaoke_ajax/k_reset_thread',
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
		video.src = _app_vars.karaokeURL + 'uploads/VIDEOSKARAOKE/' + songNow[4]+'.mp4';
		$('#pausedDiv').hide();
	}
}

function endedVideo(e)
{
	video.src = "";
	$('#playingNow').html('<p>&nbsp</p>');
	$.ajax({
		'url': _app_vars.app_url+'admin/Karaoke_ajax/k_ended_video',
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