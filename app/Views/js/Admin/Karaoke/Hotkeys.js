getLastVolume();
$(document).keyup(function(event) {
    event.preventDefault();
    let focusSearh = $('.swal2-container').length;
    if(!focusSearh){
        switch(event.which){
            case 103:
                $('#playbutton').click();
                break;
            case 105:
                $('#pausebutton').click();
                break;
            case 100:
                $('#repeatbutton').click();
                break;
            case 101:
                karaoke.searchCodeMusic();
                break;
            case 102:
                $('#nextbutton').click();
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
    }else if(event.which == 8 || event.which == 0){
        $('.swal2-cancel').click();
    }
});