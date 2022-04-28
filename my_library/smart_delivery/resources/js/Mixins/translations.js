export const translations = {
    methods:{
        __(key,replacements = {}){
            let transaltion = window._translations[key] || key;
            return transaltion;
        }
    }
}
