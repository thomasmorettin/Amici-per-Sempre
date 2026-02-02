document.addEventListener("DOMContentLoaded", () => {
  changeWelcome();
  currentDate();
  currentNumApp();
  loadAmministrazione();
})

// Funzione per il caricamento degli elementi gestiti da JS
function loadAmministrazione() {
    document.getElementById("data-oggi").classList.remove("hidden");
}

// Funzione per cambiare il saluto all'amministratore
function changeWelcome() {
    const ora = new Date().getHours();
    const time = document.getElementById("ben-time");

    if (ora >= 6 && ora < 12) { time.textContent = "Buongiorno"; }
    else if (ora > 12 && ora < 18) { time.textContent = "Buonpomeriggio"; }
    else { time.textContent = "Buonasera"; }
}

// Funzione per mostrare la data odierna formattata in modo accogliente
function currentDate() {
    const data = new Date();
    const nomiSett = ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"];
    const nomiMesi = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", 
                    "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
    const sett = nomiSett[data.getDay()];
    const giorno = data.getDate();
    const mese = nomiMesi[data.getMonth()];
    const anno = data.getFullYear();

    document.getElementById("ben-date").textContent = `${sett}` + " " + `${giorno}` + " " + `${mese}` + ", " + `${anno}`;
}

// Funzione per mostrare il pallino di notifica per uno o più appuntamenti odierni
function currentNumApp() {
    const not = document.getElementById("num-app");
    const numApp = parseInt(not.textContent.trim());
    const app = document.getElementById("app");

    (numApp == 1) ? app.innerHTML = "appuntamento" : null;
    (numApp == 0) ? not.classList.add("no-dot") : null;
    (numApp > 0) ? document.getElementById("polite-app").setAttribute("aria-live", "polite") : null;
}