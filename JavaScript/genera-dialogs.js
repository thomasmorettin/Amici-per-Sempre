// Funzione per il controllo degli input
export function checkInput() {
  const form = document.getElementById("frm-app");
  const data = document.getElementById("data-appuntamento");
  const ora = document.getElementById("ora-appuntamento");
  const cont = document.querySelector(".field-error");
  const error = document.getElementById("msg-errore-app");
  let errors = [];

  function validaForm() {
    // Svuotiamo gli errori all'inizio di ogni controllo totale
    errors = []; 
    const adesso = new Date();
    const oggi = new Date();
    oggi.setHours(0, 0, 0, 0);

    // Reset classi e attributi
    data.classList.remove("error");
    ora.classList.remove("error");
    data.setAttribute("aria-invalid", "false");
    ora.setAttribute("aria-invalid", "false");

    // 1. Validazione DATA
    if (data.value === "") {
      errors.push("La data è obbligatoria.");
      data.classList.add("error");
    } else {
      const dataIn = new Date(data.value);
      if (dataIn < oggi) {
        errors.push("La data non può essere passata.");
        data.classList.add("error");
      } else if (dataIn.getDay() === 0) {
        errors.push("La data non può essere una domenica.");
        data.classList.add("error");
      }
    }

    // 2. Validazione ORA
    if (ora.value === "") {
      errors.push("L'orario è obbligatorio.");
      ora.classList.add("error");
    } else {
      // Controllo range aziendale
      if (ora.value < "08:30" || ora.value > "19:30") {
        errors.push("L'orario consentito è: 08:30 - 19:30.");
        ora.classList.add("error");
      }

      // FIX: Controllo ora passata se la data è OGGI
      if (data.value !== "") {
        const dataIn = new Date(data.value);
        if (dataIn.toDateString() === oggi.toDateString()) {
          const oraAdesso = adesso.getHours().toString().padStart(2, '0') + ":" + 
                            adesso.getMinutes().toString().padStart(2, '0');
          
          if (ora.value < oraAdesso) {
            errors.push("L'orario inserito è già passato.");
            ora.classList.add("error");
          }
        }
      }
    }

    // Gestione UI errori
    if (errors.length > 0) {
      error.textContent = errors[0];
      cont.classList.remove("hidden");
      // Applichiamo aria-invalid agli elementi con classe error
      if (data.classList.contains("error")) data.setAttribute("aria-invalid", "true");
      if (ora.classList.contains("error")) ora.setAttribute("aria-invalid", "true");
      return false;
    } else {
      error.textContent = "";
      cont.classList.add("hidden");
      return true;
    }
  }

  // Event listeners semplificati: tutti chiamano validaForm per un controllo incrociato
  data.addEventListener("input", validaForm);
  ora.addEventListener("input", validaForm);
  data.addEventListener("blur", validaForm);
  ora.addEventListener("blur", validaForm);

  form.addEventListener("submit", function(e) {
    const check = validaForm();

    if (!check) { e.preventDefault(); }
  })
}

// Funzione per la gestione del dialog di modifica dei dati dell'appuntamento
export function updateDialogRichiesta(body) {
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

// Funzione per la gestione del dialog di modifica dei dati dell'appuntamento
export function updateDialogAppuntamento(body) {
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
    error.classList.add("hidden");
  }

  btnPopUp.forEach(btn => {
    btn.addEventListener("click", (e) => {
      dialogApp.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("hidden-id").value = `${btn.dataset.id}`;
      document.getElementById("hidden-old-data").value = `${btn.dataset.data}`;
      document.getElementById("data-appuntamento").value = `${btn.dataset.data}`;
      document.getElementById("ora-appuntamento").value = `${btn.dataset.ora}`;

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
export function deleteDialogRichiesta(body) {
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

// Funzione per la gestione del dialog di eliminazione del ticket
export function deleteDialogAppuntamento(body) {
  const dialogDel = document.getElementById("dia-cancellazione");
  const btnChiudiDel = dialogDel.querySelector(".btn-close");
  const btnDel = document.querySelectorAll(".btn-elimina-app");
  let lastElem = null;

  btnDel.forEach(btn => {
    btn.addEventListener("click", (e) => {
      dialogDel.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("data-app").innerHTML = `${btn.dataset.dataDisplay}`;
      document.getElementById("ora-app").innerHTML = `${btn.dataset.ora}`;

      document.getElementById("hidden-id-del").value = `${btn.dataset.id}`;
      document.getElementById("hidden-data-del").value = `${btn.dataset.data}`;
      document.getElementById("hidden-ora-del").value = `${btn.dataset.ora}`;

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
export function infoDialog(body) {
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
export function currentNumRich() {
  const numRich = document.querySelectorAll(".num-rich");
  const status = document.querySelectorAll(".richieste");
  const line = document.querySelectorAll(".status-richieste");

  numRich.forEach((num, index) => {
    const currentStat = status[index];
    (parseInt(num.textContent.trim()) == 1) ? currentStat.innerHTML = "richiesta" : null;

    const currentLine = line[index];
    (parseInt(num.textContent.trim()) == 0) ? currentLine.classList.add("no-dot") : null;
  })
}