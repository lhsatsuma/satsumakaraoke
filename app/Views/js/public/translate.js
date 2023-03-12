class translateApp
{
    constructor(defaultF)
    {
        this.app_langs = {}
        this.defaultF = defaultF;
    }
    add(f, lang)
    {
        this.app_langs[f] = lang;
    }
    remove(f)
    {
       delete this.app_langs[f]; 
    }
    get(lbl, f)
    {
        if(!lbl){
            return '';
        }
        if(!f){
            f = this.defaultF;
        }
        
        if(typeof this.app_langs[f] != 'undefined' && typeof this.app_langs[f][lbl] != 'undefined'){
            return this.app_langs[f][lbl];
        }else if(typeof this.app_langs['Public.App'][lbl] != 'undefined'){
            return this.app_langs['Public.App'][lbl];
        }
        return lbl;
    }
    getOptionsHTML(lbl,selected=null)
    {
        if(!this.app_langs['Public.Dropdown'][lbl] && !this.app_langs['Public.Dropdown_ext'][lbl]){
            return '';
        }
        let htmlOpt = '<option value="">'+this.get('LBL_SELECT_OPTION')+'</option>';
        let options = this.app_langs['Public.Dropdown_ext'][lbl] ?this.app_langs['Public.Dropdown_ext'][lbl] : this.app_langs['Public.Dropdown'][lbl];

        $.each(options, (idx, ipt) => {
            let selected_opt = (!!selected && selected===idx) ? 'selected' : '';
            htmlOpt += '<option value="'+idx+'" '+selected_opt+'>'+ipt+'</option>';
        });

        return htmlOpt;
    }
}