getLastVolume();
$(document).keyup(function(event) {
    event.preventDefault();
    let focusSearh = document.querySelector('#music_code') === document.activeElement;
    if(!focusSearh){
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
    }else if(event.which === 13){
        karaoke.searchCodeMusic();
    }
});