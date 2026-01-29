const pannelloPopupWidth = 1024;

document.addEventListener("DOMContentLoaded", () => {
    togglePannello();
    gestioneAccordion();
    contaFiltri();
    gestioneBottonePannelloFiltri();
    updateSectionFlags();
    gestioneRicerca();
    gestioneFormFiltri();
    setupPosizionePanel();
    window.addEventListener('resize', setupPosizionePanel);
    chiusuraPopup();
    gestionePulsanteResetFiltri();
    controlloWrapBottone();

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
    const panel = document.querySelector('.filter-panel');
    if (!panel) return;
    // aggiorna bottone principale contando tutti i controlli rilevanti in modo generico
    const btn = panel.querySelector('#applica');
    const controls = panel.querySelectorAll('input, select, textarea');

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
            }
        });
    });
}


function togglePannello() {
    document.querySelectorAll("fieldset.collapse legend.toggle").forEach(legend => {
        legend.addEventListener("click", function () {
            this.parentElement.classList.toggle("open");
        });
    });
}


function contaFiltri() {
  const panel = document.querySelector('.filter-panel');
  if (!panel) return;
  const btn = panel.querySelector('#applica');
  const nameInp = panel.querySelector('input[name="nome"]');
  const pesoInp = panel.querySelector('input[name="peso"]');
  const etaInp  = panel.querySelector('input[name="eta"]');
  const tipoChecks = panel.querySelectorAll('input[name="tipo[]"]');

  function countActive() {
    let c = 0;
    c += [...tipoChecks].filter(ch => ch.checked).length;
    if (nameInp && nameInp.value.trim() !== '') c++;
    if (pesoInp && Number(pesoInp.value) > Number(pesoInp.getAttribute('min') || 1)) c++;
    if (etaInp  && Number(etaInp.value)  > Number(etaInp.getAttribute('min')  || 1)) c++;
    btn.textContent = `Applica ${c} filtri`;
  }

    const deb = (fn, ms=120) => { let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a), ms); }; };
        const onChange = deb(refreshFilterUI, 100);

        // listeners locali sul panel
        panel.addEventListener('input', onChange);
        panel.addEventListener('change', onChange);

        // assicurati che il reset del form aggiorni i contatori (dopo che il browser ha ripristinato i valori)
        const form = panel.querySelector('form');
        if (form) {
            form.addEventListener('reset', () => {
                // posticipa leggermente per lasciare il browser applicare i valori di default
                setTimeout(() => {
                    refreshFilterUI();
                }, 10);
            });
        }

        // delega globale: utile se elementi vengono ricreati o il DOM cambia
        document.addEventListener('input', (e) => {
                if (e.target.closest && e.target.closest('.filter-panel')) onChange();
        });
        document.addEventListener('change', (e) => {
                if (e.target.closest && e.target.closest('.filter-panel')) onChange();
        });

        // inizializza UI
        refreshFilterUI();
}

function controlloWrapBottone() {
    window.addEventListener('resize', () => {
        const btnFiltri = document.getElementById('filtra-btn');
        const formRicerca = document.getElementById('form-ricerca');

        const formRicercaTop = formRicerca.getBoundingClientRect().top;
        const buttonTop = btnFiltri.getBoundingClientRect().top;

        if (buttonTop > formRicercaTop) {
            btnFiltri.classList.add('wrapped');
        } else {
            btnFiltri.classList.remove('wrapped');
        }
    });
}