document.addEventListener("DOMContentLoaded", () => {
  animaleDetails();
  dialogsSettings();
  checkInput();
})

// Funzione per apertura/chiusura del tag details per ciascun animale del rifugio
function animaleDetails() {
  const details = document.querySelectorAll("details.dtl-animale");

  details.forEach(detail => {
    const summary = detail.querySelector("summary");
    const contenuto = detail.querySelector(".contenuto-nascosto");

    summary.addEventListener("click", (event) => {
      event.preventDefault();   // Prevenzione dell'azione di default

      if (detail.open) {    // È aperto = si vuole chiudere
        detail.classList.remove("is-expanded");    // Permette animazione fluida della freccia

        const animeChiusura = contenuto.animate({gridTemplateRows: ["1fr", "0fr"]}, {
          duration: 300,
          easing: "ease"
        })

        animeChiusura.onfinish = () => {
          detail.removeAttribute("open");
        }

      }
      
      else {    // È chiuso = si vuole aprire
        detail.classList.add("is-expanded");    // Permette animazione fluida della freccia
        detail.setAttribute("open", "");
        
        contenuto.animate({gridTemplateRows: ["0fr", "1fr"]}, {
          duration: 300,
          easing: "ease"
        })
      }
    })
  })

  currentNumRich();
}

// Funzione per la gestione di tutti i dialog all'interno della pagina
function dialogsSettings() {
  const body = document.body;
  
  updateDialog(body);
  deleteDialog(body);
  infoDialog(body);
}

// Funzione per il controllo degli input
function checkInput() {
  const form = document.getElementById("frm-app");
  const data = document.getElementById("data-appuntamento");
  const ora = document.getElementById("ora-appuntamento");
  const error = document.querySelector(".field-error");
  let errors = [];

  function validaData() {
    const oggi = new Date();
    oggi.setHours(0, 0, 0, 0);

    data.classList.remove("error");

    // Controllo di obbligatorietà
    if (data.value === "") {
      data.classList.add("error");
      data.setAttribute("aria-invalid", "true");
      errors.push("Entrambi i campi sono obbligatori.");
    } else { errors.pop(); }

    // Controllo di data (passata/domenica)
    if (data.value !== "") {
      const dataIn = new Date(data.value);

      if (dataIn < oggi) {
        data.classList.add("error");
        data.setAttribute("aria-invalid", "true");
        errors.push("La data non può essere passata.");
      } else { errors.pop(); }

      if (dataIn.getDay() === 0) {
        data.classList.add("error");
        data.setAttribute("aria-invalid", "true");
        errors.push("La data non può essere una domenica.");
      } else { errors.pop(); }
    }

    validaForm();
  }

  function validaOra() {
    ora.classList.remove("error");

    if (ora.value === "") {
      ora.classList.add("error");
      ora.setAttribute("aria-invalid", "true");
      errors.push("Entrambi i campi sono obbligatori.");
    } else { errors.pop(); }

    // Controllo dell'ora
    if (ora.value !== "") {
      if (ora.value < "08:30" || ora.value > "19:30") {
        ora.classList.add("error");
        ora.setAttribute("aria-invalid", "true");
        errors.push("L'orario consentito è: 08:30 - 19:30.");
      } else { errors.pop(); }
    }

    validaForm();
  }

  function validaForm() {
    // Mostra il primo errore trovato o nasconde il messaggio
    if (errors.length > 0) {
      error.textContent = errors[0];
      error.classList.remove("hidden");
      return false;     // Form non valido
    } else {
      data.setAttribute("aria-invalid", "false");
      ora.setAttribute("aria-invalid", "false");
      error.classList.add("hidden");
      return true;    // Form valido
    }
  }

  // Event listeners
  data.addEventListener("input", validaData);
  ora.addEventListener("input", validaOra);

  data.addEventListener("blur", validaData);
  ora.addEventListener("blur", validaOra);

  form.addEventListener("submit", function(e) {
    const check = validaForm();

    if (!check) { e.preventDefault(); }
  })
}

// Funzione per la gestione del dialog di modifica dei dati dell'appuntamento
function updateDialog(body) {
  const dialogApp = document.getElementById("dia-appuntamento");
  const btnChiudiApp = dialogApp.querySelector(".btn-close");
  const btnPopUp = document.querySelectorAll(".btn-popup-app");
  let lastElem = null;

  function cleanFields() {
    const data = document.getElementById("data-appuntamento");
    const ora = document.getElementById("ora-appuntamento");
    const error = document.querySelector(".field-error");

    data.value = "";
    ora.value = "";
    data.classList.remove("error");
    ora.classList.remove("error");
    data.setAttribute("aria-invalid", "false");
    ora.setAttribute("aria-invalid", "false");
    error.classList.add("hidden");
  }

  btnPopUp.forEach(btn => {
    btn.addEventListener("click", (e) => {
      dialogApp.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("hidden-id").value = `${btn.dataset.id}`;

      lastElem = e.currentTarget;

      dialogApp.showModal();
      body.classList.add("no-scroll");

      document.getElementById("data-appuntamento").focus();
    })
  })

  const chiudiDialogApp = () => {
    dialogApp.close();
    body.classList.remove("no-scroll");
    cleanFields();

    lastElem.focus();
  }

  btnChiudiApp.addEventListener("click", chiudiDialogApp);
  dialogApp.addEventListener("cancel", chiudiDialogApp);
}

// Funzione per la gestione del dialog di eliminazione del ticket
function deleteDialog(body) {
  const dialogDel = document.getElementById("dia-cancellazione");
  const btnChiudiDel = dialogDel.querySelector(".btn-close");
  const btnDel = document.querySelectorAll(".btn-elimina-app");
  let lastElem = null;

  btnDel.forEach(btn => {
    btn.addEventListener("click", (e) => {
      dialogDel.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("hidden-id-del").value = `${btn.dataset.id}`;

      lastElem = e.currentTarget;

      dialogDel.showModal();
      body.classList.add("no-scroll");

      dialogDel.querySelector(".btn-close").focus();
    })
  })

  const chiudiDialogDel = () => {
    dialogDel.close();
    body.classList.remove("no-scroll");

    lastElem.focus();
  }

  btnChiudiDel.addEventListener("click", chiudiDialogDel);
  dialogDel.addEventListener("cancel", chiudiDialogDel);
}

// Funzione per la gestione del dialog per eventuali informazioni sulla richiesta
function infoDialog(body) {
  const dialogInfo = document.getElementById("dia-info");
  const btnChiudiInfo = dialogInfo.querySelector(".btn-close");
  const btnInfo = document.querySelectorAll(".btn-info");
  let lastElem = null;

  btnInfo.forEach(btn => {
    btn.addEventListener("click", (e) => {
      dialogInfo.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("info-rich").innerHTML = `${btn.dataset.info}`;

      lastElem = e.currentTarget;

      dialogInfo.showModal();
      body.classList.add("no-scroll");

      dialogInfo.querySelector(".btn-close").focus();
    })
  })

  const chiudiDialogInfo = () => {
    dialogInfo.close();
    body.classList.remove("no-scroll");

    lastElem.focus();
  }

  btnChiudiInfo.addEventListener("click", chiudiDialogInfo);
  dialogInfo.addEventListener("cancel", chiudiDialogInfo);
}

// Funzione per il calcolo del numero di richiedenti per ciascun animale
function currentNumRich() {
  const numRich = document.querySelectorAll(".num-rich");
  const status = document.querySelectorAll(".richieste");
  const line = document.querySelectorAll(".status-richieste");

  numRich.forEach((num, index) => {
    const currentStat = status[index];
    (parseInt(num.textContent.trim()) == 1) ? currentStat.innerHTML = "nuova richiesta" : null;

    const currentLine = line[index];
    (parseInt(num.textContent.trim()) == 0) ? currentLine.classList.add("no-dot") : null;
  })
}