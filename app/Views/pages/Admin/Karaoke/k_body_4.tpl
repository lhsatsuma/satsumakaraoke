<div class="col-12">
    <div class="row">
        <div class="col-4 mt-2 mb-3 center karaokeLogo">
            <img src="{$app_url}images/logo.png" style="width: 65%"/>
        </div>
        <div class="col-8 mt-4 mb-5 left">
            <h1>Cante com nós! Acesse <span class="b800">{$host_fila}</span></h1>
        </div>
    </div>
    <div class="row" id="SongLists">
        <div class="col-8 mb-5 center">
            <h2><strong>Músicas na Fila:</strong></h2>
        </div>
        <div class="col-8 h-75" id="SongListsDiv"></div>
        <div class="col-4 h-75">
            <div class="row">
                <div class="col-8">
                    <p class="bold">Digite o código da música para inserir na fila como Admin:</p>
                    <p><input class="form form-control" type="text" id="music_code" style="width: 60%;display:inline-block"/> <button type="button" class="btn btn-success" onclick="karaoke.searchCodeMusic()">Buscar</button></p>
                </div>
            </div>
            <div class="row">
                <div class="col-12" style="border: 1px solid #ccc;background-color: #4c4c4ccc;padding: 1.7em;">
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
                            <span class="ptr controlbtns" id="nextbutton" data-val="next">
                                <i class="fas fa-step-forward margin-5"></i>
                                <p>Próximo [NP-4]</p>
                            </span>
                        </div>
                        <div class="col-6 center">
                            <span class="ptr controlbtns" id="repeatbutton" data-val="repeat">
                                <i class="fas fa-sync-alt margin-5"></i>
                                <p>Repetir [NP-6]</p>
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
        </div>
    </div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/public/ControleRemoto.js?v={$ch_ver}"></script>
<script type="text/javascript" src="{$app_url}jsManager/Admin/Karaoke/Hotkeys.js?v={$ch_ver}"></script>