class translateApp
{
    constructor()
    {
        this.app_langs = {}
    }
    add(f, lang)
    {
        this.app_langs[f] = lang;
    }
    remove(f)
    {
       delete this.app_langs[f]; 
    }
    get(f, lbl)
    {
        if(!lbl){
            return '';
        }
        if(!f){
            f = 'app';
        }
        if(typeof this.app_langs[f] != 'undefined' && typeof this.app_langs[f][lbl] != 'undefined'){
            return this.app_langs[f][lbl];
        }else if(f !== 'app' && typeof this.app_langs['app'][lbl] != 'undefined'){
            return this.app_langs[f][lbl];
        }
        return lbl;
    }
}