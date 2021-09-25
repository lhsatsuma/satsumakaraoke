<div class="col-12">
    <div class="row">
        <div class="col-4 mt-2 center karaokeLogo" style="height: 10vh" >
            <img src="{$app_url}images/logo.png" style="width: 65%" />
        </div>
        <div class="col-8 mt-4 left">
            <h1>Cante com nós! Acesse <span class="b800">{$host_fila}</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12 center playingNowDiv" style="height: 10vh">
            <h4><span id="playingNow"></span></h4>
            <div id="pausedDiv" class="center" style="display: none">
                <h3>VIDEO PAUSADO!</h3>
            </div>
        </div>
        <div class="col-12 center videoDiv" style="height: 69vh;">
            <input type="hidden" id="songNowId" value=""/>
            <video id="video" autoplay>
                <source id="videoSrc" src="" type="video/mp4" />
            </video>
        </div>
        <div class="col-12 center volumeDiv" style="height: 10vh;">
            <h3>Volume: <span id="volumeSpan">100%</span></h3>
        </div>
    </div>
</div>