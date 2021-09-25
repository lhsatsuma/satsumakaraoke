<div class="col-5">
    <div class="row">
        <div class="col-12 mt-2 mb-2 center karaokeLogo">
            <img src="{$app_url}images/logo.png" style="width: 50%"/>
        </div>
    </div>
    <div class="row" id="SongLists">
        <div class="col-12 center">
            <p><strong>Músicas na Fila:</strong></p>
        </div>
        <div class="col-12 h-75" id="SongListsDiv"></div>
    </div>
</div>
<div class="col-7">
    <div class="row">
        <div class="col-12 center playingNowDiv" style="padding: 30px 0px 15px 0px">
            <h4><span id="playingNow"></span></h4>
            <div id="pausedDiv" class="center" style="display: none">
                <h3>VIDEO PAUSADO!</h3>
            </div>
        </div>
        <div class="col-12 center videoDiv">
            <input type="hidden" id="songNowId" value=""/>
            <video id="video" autoplay>
                <source id="videoSrc" src="" type="video/mp4" />
            </video>
        </div>
        <div class="col-12 center volumeDiv">
            <h3>Volume: <span id="volumeSpan">100%</span></h3>
        </div>
    </div>
</div>
<div id="joinUsKaraoke" class="col-12 center">
    <h1>Cante com nós! Acesse <span class="b800">{$host_fila}</span></h1>
</div>