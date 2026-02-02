document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#form-login');
    if (!form) return;

    const userInput = document.getElementById('username');
    const pwdInput = document.getElementById('password');

    const errorUser = document.getElementById('error-username');
    const errorPassword = document.getElementById('error-password');

    function validaStringaConNumeri(input, nomeCampo, ultimaLettera) {
        const valore = input.value.trim();
        
        if (valore === '') {
            return `${nomeCampo} è obbligatori${ultimaLettera}`;
        } else if (valore.length > 25) {
            return ` ${nomeCampo} non può superare i 25 caratteri`;
        } else if (!/^[a-zA-ZÀ-ÿ0-9\s'-]+$/.test(valore)) {
            return `${nomeCampo} contiene caratteri non validi`;
        }
        
        return null;  // Nessun errore
    }

    // ========================================
    // MOSTRA/NASCONDI ERRORI CON ARIA
    // ========================================
    
    function mostraErroreCampo(input, errorDiv, messaggio) {
        if (messaggio) {
            input.classList.add('error');
            input.setAttribute('aria-invalid', 'true');
        
            const span = errorDiv.querySelector('.error-text');
            span.textContent = messaggio;
            errorDiv.classList.remove('hidden');
        } else {
            rimuoviErroreCampo(input, errorDiv);
        }
    }

    function rimuoviErroreCampo(input, errorDiv) {
        input.classList.remove('error');
        input.removeAttribute('aria-invalid');
    
        const span = errorDiv.querySelector('.error-text');
        span.textContent = '';
    
        errorDiv.classList.add('hidden');
    }

    // ========================================
    // VALIDAZIONE AL SUBMIT
    // ========================================
    
    form.addEventListener('submit', function(e) {
        let haErrori = false;
        let primoErrore = null;
        
        // Valida username
        const errUser = validaNome(userInput);
        mostraErroreCampo(userInput, errorUser, errUser);
        if (errUser) {
            haErrori = true;
            if (!primoErrore) primoErrore = userInput;
        }
        
        // Valida password
        const errPassword = validaNome(pwdInput);
        mostraErroreCampo(pwdInput, errorPassword, errPassword);
        if (errPassword) {
            haErrori = true;
            if (!primoErrore) primoErrore = pwdInput;
        }
        
        // Se ci sono errori
        if (haErrori) {
            e.preventDefault();
            
            // Focus sul primo errore
            if (primoErrore) {
                primoErrore.focus();
            }
        }
    });
    
    // ========================================
    // VALIDAZIONE IN TEMPO REALE (blur/input)
    // ========================================
    
    userInput.addEventListener('blur', function() {
        const errore = validaStringaConNumeri(this, 'Il username', 'o');
        mostraErroreCampo(this, errorUser, errore);
    });
    
    userInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorUser);
        }
    });
    
    pwdInput.addEventListener('blur', function() {
        const errore = validaStringaConNumeri(this, 'La password', 'a');
        mostraErroreCampo(this, errorPassword, errore);
    });
    
    pwdInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorPassword);
        }
    });
    
});