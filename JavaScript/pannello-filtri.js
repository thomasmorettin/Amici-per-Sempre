const pannelloPopupWidth = 1024;

document.addEventListener("DOMContentLoaded", () => {
    togglePannello();
    gestioneAccordion();
    gestioneBottonePannelloFiltri();
    gestioneRicerca();
    gestioneFormFiltri();
    setupPosizionePanel();
    window.addEventListener('resize', setupPosizionePanel);
    chiusuraPopup();
    gestionePulsanteResetFiltri();
    controlloWrapBottone();
    window.addEventListener('resize', controlloWrapBottone);
    gestioneContaFiltri();
    controllaFlagSezioni();

    const pannello = document.getElementById("side-panel");
    pannello.inert = true;
})

function setupPosizionePanel() {
    const isMobile = window.innerWidth <= pannelloPopupWidth;
    const form = document.getElementById('form-filtri');
    const sidepanel = document.getElementById('side-panel');
    const popup = document.getElementById('popup-panel');

    const filter_content_side = sidepanel.querySelector('.filter-content');
    const filter_content_popup = popup.querySelector('.filter-content');

    if (isMobile) {
        if (sidepanel.classList.contains('open')) {
            sidepanel.classList.toggle('open');
        }
        filter_content_popup.appendChild(form);
        popup.classList.add('active');
        sidepanel.classList.remove('active');
    } else {
        if (popup.classList.contains('open')) {
            document.body.classList.remove("no-scroll");
            popup.classList.toggle('open');
            popup.close();
        }
        filter_content_side.appendChild(form);
        sidepanel.classList.add('active');
        popup.classList.remove('active');
    }
}

function chiusuraPopup() {
    const popup = document.getElementById('popup-panel');
    const btnChiudiPopup = popup.querySelector(".btn-close");

    btnChiudiPopup.addEventListener("click", () => {
        popup.close();
        document.body.classList.remove("no-scroll");
    });
}

function gestioneRicerca() {
    const formRicerca = document.getElementById('form-ricerca');

    if(!formRicerca)
        return;

    formRicerca.addEventListener('submit', function(e) {
        e.preventDefault();

        const urlParams = new URLSearchParams(window.location.search);

        const formData = new FormData(this);

        for (let key of formData.keys()) {
            urlParams.delete(key);
        }

        for (let [key, value] of formData.entries()) {
            if (value.trim() !== '') {
                urlParams.append(key, value);
            }
        }

        window.location.href = '?' + urlParams.toString();
    });
}

function gestioneFormFiltri() {
    const formRicerca = document.getElementById('form-filtri');

    formRicerca.addEventListener('submit', function(e) {
        e.preventDefault();

        const urlParams = new URLSearchParams(window.location.search);

        const formData = new FormData(this);

        // IMPORTANTE: rimuovi TUTTI i parametri del form (anche checkbox)
        const formFields = new FormData(this);
        const allFieldNames = new Set();
        
        // Raccogli TUTTI i nomi dei campi (anche non-checked)
        this.querySelectorAll('input, select, textarea').forEach(field => {
            if (field.name) {
                // Rimuovi [] per gestire array
                const cleanName = field.name.replace('[]', '');
                allFieldNames.add(cleanName);
            }
        });
        
        // Rimuovi dall'URL tutti i parametri del form
        allFieldNames.forEach(name => {
            urlParams.delete(name);
            urlParams.delete(name + '[]'); // Rimuovi anche versione array
        });

        for (let [key, value] of formData.entries()) {
            if (value.trim() !== '') {
                urlParams.append(key, value);
            }
        }

        window.location.href = '?' + urlParams.toString();
    });
}

// Aggiorna contatori e badge (usata sia da listeners locali che da delegati)
function refreshFilterUI() {
    const form = document.getElementById('#form-filtri');
    if (!form) return;
    // aggiorna bottone principale contando tutti i controlli rilevanti in modo generico
    const btn = document.getElementById('#applica');
    const controls = form.querySelectorAll('input, select, textarea');

    let c = 0;
    controls.forEach(el => {
        if (isControlActive(el)) c++;
    });

    if (btn) btn.textContent = `Applica ${c} filtri`;

    // aggiorna i flag per sezione
    updateSectionFlags();
}

// Determina se un controllo va considerato "attivo" come filtro
function isControlActive(el) {
    if (!el || el.disabled) return false;
    const tag = el.tagName && el.tagName.toLowerCase();
    const type = el.type && el.type.toLowerCase();

    if (type === 'checkbox' || type === 'radio') return el.checked;
    if (tag === 'select') {
        if (el.value === '' || el.value == null) return false;
        return String(el.value).trim() !== '';
    }
    if (type === 'range') {
        const min = el.getAttribute('min');
        return Number(el.value) > (min !== null ? Number(min) : 1);
    }
    if (type === 'text' || type === 'search' || tag === 'textarea') {
        if (el.name && el.name.endsWith('_display')) return false;
        return String(el.value || '').trim() !== '';
    }
    // fallback generico
    return String(el.value || '').trim() !== '';
}

// Aggiorna i contatori per ogni sezione (.accordion)
function updateSectionFlags() {
    document.querySelectorAll('.accordion').forEach(acc => {
        const header = acc.querySelector('.accordion-header');
        if (!header) return;
        let flag = header.querySelector('.flag-filtro');
        flagNumero = flag.querySelector('.flag');
        flagScreenReader = flag.querySelector('.solo-sr');

        // conta elementi attivi solo dentro questa accordion (generico)
        let c = 0;
        const controls = acc.querySelectorAll('input, select, textarea');
        controls.forEach(control => {
            if (isControlActive(control)) c++;
        });

        if (c > 0) {
            flagNumero.textContent = c;

            if (c == 1) {
                flagScreenReader.textContent = c + " filtro selezionato";
            } else {
                flagScreenReader.textContent = c + " filtri selezionati";
            }
            
            // flag.style.display = '';
            flag.classList.add('show');
        } else {
            flagNumero.textContent = "";
            flagScreenReader.textContent = "";

            flag.classList.remove('show');
            // hideAndRemoveBadge(flag);
        }
    });
}

// Hide the badge with CSS transition and remove from DOM after legend expansion
function hideAndRemoveBadge(el) {
    if (!el || el.dataset.removing) return;
    el.dataset.removing = '1';
    const header = el.parentElement;
    const legendRight = header ? header.querySelector('.legend-right') : null;

    // compute badge full width including margins
    const bs = getComputedStyle(el);
    const badgeWidth = el.getBoundingClientRect().width + (parseFloat(bs.marginLeft) || 0) + (parseFloat(bs.marginRight) || 0);

    if (legendRight) {
        const prevMax = legendRight.style.maxWidth || '';
        legendRight.style.transition = 'max-width .22s ease';
        legendRight.style.maxWidth = `calc(100% - ${badgeWidth}px)`;
        // force reflow
        legendRight.getBoundingClientRect();

        // shrink badge (this will animate via CSS)
        el.classList.add('hidden');

        // then expand legend to take full space
        requestAnimationFrame(() => {
            legendRight.style.maxWidth = '100%';
        });

        const onLegendEnd = (ev) => {
            if (ev.target !== legendRight) return;
            legendRight.removeEventListener('transitionend', onLegendEnd);
            if (el.parentElement) el.remove();
            legendRight.style.transition = '';
            legendRight.style.maxWidth = prevMax;
            delete el.dataset.removing;
        };

        legendRight.addEventListener('transitionend', onLegendEnd);
    } else {
        el.classList.add('hidden');
        const onEnd = (ev) => {
            if (ev.target !== el) return;
            el.removeEventListener('transitionend', onEnd);
            if (el.classList.contains('hidden')) el.remove();
            delete el.dataset.removing;
        };
        el.addEventListener('transitionend', onEnd);
    }
}

function gestioneAccordion() {
    document.querySelectorAll('.accordion').forEach(acc => {
        const header = acc.querySelector('.accordion-header');
        const arrow = header.querySelector('svg');
        const panel = acc.querySelector('.content');
        const button = acc.querySelectorAll('button')[0];

        panel.inert = true;

        header.addEventListener('click', () => {

            if (panel.classList.contains('open')) {

                // Closing
                panel.style.height = panel.scrollHeight + "px";
                panel.getBoundingClientRect(); // force reflow

                requestAnimationFrame(() => {
                panel.style.height = "0px";
                });

                panel.inert = true;
                button.setAttribute('aria-expanded', 'false');

            } else {

                // Opening
                panel.style.height = panel.scrollHeight + "px";
                panel.inert = false;
                button.setAttribute('aria-expanded', 'true');
            }

            panel.classList.toggle('open');
            arrow.classList.toggle('open');
        });

        panel.addEventListener('transitionend', () => {
        if (panel.classList.contains('open')) {
            panel.style.height = "auto";
        }
        });
    });
}

function gestioneBottonePannelloFiltri() {
    const filtraBtn = document.getElementById('filtra-btn');
    
    if (filtraBtn) {
        filtraBtn.addEventListener('click', (e) => {
            toggleFilter(e);
        });
    } else {
        console.debug('gestioneBottonePannelloFiltri: #filtra-btn non trovato');
    }
}

function toggleFilter() {
    if (window.innerWidth <= pannelloPopupWidth) {
        const pannello = document.getElementById("popup-panel");
        if (!pannello) {
            return;
        }
        pannello.showModal();
        pannello.classList.toggle("open");
        document.body.classList.add("no-scroll");
    } else {
        const pannello = document.getElementById("side-panel");
        if (!pannello) {
            console.warn('toggleFilter: .filter-panel non trovato');
            return;
        }
        pannello.classList.toggle("open");

        if(pannello.classList.contains("open")) {
            pannello.inert = false;
        } else {
            pannello.inert = true;
        }
    }
}

function gestionePulsanteResetFiltri() {
    const form = document.getElementById("form-filtri");
    const pulsanteReset = form.querySelector(".reset");

    pulsanteReset.addEventListener('click', function() {
        form.reset();

        form.querySelectorAll('input, textarea, select').forEach(el => {
            if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = false;
            }
            else {
                el.value = '';
                el.dataset.changed = false;
            }
        });

        azzeraCount();
    });
}

function togglePannello() {
    document.querySelectorAll("fieldset.collapse legend.toggle").forEach(legend => {
        legend.addEventListener("click", function () {
            this.parentElement.classList.toggle("open");
        });
    });
}

function cambiaNumeroFlag(numero, flag, numFlag, descFlag, add = true) {
    if (!numero && numero !== 0) return;
    
    if (add) {
        numero++;
    } else {
        numero--;
    }

    numFlag.textContent = numero;
    
    if (numero == 1) {
        descFlag.textContent = numero + " filtro selezionato";
    } else if (numero > 1) {
        descFlag.textContent = numero + " filtri selezionati";
    } else {
        descFlag.textContent = "";
    }

    if (numero > 0) {
        flag.classList.add('show');
    } else {
        flag.classList.remove('show');
    }
}

function cambiaNumeroFlagDinamico(element, add = true) {
    const accordionPadre = element.closest('.accordion');
    const flag = accordionPadre.querySelector('.flag-filtro');
    const numFlag = accordionPadre.querySelector('.flag');
    const descFlag = accordionPadre.querySelector('.solo-sr');

    if (!numFlag) return;

    let nFlag = +numFlag.textContent;

    cambiaNumeroFlag(nFlag, flag, numFlag, descFlag, add);
}

function azzeraCount() {
    const form = document.getElementById('form-filtri');
    const btn = document.getElementById('applica');

    form.querySelectorAll('.accordion').forEach(acc => {
        const flag = acc.querySelector('.flag-filtro');
        const numFlag = acc.querySelector('.flag');
        const descFlag = acc.querySelector('.solo-sr');

        acc.querySelectorAll('input, select').forEach(el => {
            if (el.type === 'checkbox' || el.type === 'radio') {
                cambiaNumeroFlag(1, flag, numFlag, descFlag, false);
            } else if (el.type === 'text' || el.type === 'email' || el.type === 'tel') {
                cambiaNumeroFlag(1, flag, numFlag, descFlag, false);
            } else {
                cambiaNumeroFlag(1, flag, numFlag, descFlag, false);
            }
        });
    });

    btn.dataset.nFiltri = 0;
    btn.textContent = "Applica " + 0 + " filtri";
}

function controllaFlagSezioni() {
    const form = document.getElementById('form-filtri');

    form.querySelectorAll('.accordion').forEach(acc => {
        const flag = acc.querySelector('.flag-filtro');
        const numFlag = acc.querySelector('.flag');
        const descFlag = acc.querySelector('.solo-sr');

        acc.querySelectorAll('input, select').forEach(el => {
            if (el.type === 'checkbox' || el.type === 'radio') {
                if(el.checked) {
                    let nFlag = +numFlag.textContent;
                    cambiaNumeroFlag(nFlag, flag, numFlag, descFlag);
                }
            } else if (el.type === 'text' || el.type === 'email' || el.type === 'tel') {
                if(el.value !== "" && el.dataset.changed == "true") {
                    let nFlag = +numFlag.textContent;
                    cambiaNumeroFlag(nFlag, flag, numFlag, descFlag);
                }
            } else {
                if (el.value !== "" && el.dataset.changed == "true") {
                    let nFlag = +numFlag.textContent;
                    cambiaNumeroFlag(nFlag, flag, numFlag, descFlag);
                }
            }
        });
    });
}

function cambiaNumeroFiltri(add = true) {
    const btn = document.getElementById('applica');
    let nFiltri = +btn.dataset.nFiltri;

    if (!nFiltri && nFiltri !== 0) return;

    if (add) {
        nFiltri++;
    } else {
        nFiltri--;
    }

    btn.dataset.nFiltri = nFiltri;
    btn.textContent = "Applica " + nFiltri + " filtri";
}

function gestioneContaFiltri() {
    const form = document.getElementById('form-filtri');
    if (!form) return;
    const btn = document.getElementById('applica');

    form.querySelectorAll('input, select').forEach(el => {
        if (el.type === 'checkbox' || el.type === 'radio') {
            el.addEventListener('change', () => {
                cambiaNumeroFiltri(el.checked ? true : false);
                cambiaNumeroFlagDinamico(el, (el.checked ? true : false));
            });
        } else if (el.type === 'text' || el.type === 'email' || el.type === 'tel') {
            el.addEventListener('input', () => {
                if(el.value === "" && el.dataset.changed == "true") {
                    cambiaNumeroFiltri(false);
                    cambiaNumeroFlagDinamico(el, false);
                    el.dataset.changed = false;
                }
                else if(el.value !== "" && el.dataset.changed == "false") {
                    cambiaNumeroFiltri();
                    cambiaNumeroFlagDinamico(el);
                    el.dataset.changed = true;
                }
            });
        } else {
            el.addEventListener('change', () => {
                if (el.value === "" && el.dataset.changed == "true") {
                    cambiaNumeroFiltri(false);
                    cambiaNumeroFlagDinamico(el, false);
                    el.dataset.changed = false;
                } else if (el.value !== "" && el.dataset.changed == "false") {
                    cambiaNumeroFiltri();
                    cambiaNumeroFlagDinamico(el);
                    el.dataset.changed = true;
                }
            })
        }
    });
}

function controlloWrapBottone() {
    const btnFiltri = document.getElementById('filtra-btn');
    const formRicerca = document.getElementById('form-ricerca');

    if(!formRicerca) return;
    
    const formRicercaTop = formRicerca.getBoundingClientRect().top;
    const buttonTop = btnFiltri.getBoundingClientRect().top;

    if (buttonTop > formRicercaTop) {
        btnFiltri.classList.add('wrapped');
    } else {
        btnFiltri.classList.remove('wrapped');
    }
}