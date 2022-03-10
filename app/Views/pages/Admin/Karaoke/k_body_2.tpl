<div class="col-12 mb-0 mt-0">
    <div class="row mb-0 mt-0">
        <div class="col-4 mt-2 center karaokeLogo" style="height: 15vh" >
            <img src="{$app_url}images/logo.png" style="width: 65%" />
        </div>
        <div class="col-8 mt-4 left">
            <h4><span id="playingNow"></span></h4>
        </div>
    </div>
    <div class="row mb-0 mt-0">
        <div class="col-12 mb-0 mt-0 center videoDiv" style="height: 74vh;">
            <input type="hidden" id="songNowId" value=""/>
            <video id="video" autoplay>
                <source id="videoSrc" src="" type="video/mp4" />
            </video>
        </div>
        <div class="col-12 mb-0 mt-0 center volumeDiv" style="height: 10vh;">
            <h3 class="mb-0 mt-0">Volume: <span id="volumeSpan">100%</span> <span id="pausedDiv" style="display: none">VIDEO PAUSADO!</span></h3>
        </div>
    </div>
</div>