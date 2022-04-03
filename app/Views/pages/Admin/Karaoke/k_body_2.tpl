<div class="d-flex">
    <div class="col-6 center">
        <img src="{$app_url}images/logo.png" style="width: 50%;padding-top: 1rem;"/>
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
    <div class="col-12">
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