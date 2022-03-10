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
        <div class="col-4">&nbsp;</div>
        <div class="col-4 h-75" style="border: 1px solid #ccc;background-color: #4c4c4ccc;padding: 3.5em;">
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
        <div class="col-4">&nbsp;</div>
    </div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/public/ControleRemoto.js?v={$ch_ver}"></script>
{literal}
<script type="text/javascript">
getLastVolume();
$(document).keyup(function(event) {
    switch(event.which){
        case 103:
            $('#playbutton').click();
            break;
        case 105:
            $('#pausebutton').click();
            break;
        case 100:
            $('#nextbutton').click();
            break;
        case 102:
            $('#repeatbutton').click();
            break;
        case 96:
            $('#mutebutton').click();
            break;
        case 97:
            document.getElementById("volumeRange").stepDown(1);
            changedVolumeRange();
            break;
        case 99:
            document.getElementById("volumeRange").stepUp(1);
            changedVolumeRange();
            break;
        default:
            break;
    }
});
{/literal}
</script>