const query = (el)=> document.querySelector(el);
const all = (el)=> document.querySelectorAll(el);

//evento de mostrar e ocutar barra de menu;
const menu = query('aside.dash_sidebar');
const mobileM = query('.mobile_menu');
mobileM.addEventListener('click', (e) => {
    e.preventDefault();
    menu.classList.toggle('show'); 
});

//Mascaras de input
const masks = {
    //Mascar de data para input text;
    dateMask(value){
        //console.log(value);
        return value.replace(/\D/g, '')
        .replace(/(\d{2})(\d)/, '$1/$2')
        .replace(/(\d{2})(\d{1,4})/, '$1/$2')
        .replace(/(\/\d{4})\d+?$/, '$1');        
    },
    nunberLengthMask(value){
        return value.replace(/\D/g, '')
        .replace(/(\d{1})\d+?$/, '$1');
    },
}
document.querySelectorAll('input').forEach(($input) =>{
    const field = $input.dataset.action;
    if (field) {
        $input.addEventListener('input', (e) =>{
            e.target.value = masks[field](e.target.value);
        }, false); 
    }    
});
//Mascaras de input fim

//validator FORM
let ValidatorJs = {
    handleSubmit:(e) =>{
        e.preventDefault();
        let send = true;
        let inputs = form.querySelectorAll('input');
        ValidatorJs.clearErrors();
        for (let i = 0; i < inputs.length; i++) {
            let input = inputs[i];
            let check = ValidatorJs.checkInput(input);
            if (check !== true) {
                send = false;
                ValidatorJs.showError(input, check);
            }
        }
        
        if (send) {
            form.submit();
        }
    },
    checkInput:(input) => {
        let rules = input.getAttribute('data-rules');
        if (rules !== null) {
            rules = rules.split('|');
            for(let k in rules){
                let rDetails = rules[k].split('=');
                switch (rDetails[0]) {
                    case 'required':
                        if (input.value == '') {
                            return 'Campo não pode ser vazio.'
                        }
                        
                        break;
                        case 'min':
                            if (input.value.length < rDetails[1]) {
                                return 'Campo deve ter no minimo '+rDetails[1]+' caracteres.';
                            }
                            
                            break;
                            case 'email':
                        if(input.value != '') {
                            let regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                            if(!regex.test(input.value.toLowerCase())) {
                                return 'E-mail digitado não é válido!';
                            }
                        }
                    break;
                    default:
                        break;
                }
            }
        }
        
        return true;
    },
    showError:(input, error)=>{
        input.style.borderColor = '#D94352';
        let errorElement = document.createElement('div');
        errorElement.classList.add('dverror');
        errorElement.innerHTML = error;
        input.parentElement.insertBefore(errorElement, input.ElementSibling);
    },
    clearErrors:() =>{
        let inputs = form.querySelectorAll('input');
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].style.borderColor = '#61DDBC';
        }
        let errorElements = document.querySelectorAll('.dverror');
        for (let i = 0; i < errorElements.length; i++) {
            errorElements[i].remove();
            
        }
    },
};
let form = document.querySelector('.validatorjs');
if (form) {
    form.addEventListener('submit', ValidatorJs.handleSubmit);
}

