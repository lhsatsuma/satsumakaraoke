$('select[name="tipo"]').on('change', () => {
    let tipo = $('select[name="tipo"]').val();
    rmvRequired('input[name="menu_pai_name"]');
    if(tipo == '2' || tipo == '4'){
        addRequired('input[name="menu_pai_name"]');
    }
}).trigger('change');
var lnMenuLanguages = 0;
function addNewMenuLanguage(id='',language='',name='')
{
    let options = translate.getOptionsHTML('languages',language);
    let html = `<tr>
        <td>
            <select class="form-control" name="menu_languages[${lnMenuLanguages}][language]"  onchange="checkMenuLanguage(this)" required>
            ${options}
            </select>
        </td>
        <td>
            <input type="text" class="form-control" name="menu_languages[${lnMenuLanguages}][name]" value="${name}" required/>
            <input type="hidden" name="menu_languages[${lnMenuLanguages}][deleted]" value="0" />
            <input type="hidden" name="menu_languages[${lnMenuLanguages}][id]" value="${id}" />
        </td>
        <td>
            <button type="button" class="btn btn-outline-danger" onclick="rmvMenuLanguage(this, ${lnMenuLanguages})"><i class="fas fa-times"></i></button>
        </td>
    </tr>`;

    $('#menu_languages_tbody').append(html);
}


function checkMenuLanguage(elm)
{
    let value = $(elm).val();
    
    if(value){
        let count = 0;
        $('select[name^="menu_languages"]').each((idx, ipt) => {
            if($(ipt).val() == value){
                count++;
            }
        });
        if(count > 1){
            fireInfo(translate.get('LBL_ALREADY_HAVE_LANGUAGE'));
            $(elm).val('');
        }
    }

    return true;
}

function rmvMenuLanguage(elm, ln)
{
    $('input[name="menu_languages['+ln+'][deleted]"]').val('1');
    $(elm).parent().parent().hide();
}