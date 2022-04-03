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
        <hr />
    </div>
</div>
<div class="row m-0 p-0">
    <div class="col-5" id="SongLists">
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
                <span class="ptr" onclick="karaoke.setVideoAction('play');">
                    <i  class="fas fa-play margin-5"></i>
                    <p>Play [NP-7]</p>
                </span>
            </div>
            <div class="col-6 center">
                <span class="ptr" onclick="karaoke.setVideoAction('pause');">
                    <i  class="fas fa-pause margin-5"></i>
                    <p>Pausar [NP-9]</p>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-6 center">
                <span class="ptr" onclick="karaoke.setVideoAction('next');">
                    <i  class="fas fa-step-forward margin-5"></i>
                    <p>Próximo [NP-4]</p>
                </span>
            </div>
            <div class="col-6 center">
                <span class="ptr" onclick="karaoke.setVideoAction('repeat');">
                    <i  class="fas fa-sync-alt margin-5"></i>
                    <p>Repetir [NP-6]</p>
                </span>
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
                <span class="ptr" onclick="karaoke.setVideoAction('mute');">
                    <i  class="fas fa-volume-mute margin-5"></i>
                    <p>Mutar [NP-0]</p>
                </span>
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
    <div class="col-12 mt-2 center">
        <h1>Cante com nós! Acesse <span class="b800">{$host_fila}</span></h1>
    </div>
</div>