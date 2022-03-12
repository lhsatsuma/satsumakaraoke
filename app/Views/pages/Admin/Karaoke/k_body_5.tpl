<div class="col-12">
    <div class="row">
        <div class="col-4 mt-2 mb-3 center karaokeLogo">
            <img src="{$app_url}images/logo.png" style="width: 65%"/>
        </div>
        <div class="col-8 mt-4 mb-5 left">
            <h1>Cante com nós! Acesse <span class="b800">{$host_fila}</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-2">&nbsp;</div>
        <div class="col-4 h-75" style="border: 1px solid #ccc;background-color: #4c4c4ccc;padding: 3.5em;">
            <div class="row">
                <div class="col-6 center">
                    <span class="ptr controlbtns" id="playbutton" data-val="play">
                        <i style="font-size: 1.5rem" class="fas fa-play margin-5"></i>
                        <p style="font-size: 1.5rem">Play [NP-7]</p>
                    </span>
                </div>
                <div class="col-6 center">
                    <span class="ptr controlbtns" id="pausebutton" data-val="pause">
                        <i style="font-size: 1.5rem" class="fas fa-pause margin-5"></i>
                        <p style="font-size: 1.5rem">Pausar [NP-9]</p>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-6 center">
                    <span class="ptr controlbtns" id="nextbutton" data-val="next">
                        <i style="font-size: 1.5rem" class="fas fa-step-forward margin-5"></i>
                        <p style="font-size: 1.5rem">Próximo [NP-4]</p>
                    </span>
                </div>
                <div class="col-6 center">
                    <span class="ptr" id="insertmusic" onclick="karaoke.searchCodeMusic()">
                        <i style="font-size: 1.5rem" class="fas fa-list margin-5"></i>
                        <p style="font-size: 1.5rem">Inserir Música [NP-5]</p>
                    </span>
                </div>
                <div class="col-6 center">
                    <span class="ptr controlbtns" id="repeatbutton" data-val="repeat">
                        <i style="font-size: 1.5rem" class="fas fa-sync-alt margin-5"></i>
                        <p style="font-size: 1.5rem">Repetir [NP-6]</p>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 center mb-4">
                    <span class="ptr">
                        <p style="font-size: 1.5rem" id="volP">100%</p>
                        <input type="range" class="form-control-range" id="volumeRange" step="5">
                        <p style="font-size: 1.5rem">[NP-1] [NP-3]
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 center">
                    <span class="ptr controlbtns" id="mutebutton" data-val="mute">
                        <i style="font-size: 1.5rem" class="fas fa-volume-mute margin-5"></i>
                        <p style="font-size: 1.5rem">Mutar [NP-0]</p>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/public/ControleRemoto.js?v={$ch_ver}"></script>
<script type="text/javascript" src="{$app_url}jsManager/Admin/Karaoke/Hotkeys.js?v={$ch_ver}"></script>