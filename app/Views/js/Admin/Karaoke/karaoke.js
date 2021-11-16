
var musicsLine = [];
var keyListSong = null;
$(document).ready(function(){
	$('#InitialModal').modal('show');
});

class KaraokeJS{
	constructor()
	{
		this.url = _app_vars.app_url+'admin/Karaoke_ajax/';
		this.running = {
			'nextVideo': false,
			'list': false,
			'thread': false,
		};
		this.shortName = true;
		this.numSongsList = 7;
		this.last_volume = 1;
	}
	/*
	* type 1 -> Complete (video, next songs)
	* type 2 -> Video only
	* type 3 -> next songs only
	* type 4 -> next songs with remote control
	*/
	removeInitial(type)
	{
		this.typeScreen = type;

		handleAjax({
			url: _app_vars.app_url+'admin/karaoke_ajax/k_get_body',
			data: {
				id: type,
			},
			callback: (res) => {
				$('#bdKaraoke').html(res.detail);
			},
			callbackAll: (res) => {
				if(type == 1 || type == 2){
					this.video = document.getElementById('video');
					handleAjax({
						dontFireError: true,
						url: _app_vars.app_url+'Karaoke_ajax/k_get_thread_copy',
						callback: (res) => {
							if(res.detail !== null
								&& res.detail.volume !== null){

								this.video.volume = res.detail.volume / 100;
								this.last_volume = res.detail.volume / 100;
								$('#volumeSpan').html(res.detail.volume + '%');
							}
						},
						callbackAll: (res) => {
							this.setInitialVars();
						}
					});
				}else{
					this.setInitialVars();
				}
			}
		});
	}
	setInitialVars()
	{
		$('#InitialModal').modal('hide');
		if(this.typeScreen == 3){
			this.numSongsList = 14;
		}
		if(this.typeScreen == 1 || this.typeScreen == 2){
			this.video.onloadeddata = function() {
				karaoke.resizeVideo();
			};
			$(window).on('resize', () => {
				karaoke.resizeVideo();
			});
			this.video.onpause = (event) => {
				if(video.paused && video.src !== "" && video.duration != video.currentTime){
					$('#pausedDiv').show();
				}else{
					$('#pausedDiv').hide();
				}
			};
			this.video.onplay = (event) => {
				$('#pausedDiv').hide();
			};
			video.addEventListener('ended',this.endedVideo,false);
			this.getNextVideo();
			this.getThread();
		}
		if(this.typeScreen == 1 || this.typeScreen == 3 || this.typeScreen == 4){
			this.mountWaitList();
		}
	}
	endedVideo()
	{
		if(karaoke.url){
			karaoke.video.src = "";
			$('#playingNow').html('<p>&nbsp</p>');
			handleAjax({
				url: karaoke.url+'k_ended_video',
				data: {
					'id': $('#songNowId').val()
				},
				callback: (d) => {
					karaoke.songNow = [];
					$('#songNowId').val('');
					karaoke.getNextVideo();
					karaoke.mountWaitList(true);
				},
			});
		}
	}
	getNextVideo()
	{
		if(this.running.nextVideo){
			return;
		}
		this.running.nextVideo = true;
		handleAjax({
			url: karaoke.url+'k_musics_list',
			data: {
				sh: 0,
				ct: 1,
				of: 0,
			},
			callback: (res) => {
				if(res.detail){
					if(res.detail.s.length && res.detail.s[0][4]){
						this.songNow = res.detail.s[0];
						$('#songNowId').val(this.songNow[0]);
						video.src = _app_vars.karaokeURL + 'uploads/VIDEOSKARAOKE/' + this.songNow[4]+'.mp4';
						$('#playingNow').html('<p>'+this.songNow[1]+' | ['+this.songNow[2]+'] '+ this.songNow[3]+'</p>');
						$('#pausedDiv').hide();
					}
				}else{
					setTimeout(() => {
						karaoke.getNextVideo();
					}, 2000);
				}
			},
			callbackAll: (res) => {
				this.running.nextVideo = false;
			}
		});
	}
	getThread()
	{
		if(this.running.thread){
			return;
		}
		this.running.thread = true;
		var wait_mil = 3000;
		handleAjax({
			url: this.url+'k_get_thread',
			dontFireError: true, 
			callback: (res) => {
				if(res.detail){
					let action = res.detail.action;
					switch(action){
						case 'play':
							this.video.play();
							break;
						case 'pause':
							this.video.pause();
							break;
						case 'next':
							karaoke.endedVideo();
							break;
						case 'repeat':
							this.video.currentTime = 0;
							break;
						case 'volume':
							let volSpan = parseFloat(res.detail.valueTo);
							let volumeAllFloat = volSpan / 100;
							video.volume = volumeAllFloat;
							this.last_volume = volumeAllFloat;
							wait_mil = 1000;
							$('#volumeSpan').html(volSpan + '%');
							break;
						case 'mute':
							let volumeMuteFloat = parseFloat(video.volume.toFixed(2));
							let volSpanMute = 0;
							if(volumeMuteFloat <= 0){
								video.volume = this.last_volume;
								volSpanMute = this.last_volume * 100;
							}else{
								video.volume = 0;
							}
							$('#volumeSpan').html(volSpanMute + '%');
							break;
						default:
							break;
					}
					handleAjax({
						url: _app_vars.app_url+'admin/Karaoke_ajax/k_reset_thread',
						callback: (res) => {
							karaoke.running.thread = false;
						}
					});
				}
			},
			callbackAll: (res) => {
				karaoke.running.thread = false;
				setTimeout(function(){
					karaoke.getThread();
				}, wait_mil);
			}
		})
	}
	resizeVideo()
	{
		$('#video').css('height', $('.videoDiv').css('height'));
	}

	mountWaitList(dontRefresh = false)
	{
		if(this.running.list){
			return;
		}
		this.running.list = true;
		handleAjax({
			url: karaoke.url+'k_musics_list',
			dontFireError: true,
			data: {
				sh: (!$('#SongListsDiv2').length) ? true : 40,
				ct: this.numSongsList,
				of: 1,
			},
			callback: (res) => {
				$('#SongListsDiv').html('');
				$('#SongListsDiv2').html('');
				$('#SongListsDivCenter').html('');
				if(res.detail){
					let hasDiv2 = false;
					res.detail.s.forEach((ipt, idx) => {
						let turn = 0;
						turn = idx + 1;
						if(idx < this.numSongsList){
							if($('#SongListsDiv2').length && turn > 7){
								hasDiv2 = true;
								$('#SongListsDiv2').append('<p>'+turn+'. ' + ipt[1]+' ['+ipt[2]+']'+ ipt[3]+'</p>');
							}else{
								$('#SongListsDiv').append('<p>'+turn+'. ' + ipt[1]+' ['+ipt[2]+']'+ ipt[3]+'</p>');
							}
						}
					});
					if(this.typeScreen !== 4){
						if(hasDiv2){
							$('#SongListsDiv').removeClass('col-12').addClass('col-6');
						}else{
							$('#SongListsDiv').removeClass('col-6').addClass('col-12');
						}
					}
					
					if(res.detail.t - 1 > res.detail.s.length){
						let leftSongs = res.detail.t - res.detail.s.length - 1;
						if($('#SongListsDivCenter').length){
							$('#SongListsDivCenter').append('<p>....Mais '+leftSongs+' música(s) na fila....</p>');
							
						}else{
							$('#SongListsDiv').append('<p>....Mais '+leftSongs+' música(s) na fila....</p>');
						}
					}
				}
			},
			callbackError: (res) => {
				$('#SongListsDiv').html('');
			},
			callbackAll: (res) => {
				this.running.list = false;
				if(!dontRefresh){
					setTimeout(() => {
						karaoke.mountWaitList();
					}, 5000);
				}
			}
		});
	}
}