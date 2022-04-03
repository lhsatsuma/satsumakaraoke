<div class="d-flex d-flex-row justify-content-center">
    <img src="{$app_url}images/logo.png" class="logoKaraoke"/>
</div>
<div class="row">
    <div class="col-12 mt-4 center">
        <h1>Cante com nós! Acesse <span class="b800">{$host_fila}</span></h1>
    </div>
</div>
<div class="row">
    <div class="col-12 center">
        <hr />
    </div>
</div>
<div class="row m-0 p-0">
    <div class="col-8">
        <h2 class="center mb-3"><strong>Próximas Músicas:</strong></h2>
        <div class="col-12"><div class="row" id="SongListsDiv"></div></div>
        <div class="col-12 m-0 p-0 center" id="SongListsDivCenter"></div>
    </div>
    <div class="col-4 bg-karaoke-remote-control">
        <div class="row">
            <div class="col-6 center">
                <span class="ptr controlbtns" id="playbutton" data-val="play">
                    <i class="fas fa-play margin-5"></i>
                    <p>Play [NP-7]</p>
                </span>
            </div>
            <div class="col-6 center">
                <span class="ptr controlbtns" id="pausebutton" data-val="pause">
                    <i class="fas fa-pause margin-5"></i>
                    <p>Pausar [NP-7]</p>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-6 center">
                <span class="ptr controlbtns" id="repeatbutton" data-val="repeat">
                    <i class="fas fa-sync-alt margin-5"></i>
                    <p>Repetir [NP-4]</p>
                </span>
            </div>
            <div class="col-6 center">
                <span class="ptr" id="insertmusic" onclick="karaoke.searchCodeMusic()">
                    <i class="fas fa-list margin-5"></i>
                    <p >Inserir Música [NP-5]</p>
                </span>
            </div>
            <div class="col-6 center">
                <span class="ptr controlbtns" id="nextbutton" data-val="next">
                    <i class="fas fa-step-forward margin-5"></i>
                    <p>Próximo [NP-6]</p>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 center mb-4">
                <span class="ptr">
                    <p id="volP">100%</p>
                    <input type="range" class="form-control-range" id="volumeRange" step="5">
                    <p>[NP-1] [NP-3]
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 center">
                <span class="ptr controlbtns" id="mutebutton" data-val="mute">
                    <i class="fas fa-volume-mute margin-5"></i>
                    <p>Mutar [NP-0]</p>
                </span>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/public/ControleRemoto.js?v={$ch_ver}"></script>
<script type="text/javascript" src="{$app_url}jsManager/Admin/Karaoke/Hotkeys.js?v={$ch_ver}"></script>