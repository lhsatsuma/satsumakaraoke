
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
		this.last_volume = 1;
		this.reset_line = true;
		this.typeScreen = 0;
		this.optionsSongsList = {
			num: 7,
			class: '',
		};
		$(document).keyup(function(event) {
			event.preventDefault();
			if(!karaoke.typeScreen){
				switch(event.which){
					case 49:
					case 97:
						$('#type_screen_1').click();
						break;
					case 50:
					case 98:
						$('#type_screen_2').click();
						break;
					case 51:
					case 99:
						$('#type_screen_3').click();
						break;
					case 52:
					case 100:
						$('#type_screen_4').click();
						break;
					case 53:
					case 101:
						$('#type_screen_5').click();
						break;
					default:
						break;
				}
			}
		});
	}
	/*
	* type 1 -> Complete (video, next songs, remote control)
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
				$('#bodyKaraoke').removeClass('modal-open');
				$('#bodyKaraoke').html('<div class="container-fluid">'+res.detail+'</div>');
			},
			callbackAll: (res) => {

				//If type 2, dont need list of songs in line
				if(type == 2){
					this.search_list = false;
				}
				if(type == 1 || type == 2){
					if(type == 1){
						this.hotkeysTypeOne();
					}
					this.video = document.getElementById('video');
					handleAjax({
						dontFireError: true,
						url: _APP.app_url+'Karaoke_ajax/k_get_thread_copy',
						callback: (res) => {
							if(!!res.detail && !!res.detail.volume){
								this.video.volume = res.detail.volume / 100;
								this.last_volume = res.detail.volume / 100;
								$('#volumeSpan').html(res.detail.volume + '%');
								$('#volumeRange').val(res.detail.volume);
								$('#volP').html(res.detail.volume+'%');
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
		switch(this.typeScreen){
			case 1:
				this.optionsSongsList.num = 6;
				this.optionsSongsList.class = 'col-12 col-md-6 m-0';
				this.optionsSongsList.classRow = 'row mr-3 mb-3 border';
				break;
			case 3:
				this.optionsSongsList.num = 12;
				this.optionsSongsList.class = 'col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2';
				this.optionsSongsList.classRow = 'row mr-3 mb-3 border';
				break;
			case 4:
				this.optionsSongsList.num = 8;
				this.optionsSongsList.class = 'col-12 col-md-6 col-lg-4 col-xl-3 mb-3';
				this.optionsSongsList.classRow = '';
				break;
			default:
				break;
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
		setTimeout(() => { 
			$('#InitialModal').remove();}
		, 1500);
	}
	endedVideo()
	{
		if(karaoke.url){
			karaoke.video.src = "";
			$('#playingNow').html('');
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
						$('#playingNow').html(this.songNow[1]+' | ['+this.songNow[2]+'] '+ this.songNow[3]);
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
						wait_mil = this.setVideoAction(res.detail.th.action, res.detail.th.valueTo);
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
					this.reset_line = false;
				}else{
					this.search_list = true;
					this.reset_line = true;
				}
				if(need_loop){
					setTimeout(function(){
						karaoke.getThread();
					}, wait_mil);
				}
			}
		})
	}
	setVideoAction(action, valueTo, wait_mil = 3000)
	{
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
				let volSpan = parseFloat(valueTo);
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
				return wait_mil;
				break;
		}
	}
	mountWaitList(list, total)
	{
		$('#SongListsDiv').html('');
		$('#SongListsDiv2').html('');
		$('#SongListsDivCenter').html('');
		let totalDisplay = 0;
		let validList = true;
		let hasDiv2 = false;
		if(list){
			list.forEach((ipt, idx) => {
				if(Array.isArray(ipt)){
					if(idx > 0){
						if(idx <= this.optionsSongsList.num){
							totalDisplay++;

							let htmlBox = '';
							let cancelButton = '';
							if(this.optionsSongsList.class){
								htmlBox += `<div class="${this.optionsSongsList.class}">`;
							}

							if(this.optionsSongsList.classRow){
								htmlBox += `<div class="${this.optionsSongsList.classRow}">`;
							}
							if(this.typeScreen == 4){
								cancelButton = ` <i class="fas fa-trash-alt ptr mr-3" style="font-size: 1.5rem;vertical-align:middle" onclick="karaoke.cancelSong(${ipt[5]}, '${ipt[0]}')"></i>`;
							}
							htmlBox += `<div class="col-12 border" style="background-color: #1a1a1a">
							<h1 class="center m-0">#${ipt[5]} ${cancelButton}</h1>`;
							htmlBox += `<hr />
							<h2>${ipt[1]}</h2>
							<p style="font-size: 1.2rem;min-height: 3.5rem;">${ipt[3]}</p>
							</div>`;

							
							if(this.optionsSongsList.classRow){
								htmlBox += `</div>`;
							}
							if(this.optionsSongsList.class){
								htmlBox += `</div>`;
							}

							$('#SongListsDiv').append(htmlBox);
						}
					}
				}else{
					validList = false;
				}
			});
			if(validList){				
				if(total - 1 > totalDisplay){
					let leftSongs = total - totalDisplay - 1;
					if($('#SongListsDivCenter').length){
						$('#SongListsDivCenter').append('<h2>....Mais '+leftSongs+' música(s) na fila....</h2>');
						
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
		Swal.fire({
			title: 'Insira o código',
			input: 'text',
			inputAttributes: {
				autocapitalize: 'off'
			},
			showCancelButton: true,
			confirmButtonText: 'Buscar',
			showLoaderOnConfirm: true,
			preConfirm: (codigo) => {
				return codigo;
			}
		}).then((result) => {
			if(result.isConfirmed){
				fireAjaxLoading({
					dontFireError: true,
					url: karaoke.url+'k_search_music',
					data: JSON.stringify({'code': result.value}),
					callback: (res) => {
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
									fireAjaxLoading({
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
									},
									{
										toast: true,
										position: 'top-end',
									})
								}
							});
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
				}, {
					title: 'Buscando música...'
				})
			}
		})
	}
	hotkeysTypeOne()
	{
		$(document).keyup(function(event) {
			event.preventDefault();
			let focusSearh = $('#RemoteControlType1').css('display') !== 'none';
			if(focusSearh){
				switch(event.which){
					case 103:
						karaoke.setVideoAction('play');
						break;
					case 105:
						karaoke.setVideoAction('pause');
						break;
					case 100:
						karaoke.setVideoAction('repeat');
						break;
					case 102:
						karaoke.setVideoAction('next');
						break;
					case 96:
						karaoke.setVideoAction('mute');
						break;
					case 97:
						document.getElementById("volumeRange").stepDown(1);
						karaoke.changedVolumeRange();
						karaoke.setVideoAction('volume', parseInt($('#volumeRange').val()));
						break;
					case 99:
						document.getElementById("volumeRange").stepUp(1);
						karaoke.changedVolumeRange();
						karaoke.setVideoAction('volume', parseInt($('#volumeRange').val()));
						break;
					default:
						break;
				}
			}else if(event.which == 8 || event.which == 0){
				$('.swal2-cancel').click();
			}
			if(event.which == 106){
				$('#RemoteControlType1').toggle();
				$('#SongLists').toggle();
			}
		});
		document.getElementById('volumeRange').addEventListener('input', function() {
			karaoke.changedVolumeRange();
			karaoke.setVideoAction('volume', parseInt($('#volumeRange').val()));
		});
	}
	changedVolumeRange()
	{
		$('#volP').html($('#volumeRange').val()+'%');
		handleAjax({
			url: _APP.app_url+'Karaoke_ajax/k_set_thread',
			dontFireError: true,
			data: {
				action: 'volume',
				valueTo: $('#volumeRange').val(),
				copy_only: 1,
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
	cancelSong(number, id)
	{
		Swal.fire({
			title: 'Deseja remover da fila?',
			icon: 'question',
			text: '#'+number,
			showCloseButton: true,
			showCancelButton: true,
			focusConfirm: true,
		}).then((result) => {
			if(result.isConfirmed){
				fireAjaxLoading({
					url: this.url+'k_cancel_wait_list',
					data: JSON.stringify({'id': id}),
					callback: (res) => {
						Swal.close();
						if(res.detail){
							Swal.fire({
								toast: true,
								position: 'top-end',
								title: 'Música removida da fila!',
								text: '',
								icon: 'success',
								width: '400px',
								showConfirmButton: false,
								timer: 1500,
								timerProgressBar: true
							}).then((result) => {
								karaoke.search_list = true;
								karaoke.getThread(false);
							});
						}
					},
				},
				{
					toast: true,
					position: 'top-end',
				})
			}
		});
	}
}