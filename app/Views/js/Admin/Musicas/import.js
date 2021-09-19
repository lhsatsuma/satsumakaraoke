var links = [];
var linksDone = 0;
$('.searchLinks').click(() => {

    if(!$('#links').val().trim()){
        return;
    }
    links = $('#links').val().trim().split("\n");
    linksDone = 0;
    $('#links').val('');
    fireLoading({
        html: `<p>Isto pode demorar um tempo...</p><p>Status <span id="countLinks">0</span>/${links.length} músicas buscadas.</p>`,
        didOpen: () => {
            Swal.showLoading();
            ajaxNextSearch();
        }
    });
});

function ajaxNextSearch()
{
    if(linksDone < links.length){
        handleAjax({
            url: _app_vars.app_url+'musicas/CheckImportVideo',
            data: JSON.stringify({ 
                link: links[linksDone],
                len_link: linksDone,
            }),
            beforeSend: () => {
                $('#searchedLinks').append(`<tr class="d-flex importLink_Row">
                    <td class="col-2 col-xl-2"><input type="text" class="form-control importLink_Link importLink_Link${linksDone}" value="${links[linksDone]}" readonly ln="${linksDone}"/><input type="hidden" class="form-control importLink_MD5${linksDone}" value="" /></td>
                    <td class="col-2 col-xl-1"><select class="form-control importLink_Tipo${linksDone}"><option value="N/A">N/A</option><option value="INT">INT</option><option value="BRL">BRL</option><option value="ESP">ESP</option><option value="JPN">JPN</option><option value="OTR">OTR</option></select></td>
                    <td class="col-6 col-xl-6"><input type="text" class="form-control importLink_Name${linksDone}" /></td>
                    <td class="col-2 col-xl-2"><button type="button" class="btn btn-outline-info btn-rounded importLink_InvertLine${linksDone}" onclick="inverterLine(${linksDone})">Inverter Nome/Cantor</button></td>
                    <td class="col-2 col-xl-1 importLink_Status${linksDone}"></td>
                </tr>`);
            },
            callback: (res) => {
                if(res.detail.title){
                    $('#countLinks').html(parseInt($('#countLinks').html()) + 1);
                    $('.importLink_Name'+res.detail.len_link).val(fixNameUtf8(res.detail.title));
                    $('.importLink_MD5'+res.detail.len_link).val(res.detail.md5);
                    linksDone++;
                    if(linksDone == links.length){
                        setTimeout(() => {
                            Swal.close();
                            linksDone = 0;
                        }, 1000);
                    }else{
                        ajaxNextSearch();
                    }
                }
            },
            callbackError: (res) => {
                console.log('Error searching Ajax');
                linksDone++;
                if(linksDone == links.length){
                    setTimeout(() => {
                        Swal.close();
                        linksDone = 0;
                    }, 1000);
                }else{
                    ajaxNextSearch();
                }
            }
        });
    }
}


function inverterLine(ln)
{
    $('.importLink_Name'+ln).val(changeByTraco2($('.importLink_Name'+ln).val()));
}

var totalImported = 0;
function finallyImport()
{
    if(!$('.importLink_Link').length){
        return;
    }
    let linksDone = 0;
    fireLoading({
        html: `<p>Isto <strong>VAI</strong> demorar um tempo...</p>
        <p><span id="countLinksTotal">0</span>/`+$('.importLink_Link').length+` música(s) finalizado(s).</p>
        <p><span id="countLinksSuccess">0</span> música(s) com sucesso.</p>
        <p><span id="countLinksError">0</span> música(s) com erro.</p>`,
        didOpen: () => {
            Swal.showLoading();
            ajaxNextImport();
        }
    });
}

function ajaxNextImport()
{
    if(totalImported < $('.importLink_Link').length){
        handleAjax({
            url: _app_vars.app_url+'musicas/ImportVideoUrl',
            data: JSON.stringify({ 
                'link': $('.importLink_Link'+totalImported).val(),
                'md5': $('.importLink_MD5'+totalImported).val(),
                'title': $('.importLink_Name'+totalImported).val(),
                'tipo': $('.importLink_Tipo'+totalImported).val(),
                'auto_fila': false,
                'len_link': totalImported,
            }),
            beforeSend: () => {
                $('.importLink_Status'+totalImported).html('<img class="loading-icon" src="'+_app_vars.app_url+'images/loading.gif" />');
                showLoadingIcon($('.importLink_Status'+totalImported));
                console.log($('.importLink_Link'+totalImported).val(), 'IMPORTING');
            },
            success: function(res){
                $('#countLinksTotal').html(parseInt($('#countLinksTotal').html()) + 1);
                if(!!res.status && res.detail.downloaded){
                    $('#countLinksSuccess').html(parseInt($('#countLinksSuccess').html()) + 1);
                    console.log($('.importLink_Link'+totalImported).val(), 'OK');
                    $('.importLink_Status'+res.detail.len_link).html('<i class="fas fa-check"></i>');
                }else{
                    console.log('ERROR', '.importLink_Status'+res.detail.len_link);
                    $('#countLinksError').html(parseInt($('#countLinksError').html()) + 1);
                    $('.importLink_Status'+res.detail.len_link).html('<i class="fas fa-times"></i>');
                }                        
                totalImported++;
                if(totalImported == $('.importLink_Link').length){
                    setTimeout(() => {
                        Swal.close();
                        totalImported = 0;
                    }, 1000);
                }else{
                    ajaxNextImport();
                }
            },
            error: function(d){
                $('#countLinksTotal').html(parseInt($('#countLinksTotal').html()) + 1);
                r = d.responseJSON;
                console.log($('.importLink_Link'+totalImported).val(), 'ERROR');
                $('#countLinksError').html(parseInt($('#countLinksError').html()) + 1);


                if(!!r.detal){
                    $('.importLink_Status'+r.detail.len_link).html('<i class="fas fa-times"></i>');
                }

                totalImported++;
                if(totalImported == $('.importLink_Link').length){
                    setTimeout(() => {
                        Swal.close();
                        totalImported = 0;
                    }, 1000);
                }else{
                    ajaxNextImport();
                }
            }
        });
    }
}