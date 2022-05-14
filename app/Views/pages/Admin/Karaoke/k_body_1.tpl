<div class="d-flex">
    <div class="col-6 center">
        <img src="{$app_url}images/logo.png" style="width: 35%;padding-top: 1rem;"/>
    </div>
    <div class="col-6 p-2 align-self-center">
        <h1 id="playingNow" style="display: contents;"></h1>
    </div>
</div>
<div class="row">
    <div class="col-12 center">
        <hr style="margin-bottom: 0.5rem;" />
    </div>
</div>
<div class="row m-0 p-0">
    <div class="col-5 m-0 p-0" id="SongLists">
        <h2 class="center mb-2"><strong>Próximas Músicas:</strong></h2>
        <div class="col-12"><div class="row" id="SongListsDiv"></div></div>
        <div class="col-12 m-0 p-0 mt-2 center" id="SongListsDivCenter"></div>
    </div>
    <div class="col-5 bg-karaoke-remote-control" id="RemoteControlType1" style="display: none">
        <div class="row">
            <div class="col-12 center">
                <h3>Controle Remoto (Aperte [NP-*] para voltar a lista):</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-6 center">
                
            </div>
            <div class="col-6 center">
            </div>
        </div>
        <div class="row">
            <div class="col-6 center">
            </div>
            <div class="col-6 center">
            </div>
        </div>
        <div class="row">
            <div class="col-12 center mb-4">
                <span class="ptr">
                    <p  id="volP">100%</p>
                    <input type="range" class="form-control-range" id="volumeRange" step="5">
                    <p>[NP-1] [NP-3]</p>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 center">
            </div>
        </div>
    </div>
    <div class="col-7">
        <div class="row">
            <div class="col-12 center videoDiv">
                <input type="hidden" id="songNowId" value=""/>
                <video id="video" autoplay>
                    <source id="videoSrc" src="" type="video/mp4" />
                </video>
            </div>
            <div class="col-12 center volumeDiv">
                <h3>Volume: <span id="volumeSpan">100%</span> <span id="pausedDiv" style="display: none">VIDEO PAUSADO!</span></h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-5 p-0 center" style="font-size: 1.5rem;margin:0;margin-top:-1.5rem">
        <p class="m-0">
            <span class="ptr" onclick="karaoke.setVideoAction('play');">
                <i  class="fas fa-play margin-5"></i> Play [NP-7]
            </span>
             |
            <span class="ptr" onclick="karaoke.setVideoAction('pause');">
                <i  class="fas fa-pause margin-5"></i> Pausar [NP-9]
            </span>
             |
            <span class="ptr" onclick="karaoke.setVideoAction('repeat');">
                <i  class="fas fa-sync-alt margin-5"></i> Repetir [NP-4]
            </span>
             |
            <span class="ptr" onclick="karaoke.setVideoAction('next');">
                <i  class="fas fa-step-forward margin-5"></i> Próximo [NP-6]
            </span>
        </p>
        <p>
            <span class="ptr noselect" onclick="karaoke.actionHotkey(97);">Vol. - [NP-1]</span> | <span class="ptr noselect" onclick="karaoke.actionHotkey(99);">Vol. + [NP-3]</span> | <span class="ptr noselect" onclick="karaoke.setVideoAction('mute');">Mutar [NP-0]</span>
        </p>
    </div>
    <div class="col-7 center">
        <h1>Cante com nós! Acesse <span class="b800">{$host_fila}</span></h1>
    </div>
</div>