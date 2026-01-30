document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('appointment-form');
    const btnRichiestaVisita = document.getElementById('btn-richiedi-visita');
    
    if (!form) return;
    
    // ========================================
    // GESTIONE APERTURA/CHIUSURA FORM + ACCESSIBILITÀ
    // ========================================
    
    // Imposta aria-expanded iniziale
    if (btnRichiestaVisita) {
        btnRichiestaVisita.setAttribute('aria-expanded', 'false');
    }
    
    // Funzione per aprire/chiudere il form
    function toggleForm() {
        const isOpen = form.classList.contains('active');
        
        if (isOpen) {
            // CHIUDI il form
            form.classList.remove('active');
            btnRichiestaVisita.textContent = 'Prenota visita';
            btnRichiestaVisita.setAttribute('aria-expanded', 'false');
            
            // Riporta il focus sul bottone
            btnRichiestaVisita.focus();
            
        } else {
            // APRI il form
            form.classList.add('active');
            btnRichiestaVisita.textContent = 'Chiudi form';
            btnRichiestaVisita.setAttribute('aria-expanded', 'true');
            
            // Focus sul primo campo
            setTimeout(() => {
                const firstInput = document.getElementById('nome');
                if (firstInput) {
                    firstInput.focus();
                }
            }, 100);
        }
    }
    
    // Toggle form al click (Enter e Spazio funzionano già nativamente su <button>)
    if (btnRichiestaVisita) {
        btnRichiestaVisita.addEventListener('click', function(e) {
            e.preventDefault();
            toggleForm();
        });
    } else {
        // Se non c'è il bottone, mostra il form direttamente
        form.classList.add('active');
    }
    
    // Chiudi il form con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && form.classList.contains('active')) {
            form.classList.remove('active');
            btnRichiestaVisita.textContent = 'Prenota Visita';
            btnRichiestaVisita.setAttribute('aria-expanded', 'false');
            btnRichiestaVisita.focus();
        }
    });
    
    // ========================================
    // ELEMENTI DEL FORM
    // ========================================
    
    const nomeInput = document.getElementById('nome');
    const cognomeInput = document.getElementById('cognome');
    const emailInput = document.getElementById('email');
    const telefonoInput = document.getElementById('telefono');
    const privacyCheckbox = document.getElementById('privacy');
    
    // Recupera i div errore 
    const errorNome = document.getElementById('error-nome');
    const errorCognome = document.getElementById('error-cognome');
    const errorEmail = document.getElementById('error-email');
    const errorTelefono = document.getElementById('error-telefono');
    const errorPrivacy = document.getElementById('error-privacy');
    
    // ========================================
    // FUNZIONI DI VALIDAZIONE
    // ========================================
    
    function validaNome(input) {
        const valore = input.value.trim();
        
        if (valore === '') {
            return `${input.name === 'nome' ? 'Il nome' : 'Il cognome'} è obbligatorio`;
        } else if (valore.length > 25) {
            return `${input.name === 'nome' ? 'Il nome' : 'Il cognome'} non può superare i 25 caratteri`;
        } else if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(valore)) {
            return `${input.name === 'nome' ? 'Il nome' : 'Il cognome'} contiene caratteri non validi`;
        }
        
        return null;  // Nessun errore
    }
    
    function validaEmail(input) {
        const valore = input.value.trim();
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (valore === '') {
            return "L'email è obbligatoria";
        } else if (!regexEmail.test(valore)) {
            return "L'email non è valida";
        } else if (valore.length > 50) {
            return "L'email non può superare i 50 caratteri";
        }
        
        return null;
    }
    
    function validaTelefono(input) {
        const valore = input.value.trim();
        let telefonoPulito = valore.replace(/[\s\-\+]/g, '');
        
        if (telefonoPulito.startsWith('39')) {
            telefonoPulito = telefonoPulito.substring(2);
        }
        
        if (valore === '') {
            return "Il telefono è obbligatorio";
        } else if (!/^\d{10}$/.test(telefonoPulito)) {
            return "Il telefono deve contenere esattamente 10 cifre";
        }
        
        return null;
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
        
        // Valida nome
        const errNome = validaNome(nomeInput);
        mostraErroreCampo(nomeInput, errorNome, errNome);
        if (errNome) {
            haErrori = true;
            if (!primoErrore) primoErrore = nomeInput;
        }
        
        // Valida cognome
        const errCognome = validaNome(cognomeInput);
        mostraErroreCampo(cognomeInput, errorCognome, errCognome);
        if (errCognome) {
            haErrori = true;
            if (!primoErrore) primoErrore = cognomeInput;
        }
        
        // Valida email
        const errEmail = validaEmail(emailInput);
        mostraErroreCampo(emailInput, errorEmail, errEmail);
        if (errEmail) {
            haErrori = true;
            if (!primoErrore) primoErrore = emailInput;
        }
        
        // Valida telefono
        const errTelefono = validaTelefono(telefonoInput);
        mostraErroreCampo(telefonoInput, errorTelefono, errTelefono);
        if (errTelefono) {
            haErrori = true;
            if (!primoErrore) primoErrore = telefonoInput;
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
    
    nomeInput.addEventListener('blur', function() {
        const errore = validaNome(this);
        mostraErroreCampo(this, errorNome, errore);
    });
    
    nomeInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorNome);
        }
    });
    
    cognomeInput.addEventListener('blur', function() {
        const errore = validaNome(this);
        mostraErroreCampo(this, errorCognome, errore);
    });
    
    cognomeInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorCognome);
        }
    });
    
    emailInput.addEventListener('blur', function() {
        const errore = validaEmail(this);
        mostraErroreCampo(this, errorEmail, errore);
    });
    
    emailInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorEmail);
        }
    });
    
    telefonoInput.addEventListener('blur', function() {
        const errore = validaTelefono(this);
        mostraErroreCampo(this, errorTelefono, errore);
    });
    
    telefonoInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorTelefono);
        }
    });
    

    // FORMATTAZIONE AUTOMATICA TELEFONO
    
    telefonoInput.addEventListener('input', function() {
        let valore = this.value;
        valore = valore.replace(/[^\d\+]/g, '');
        
        if (valore.startsWith('+39')) {
            valore = valore.substring(0, 13);
        } else if (valore.startsWith('39')) {
            valore = valore.substring(0, 12);
        } else {
            valore = valore.substring(0, 10);
        }
        
        this.value = valore;
    });
});