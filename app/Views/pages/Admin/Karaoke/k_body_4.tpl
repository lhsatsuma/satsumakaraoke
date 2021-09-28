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
        <div class="col-12 mb-5 center">
            <h2><strong>Músicas na Fila:</strong></h2>
        </div>
        <div class="col-8 h-75" id="SongListsDiv"></div>
        <div class="col-4 h-75" id="SongListsDiv">
            <div class="row">
                <div class="col-6 center">
                    <span class="ptr controlbtns" data-val="play">
                        <i class="fas fa-play margin-5"></i>
                        <p>Play</p>
                    </span>
                </div>
                <div class="col-6 center">
                    <span class="ptr controlbtns" data-val="pause">
                        <i class="fas fa-pause margin-5"></i>
                        <p>Pausar</p>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-6 center">
                    <span class="ptr controlbtns" data-val="next">
                        <i class="fas fa-step-forward margin-5"></i>
                        <p>Próximo</p>
                    </span>
                </div>
                <div class="col-6 center">
                    <span class="ptr controlbtns" data-val="repeat">
                        <i class="fas fa-sync-alt margin-5"></i>
                        <p>Repetir</p>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 center mb-4">
                    <span class="ptr">
                        <p id="volP">100%</p>
                        <input type="range" class="form-control-range" id="volumeRange" step="5">
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 center">
                    <span class="ptr controlbtns" data-val="mute">
                        <i class="fas fa-volume-mute margin-5"></i>
                        <p>Mutar</p>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/ControleRemoto.js?v={$ch_ver}"></script>