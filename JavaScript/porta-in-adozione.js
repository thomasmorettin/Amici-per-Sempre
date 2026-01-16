document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.form-porta-adozione');

    if (!form) return;

    // Elementi del form
    const nomeInput = document.getElementById('nome');
    const cognomeInput = document.getElementById('cognome');
    const emailInput = document.getElementById('email');
    const telefonoInput = document.getElementById('telefono');

    // Elementi dell'animale
    const specieInput = document.getElementById('specie');
    const razzaInput = document.getElementById('razza');
    const etaInput = document.getElementById('eta');
    const sessoInput = document.getElementById('sesso');
    const pesoInput = document.getElementById('peso');
    const dettagliInput = document.getElementById('dettagli');

    // Recupera i div errore 
    const errorNome = document.getElementById('error-nome');
    const errorCognome = document.getElementById('error-cognome');
    const errorEmail = document.getElementById('error-email');
    const errorTelefono = document.getElementById('error-telefono');
    const errorSpecie = document.getElementById('error-specie');
    const errorRazza = document.getElementById('error-razza');
    const errorEta = document.getElementById('error-eta');
    const errorPeso = document.getElementById('error-peso');
    const errorSesso = document.getElementById('error-sesso');

    // === FUNZIONI DI VALIDAZIONE ===

    function validaStringa(input, nomeCampo) {
        const valore = input.value.trim();

        if (valore === '') {
            return `Il campo ${nomeCampo} è obbligatorio`;
        } else if (valore.length > 25) {
            return `Il campo ${nomeCampo} non può superare i 25 caratteri`;
        } else if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(valore)) {
            return `Il campo ${nomeCampo} contiene caratteri non validi`;
        }
    
        return null;  // Nessun errore
    }

    function validaStringaConNumeri(input, nomeCampo) {
        const valore = input.value.trim();
        
        if (valore === '') {
            return `Il campo ${nomeCampo} è obbligatorio`;
        } else if (valore.length > 25) {
            return `Il campo ${nomeCampo} non può superare i 25 caratteri`;
        } else if (!/^[a-zA-ZÀ-ÿ0-9\s'-]+$/.test(valore)) {
            return `Il campo ${nomeCampo} contiene caratteri non validi`;
        }
        
        return null;  // Nessun errore
    }

    function validaEmail(input) {
        const valore = input.value.trim();
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (valore === '') {
            return "Il campo Email è obbligatorio";
        } else if (!regexEmail.test(valore)) {
            return "Il campo Email non è valido";
        } else if (valore.length > 50) {
            return "Il campo Email non può superare i 50 caratteri";
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
            return "Il campo Telefono è obbligatorio";
        } else if (!/^\d{10}$/.test(telefonoPulito)) {
            return "Il campo Telefono deve contenere esattamente 10 cifre";
        }
        
        return null;
    }

    function validaRadio(container, nomeCampo) {
        if (!container) return `Il campo ${nomeCampo} è obbligatorio`;

        const checkboxes = container.querySelectorAll('input[type="radio"]');
        if (!checkboxes || checkboxes.length === 0) {
            return `Il campo ${nomeCampo} è obbligatorio`;
        }

        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        if (!anyChecked) {
            return `Seleziona almeno una opzione per ${nomeCampo}`;
        }

        return null;
    }

    function mostraErroreCampo(input, errorDiv, messaggio) {
        if (messaggio) {
            input.classList.add('error');
            input.setAttribute('aria-invalid', 'true');
            errorDiv.textContent = messaggio;
            errorDiv.classList.remove('hidden');
        } else {
            rimuoviErroreCampo(input, errorDiv);
        }
    }
    
    function rimuoviErroreCampo(input, errorDiv) {
        input.classList.remove('error');
        input.removeAttribute('aria-invalid');
        errorDiv.textContent = '';
        errorDiv.classList.add('hidden');
    }

    function controllaErrori() {
        let errori = [];
        let primoErrore = null;

        // Validazione dati padrone
        errore = validaStringa(nomeInput, 'Nome');
        if (errore) errori.push({elemento: nomeInput, diverrore: errorNome, messaggio: errore});

        errore = validaStringa(cognomeInput, 'Cognome');
        if (errore) errori.push({elemento: cognomeInput, diverrore: errorCognome, messaggio: errore});

        errore = validaEmail(emailInput);
        if (errore) errori.push({elemento: emailInput, diverrore: errorEmail, messaggio: errore});
        
        errore = validaTelefono(telefonoInput);
        if (errore) errori.push({elemento: telefonoInput, diverrore: errorTelefono, messaggio: errore});

        // Validazione dati animale

        errore = validaRadio(specieInput, 'Specie');
        if (errore) errori.push({elemento: specieInput, diverrore: errorSpecie, messaggio: errore});

        errore = validaStringa(razzaInput, 'Razza');
        if (errore) errori.push({elemento: razzaInput, diverrore: errorRazza, messaggio: errore});

        errore = validaStringaConNumeri(etaInput, 'Età');
        if (errore) errori.push({elemento: etaInput, diverrore: errorEta, messaggio: errore});

        errore = validaStringaConNumeri(pesoInput, 'Peso');
        if (errore) errori.push({elemento: pesoInput, diverrore: errorPeso, messaggio: errore});

        errore = validaRadio(sessoInput, 'Sesso');
        if (errore) errori.push({elemento: sessoInput, diverrore: errorSesso, messaggio: errore});

        if (errori.length > 0) {
            for (let i = 0; i < errori.length; i++) {
                const err = errori[i];
                mostraErroreCampo(err.elemento, err.diverrore, err.messaggio);
                if (!primoErrore) {
                    primoErrore = err.elemento;
                }
            }
            return primoErrore;
        } else {
            // Rimuovi tutti gli errori se non ce ne sono
            rimuoviErroreCampo(nomeInput, errorNome);
            rimuoviErroreCampo(cognomeInput, errorCognome);
            rimuoviErroreCampo(emailInput, errorEmail);
            rimuoviErroreCampo(telefonoInput, errorTelefono);
            rimuoviErroreCampo(specieInput, errorSpecie);
            rimuoviErroreCampo(razzaInput, errorRazza);
            rimuoviErroreCampo(etaInput, errorEta);
            rimuoviErroreCampo(pesoInput, errorPeso);
            rimuoviErroreCampo(sessoInput, errorSesso);
            return null;
        }
    }

    form.addEventListener('submit', function(event) {

        const errore = controllaErrori();
        if (errore) {
            event.preventDefault(); // Impedisci l'invio del form
            
            // Scroll al primo errore
            errore.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
                errore.focus();
            }, 500);
        }
    });

    // === VALIDAZIONE IN TEMPO REALE (blur/input) ===
    
    nomeInput.addEventListener('blur', function() {
        const errore = validaStringa(this, 'Nome');
        mostraErroreCampo(this, errorNome, errore);
    });

    nomeInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorNome);
        }
    });

    cognomeInput.addEventListener('blur', function() {
        const errore = validaStringa(this, 'Cognome');
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

    razzaInput.addEventListener('blur', function() {
        const errore = validaStringa(this, 'Razza');
        mostraErroreCampo(this, errorRazza, errore); 
    });

    razzaInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorRazza);
        }
    });

    etaInput.addEventListener('blur', function() {
        const errore = validaStringaConNumeri(this, 'Età');
        mostraErroreCampo(this, errorEta, errore);
    });

    etaInput.addEventListener('input', function() { 
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorEta);
        }
    });

    pesoInput.addEventListener('blur', function() {
        const errore = validaStringaConNumeri(this, 'Peso');
        mostraErroreCampo(this, errorPeso, errore);
    });

    pesoInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorPeso);
        }
    });

    // Validazione checkbox/radio gruppo

    let specieCheckboxes = specieInput.querySelectorAll('input[type="radio"]');
    specieCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const errore = validaRadio(specieInput, 'Specie');
            if(!errore) {
                rimuoviErroreCampo(specieInput, errorSpecie);
            } else {
                mostraErroreCampo(specieInput, errorSpecie, errore);
            }
        });
    });

    let sessoCheckboxes = sessoInput.querySelectorAll('input[type="radio"]');
    sessoCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const errore = validaRadio(sessoInput, 'Sesso');
            if(!errore) {
                rimuoviErroreCampo(sessoInput, errorSesso);
            } else {
                mostraErroreCampo(sessoInput, errorSesso, errore);
            }
        });
    });


    // === FORMATTAZIONE AUTOMATICA TELEFONO ===
    
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