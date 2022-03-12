
var musicsLine = [];
var keyListSong = null;
$(document).ready(function(){
	$('#InitialModal').modal('show');
});

class KaraokeJS{
	constructor()
	{
		this.url = _APP.app_url+'admin/Karaoke_ajax/';
		this.running = {
			'nextVideo': false,
			'thread': false,
		};
		this.search_list = true;
		this.shortName = true;
		this.numSongsList = 7;
		this.last_volume = 1;
		this.reset_line = true;
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
			url: _APP.app_url+'admin/karaoke_ajax/k_get_body',
			data: {
				id: type,
			},
			callback: (res) => {
				$('#bdKaraoke').html(res.detail);
			},
			callbackAll: (res) => {

				//If type 2, dont need list of songs in line
				if(type == 2){
					this.search_list = false;
				}
				if(type == 1 || type == 2){
					this.video = document.getElementById('video');
					handleAjax({
						dontFireError: true,
						url: _APP.app_url+'Karaoke_ajax/k_get_thread_copy',
						callback: (res) => {
							if(!!res.detail && !!res.detail.volume){
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
		}else if(this.typeScreen !== 5){
			this.getThread();
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
					karaoke.getNextVideo(true);
				},
			});
		}
	}
	getNextVideo(call_wait_list = false)
	{
		if(this.running.nextVideo){
			return;
		}
		this.running.nextVideo = true;
		handleAjax({
			url: karaoke.url+'k_get_thread',
			data: {
				sh: 0,
				ct: (!call_wait_list) ? 1 : 0,
				of: 0,
				search: true,
			},
			callback: (res) => {
				if(res.detail){
					if(res.detail.s.length && res.detail.s[0][4]){
						this.songNow = res.detail.s[0];
						$('#songNowId').val(this.songNow[0]);
						video.src = _APP.karaokeURL + this.songNow[4];
						$('#playingNow').html('<p>'+this.songNow[1]+' | ['+this.songNow[2]+'] '+ this.songNow[3]+'</p>');
						$('#pausedDiv').hide();
						if(call_wait_list){
							this.search_list = true;
							karaoke.getThread(false);
						}
					}else{
						setTimeout(() => {
							karaoke.getNextVideo();
						}, 5000);
					}
				}else{
					setTimeout(() => {
						karaoke.getNextVideo();
					}, 5000);
				}
			},
			callbackAll: (res) => {
				this.running.nextVideo = false;
			}
		});
	}
	getThread(need_loop = true)
	{
		if(this.running.thread){
			return;
		}

		this.running.thread = true;
		var wait_mil = 3000;
		handleAjax({
			url: this.url+'k_get_thread',
			dontFireError: true, 
			data: {
				search: this.search_list,
				reset: this.reset_line,
			},
			callback: (res) => {
				if(res.detail){
					if(res.detail.th && (this.typeScreen == 1 || this.typeScreen == 2)){
						let action = res.detail.th.action;
						switch(action){
							case 'play':
								this.video.play();
								break;
							case 'pause':
								this.video.pause();
								break;
							case 'next':
								let sec_left = video.duration - video.currentTime;
								if(sec_left > 5){
									karaoke.endedVideo();
								}
								break;
							case 'repeat':
								this.video.currentTime = 0;
								break;
							case 'volume':
								let volSpan = parseFloat(res.detail.th.valueTo);
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
					}
					if(this.search_list){
						this.mountWaitList(res.detail.s, res.detail.t);
					}
					if(res.detail.th && (this.typeScreen == 1 || this.typeScreen == 2)){
						handleAjax({
							url: _APP.app_url+'admin/Karaoke_ajax/k_reset_thread',
							callback: (res) => {
								karaoke.running.thread = false;
							}
						});
					}
				}
			},
			callbackError: (res) => {
				$('#SongListsDiv').html('');
			},
			callbackAll: (res) => {
				this.reset_line = false;
				karaoke.running.thread = false;
				if(this.search_list && (this.typeScreen == 1 || this.typeScreen == 2)){
					this.search_list = false;
				}else{
					this.search_list = true;
				}
				if(need_loop){
					setTimeout(function(){
						karaoke.getThread();
					}, wait_mil);
				}
			}
		})
	}
	mountWaitList(list, total)
	{
		$('#SongListsDiv').html('');
		$('#SongListsDiv2').html('');
		$('#SongListsDivCenter').html('');
		let hasDiv2 = false;
		let totalDisplay = 0;
		let validList = true;
		if(list){
			list.forEach((ipt, idx) => {
				if(Array.isArray(ipt)){
					if(idx > 0){
						let turn = 0;
						turn = idx;
						if(idx < this.numSongsList){
							totalDisplay++;
							if($('#SongListsDiv2').length && turn > 7){
								hasDiv2 = true;
								$('#SongListsDiv2').append('<p>'+turn+'. ' + ipt[1]+' ['+ipt[2]+']'+ ipt[3]+'</p>');
							}else{
								$('#SongListsDiv').append('<p>'+turn+'. ' + ipt[1]+' ['+ipt[2]+']'+ ipt[3]+'</p>');
							}
						}
					}
				}else{
					validList = false;
				}
			});
			if(validList){
				if(this.typeScreen !== 4){
					if(hasDiv2){
						$('#SongListsDiv').removeClass('col-12').addClass('col-6');
					}else{
						$('#SongListsDiv').removeClass('col-6').addClass('col-12');
					}
				}
				
				if(total - 1 > totalDisplay){
					let leftSongs = total - totalDisplay - 1;
					if($('#SongListsDivCenter').length){
						$('#SongListsDivCenter').append('<p>....Mais '+leftSongs+' música(s) na fila....</p>');
						
					}else{
						$('#SongListsDiv').append('<p>....Mais '+leftSongs+' música(s) na fila....</p>');
					}
				}
			}
		}
	}
	resizeVideo()
	{
		$('#video').css('height', $('.videoDiv').css('height'));
	}
	searchCodeMusic()
	{
		let code_input = $('#music_code').val().replace(/\D/g, "");
		fireLoading({
			title: 'Buscando música...',
			didOpen: () => {
				$('#music_code').val('');
				Swal.showLoading();
				handleAjax({
					dontFireError: true,
					url: karaoke.url+'k_search_music',
					data: JSON.stringify({'code': code_input}),
					callback: (res) => {
						Swal.close();
						if(res.detail){
							Swal.fire({
								title: 'Deseja inserir na fila?',
								icon: 'question',
								text: '['+res.detail.codigo+'] '+res.detail.name,
								showCloseButton: true,
								showCancelButton: true,
								focusConfirm: true,
							}).then((result) => {
								if(result.isConfirmed){
									fireLoading({
										toast: true,
										position: 'top-end',
										didOpen: () => {
											Swal.showLoading();
											handleAjax({
												url: _APP.app_url+'musicas/insert_fila_ajax',
												data: JSON.stringify({'id': res.detail.id}),
												callback: (res) => {
													Swal.close();
													if(res.detail){
														Swal.fire({
															toast: true,
															position: 'top-end',
															title: 'Música adicionada na fila!',
															text: '',
															icon: 'success',
															width: '400px',
															showConfirmButton: false,
															timer: 2000,
															timerProgressBar: true
														});
													}
												},
											});
										}
									})
								}
							})
						}
					},
					callbackError: (res) => {
						if(res.error_msg){
							fireAndClose({
								title: res.error_msg,
								html: '',
								icon: 'warning',
								allowOutsideClick: false,
							});
						}else{
							fireErrorGeneric();
						}
					}
				})
			}
		})
	}
}