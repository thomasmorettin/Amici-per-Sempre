document.addEventListener("DOMContentLoaded", () => {
  pageScroll();
  backToTop();
  changeCopyright();
  cleanDetails();
  backgroundSetting();
  themeSetting();
  toast();
  menuResponsive();
  backToTop();
  loadPage();   // Eseguita per ultima nel caso ci dovessero essere problemi in funzioni
})

function menuResponsive() {
  const hamburger = document.getElementById("hamburger");

  hamburger.addEventListener("click", function() {
    document.getElementById("contenitore-header").classList.toggle("open");
    hamburger.classList.toggle("active");
    document.querySelector("header").classList.toggle("scrolled");

    if (hamburger.getAttribute("aria-expanded") === "true") {
      hamburger.setAttribute("aria-expanded", "false");
      hamburger.setAttribute("aria-label", "apri menù di navigazione");
    } else {
      hamburger.setAttribute("aria-expanded", "true");
      hamburger.setAttribute("aria-label", "chiudi menù di navigazione");
    }
  })
}

/*
 * Funzione per il caricamento di elementi che altrimenti non verrebbero visualizzati
 * in caso di problematiche con JS (disabilitazione)
*/
function loadPage() {
  document.getElementById("contenitore-header").classList.remove("open");
  document.querySelector("header").classList.remove("scrolled");
  document.getElementById("btn-remove-bck").classList.remove("hidden");
  document.getElementById("btn-theme").classList.remove("hidden");

  const maps = document.getElementById("google-maps");
  (maps) ? maps.classList.remove("hidden") : null;
}

// Funzione la visibilità di bordo header + bottone allo scroll di pagina
function pageScroll() {
  const header = document.querySelector("header");
  const backToTop = document.getElementById("btn-back-to-top");

  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      header.classList.add("scrolled");
      backToTop.classList.add("visible");
    }
    
    else {
      if (!window.innerWidth < 768) {
        header.classList.remove("scrolled");
        backToTop.classList.remove("visible");
      }
    }
  })
}

// Funzione per tornare in cima
function backToTop() {
  const btn = document.getElementById("btn-back-to-top");

  btn.addEventListener("click", function() {
    window.scrollTo({top: 0, behavior: "smooth"});
  })
}

// Funzione per il cambio dell'anno di copyright all'anno corrente
function changeCopyright() {
  const year = document.getElementById("cop-year").innerHTML = new Date().getFullYear();
}

/*
 * Funzione per la pulizia del tag details nel caso di summary:hover, in quanto
 * persiste un bug di rendering sugli angoli interni dell'elemento grafico
*/
function cleanDetails() {
  const details = document.querySelectorAll("details");

  details.forEach(detail => {
      const summary = detail.querySelector("summary");

      summary.addEventListener("mouseenter", () => {
          detail.classList.add("summary-hovered");
      })

      summary.addEventListener("mouseleave", () => {
          detail.classList.remove("summary-hovered");
      })
  })
}

// Funzione per inserimento/rimozione del background in caso di difficoltà di lettura
function backgroundSetting() {
  const btn = document.getElementById("btn-remove-bck");
  const root = document.documentElement;

  const STORAGE_KEY = "backgroundRemoved";
  if (localStorage.getItem(STORAGE_KEY) == "true") { updateBackgroundButton(true); }
  else { updateBackgroundButton(false); }

  btn.addEventListener("click", () => {
    if (localStorage.getItem(STORAGE_KEY) == "true") {
      root.classList.add("set-background");
      btn.setAttribute("aria-label", "reimposta immagine di sfondo");
      localStorage.setItem(STORAGE_KEY, "false");
      updateBackgroundButton(false);
    }

    else {
      root.classList.remove("set-background");
      btn.setAttribute("aria-label", "rimuovi immagine di sfondo");
      localStorage.setItem(STORAGE_KEY, "true");
      updateBackgroundButton(true);
    }
  })

  function updateBackgroundButton(flag) {
    if (flag) {
      btn.classList.add("enabled");
      btn.setAttribute("title", "Ripristina Sfondo");
    }

    else {
      btn.classList.remove("enabled");
      btn.setAttribute("title", "Rimuovi Sfondo");
    }
  }
}

// Funzione per cambio di tema chiaro/scuro all'interno del sito
function themeSetting() {
  const btn = document.getElementById("btn-theme");
  const root = document.documentElement;

  const STORAGE_KEY = "themeDark";
  if (localStorage.getItem(STORAGE_KEY) == "true") { updateButton(true); }
  else { updateButton(); }

  btn.addEventListener("click", () => {
    if (localStorage.getItem(STORAGE_KEY) == "true") {
      root.dataset.theme = "light";
      btn.setAttribute("aria-label", "imposta tema chiaro");
      localStorage.setItem(STORAGE_KEY, "false");
      updateButton(false);
    }

    else {
      root.dataset.theme = "dark";
      btn.setAttribute("aria-label", "imposta tema scuro");
      localStorage.setItem(STORAGE_KEY, "true");
      updateButton(true);
    }
  })

  function updateButton(flag) {
    if (flag) {
      btn.classList.add("light");
      btn.setAttribute("title", "Tema chiaro");
    }

    else {
      btn.classList.remove("light");
      btn.setAttribute("title", "Tema scuro");
    }
  }
}

// Funzione per il popolamento dell'elemento toast per messaggi dopo esecuzione di azioni
function toast() {
  const body = document.body;
  const msg = body.dataset.toastMsg;
  const type = body.dataset.toastType;

  if (msg) {
    const toast = document.getElementById("toast-not");
    const toastMsg = toast.querySelector("span");

    if (toast && toastMsg) {
      toastMsg.innerHTML = msg;
      toast.classList.remove("hidden");
      toast.classList.add(type === "error" ? "error" : "success");
      toast.focus();

      setTimeout(() => { toast.classList.add("hidden"); }, 10000);
    }
  }
}