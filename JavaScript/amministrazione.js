function changeWelcome() {
    const ora = new Date().getHours();
    const time = document.getElementById("ben-time");

    if (ora > 8 && ora < 12) { time.textContent = "Buongiorno"; }
    else if (ora > 12 && ora < 18) { time.textContent = "Buonpomeriggio"; }
    else { time.textContent = "Buonasera"; }
}

function currentDate() {
    const data = new Date();
    const giorno = data.getDate();
    const nomiSett = ["Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica"];
    const sett = nomiSett[data.getDay() - 1];
    const anno = data.getFullYear();

    document.getElementById("ben-date").textContent = `${sett}` + " " + `${giorno}` + ", " + `${anno}`;
}

changeWelcome();

currentDate();