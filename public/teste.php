<script type="text/javascript" src="Tone.js"></script>
<script>
var SOUNDS = {};
window.AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext = new AudioContext();
function loadSound(name,success,err) {
    var request = new XMLHttpRequest();
    request.open('GET', 'uploads/VIDEOSKARAOKE/'+name+'.mp3')
    request.responseType = 'arraybuffer'
    request.onload = function() {
        audioContext.decodeAudioData(request.response, function(buffer) {
            SOUNDS[name] = buffer;
            (success || (function(){}))()
        }, err || function(msg) {console.error(msg)});
     }
     request.send();
 }
 function playSound(name,param) {
     param = param || {}
     var s = SOUNDS[name]
     var source = audioContext.createBufferSource()
     source.buffer = s
     if (param.loop) {
         source.loop = true
     }
     source.connect(audioContext.destination);
     source.detune.value = -200// value of pitch
     source.start(0);
 }

 loadSound("TESTEAUDIO",function() {
     //Onload
     playSound('TESTEAUDIO')
 })
</script>