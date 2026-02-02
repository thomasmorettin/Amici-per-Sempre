document.addEventListener('DOMContentLoaded', function() {
    gestioneCaricamentoRazze();
    loadSelect();
    updateFromSelect();
    const form = document.querySelector('.form-porta-adozione');
    if (!form) return;

    // Elementi del form
    const nomeInput = document.getElementById('nome');
    const cognomeInput = document.getElementById('cognome');
    const emailInput = document.getElementById('email');
    const telefonoInput = document.getElementById('telefono');

    // Elementi dell'animale
    const specieInput = document.getElementById('specie');
    const razzaInput = document.getElementById('razza-select');
    const etaInput = document.getElementById('eta');
    const sessoInput = document.getElementById('sesso');
    const pesoInput = document.getElementById('peso');

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

    function validaStringa(input, nomeCampo, ultimaLettera) {
        const valore = input.value.trim();

        if (valore === '') {
            return `${nomeCampo} è obbligatori${ultimaLettera}`;
        } else if (valore.length > 25) {
            return `${nomeCampo} non può superare i 25 caratteri`;
        } else if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(valore)) {
            return `${nomeCampo} contiene caratteri non validi`;
        }
    
        return null;  // Nessun errore
    }

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

    function validaRadio(container, nomeCampo, ultimaLettera) {
        if (!container) return `${nomeCampo} è obbligatori${ultimaLettera}`;

        const radios = container.querySelectorAll('input[type="radio"]');
        if (!radios || radios.length === 0) {
            return `${nomeCampo} è obbligatori${ultimaLettera}`;
        }

        const anyChecked = Array.from(radios).some(cb => cb.checked);
        if (!anyChecked) {
            return `${nomeCampo} è obbligatori${ultimaLettera}`;
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

    function controllaErrori() {
        let errori = [];
        let primoErrore = null;

        // Validazione dati padrone
        errore = validaStringa(nomeInput, 'Il nome', 'o');
        if (errore) errori.push({elemento: nomeInput, diverrore: errorNome, messaggio: errore});

        errore = validaStringa(cognomeInput, 'Il cognome', 'a');
        if (errore) errori.push({elemento: cognomeInput, diverrore: errorCognome, messaggio: errore});

        errore = validaEmail(emailInput);
        if (errore) errori.push({elemento: emailInput, diverrore: errorEmail, messaggio: errore});
        
        errore = validaTelefono(telefonoInput);
        if (errore) errori.push({elemento: telefonoInput, diverrore: errorTelefono, messaggio: errore});

        // Validazione dati animale

        errore = validaRadio(specieInput, 'la specie', 'a');
        if (errore) errori.push({elemento: specieInput, diverrore: errorSpecie, messaggio: errore});

        errore = validaStringa(razzaInput, 'La razza', 'a');
        if (errore) errori.push({elemento: razzaInput, diverrore: errorRazza, messaggio: errore});

        errore = validaStringaConNumeri(etaInput, 'La età', 'a');
        if (errore) errori.push({elemento: etaInput, diverrore: errorEta, messaggio: errore});

        errore = validaStringaConNumeri(pesoInput, 'Il peso', 'o');
        if (errore) errori.push({elemento: pesoInput, diverrore: errorPeso, messaggio: errore});

        errore = validaRadio(sessoInput, 'Il sesso', 'o');
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
        const errore = validaStringa(this, 'Il nome', 'o');
        mostraErroreCampo(this, errorNome, errore);
    });

    nomeInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorNome);
        }
    });

    cognomeInput.addEventListener('blur', function() {
        const errore = validaStringa(this, 'Il cognome', 'o');
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
        const errore = validaStringa(this, 'La razza', 'a');
        mostraErroreCampo(this, errorRazza, errore); 
    });

    razzaInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorRazza);
        }
    });

    etaInput.addEventListener('blur', function() {
        const errore = validaStringaConNumeri(this, 'La età', 'a');
        mostraErroreCampo(this, errorEta, errore);
    });

    etaInput.addEventListener('input', function() { 
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorEta);
        }
    });

    pesoInput.addEventListener('blur', function() {
        const errore = validaStringaConNumeri(this, 'Il peso', 'o');
        mostraErroreCampo(this, errorPeso, errore);
    });

    pesoInput.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            rimuoviErroreCampo(this, errorPeso);
        }
    });

    // Validazione checkbox/radio gruppo

    let specieRadios = specieInput.querySelectorAll('input[type="radio"]');
    specieRadios.forEach(cb => {
        cb.addEventListener('focusout', function() {
            const errore = validaRadio(specieInput, 'La specie', 'a');
            if(errore) {
                mostraErroreCampo(specieInput, errorSpecie, errore);
            }
        });
        cb.addEventListener('change', function() {
            const errore = validaRadio(specieInput, 'La specie', 'a');
            if(!errore) {
                rimuoviErroreCampo(specieInput, errorSpecie);
            }
        });
    });

    let sessoRadios = sessoInput.querySelectorAll('input[type="radio"]');
    sessoRadios.forEach(cb => {
        cb.addEventListener('focusout', function() {
            const errore = validaRadio(sessoInput, 'Il sesso', 'o');
            if(errore) {
                mostraErroreCampo(sessoInput, errorSesso, errore);
            }
        });
        cb.addEventListener('change', function() {
            const errore = validaRadio(sessoInput, 'La specie', 'a');
            if(!errore) {
                rimuoviErroreCampo(sessoInput, errorSesso);
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

async function richiediRazze(tipo) {
    if (tipo !== "cane" && tipo !== "gatto") {
        return null;
    }

    tipo = (tipo === "cane" ? "Cane" : "Gatto");
    
    try {
        const response = await fetch("razze-json?tipo=" + tipo);
        if (!response.ok) {
            return null;
        }

        const result = await response.json();
        return result["razze"];
    } catch (error) {
        console.error(error.message);
        return null;
    }

}

function ripopulaSelect(selectID, opzioni, razza) {
    const select = document.getElementById(selectID);

    select.innerHTML = '';

    const optDefault = document.createElement('option');
    optDefault.value = "";
    optDefault.textContent = "Seleziona razza per " + razza;
    select.appendChild(optDefault);

    opzioni.forEach(opzione => {
        const opt = document.createElement('option');
        opt.value = opzione;
        opt.textContent = opzione;
        select.appendChild(opt);
    })
}

function gestioneCaricamentoRazze() {
    const radioSpecie = document.querySelectorAll('input[name="specie"]');

    radioSpecie.forEach(radio => {
        richiediRazze(radio.value)
        .then(razze => {
        if (razze !== null) {
            ripopulaSelect('razza-select', razze, radio.value);
        } else {
            console.error("Errore nel fetch delle razze");
        }
        })
        .catch(err => console.error("Error imprevisto:", err));
    });

    radioSpecie.forEach(radio => {
        radio.addEventListener('change', () => {
            richiediRazze(radio.value)
              .then(razze => {
                if (razze !== null) {
                    ripopulaSelect('razza-select', razze, radio.value);
                } else {
                    console.error("Errore nel fetch delle razze");
                }
              })
              .catch(err => console.error("Error imprevisto:", err));
        });
    });
}

function loadSelect() {
  const inputRazza = document.getElementById("razza-input-field");
  const inputSelect = document.getElementById("razza-select-field");

  inputRazza.classList.add("hidden");
  inputRazza.querySelector('input').tabIndex = true;

  inputSelect.classList.remove("hidden");
  inputSelect.querySelector('select').required = true;
  inputRazza.querySelector('input').tabIndex = false;
}

function updateFromSelect() {
    const razzaSelect = document.getElementById("razza-select");
    const razzaInput = document.getElementById("razza-input");

    razzaSelect.addEventListener('change', () => {
        razzaInput.value = razzaSelect.value;
    });

}