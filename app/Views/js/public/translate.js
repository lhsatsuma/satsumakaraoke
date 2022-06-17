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
        }else if(typeof this.app_langs['app'][lbl] != 'undefined'){
            return this.app_langs[f][lbl];
        }
        return lbl;
    }
}