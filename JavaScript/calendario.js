document.addEventListener("DOMContentLoaded", () => {
  toggleWeeks();
  dialogsSettings();
  hashGiorno();
  checkInput();
})

// Funzione per la gestione del meccanismo toggle dei bottoni delle settimane
function toggleWeeks() {
  const buttons = document.querySelectorAll(".btn-toggle");
    const weeks = document.querySelectorAll(".lista-settimana");

    buttons.forEach(button => {
      button.addEventListener("click", function() {
          const selectBtn = document.querySelector(".btn-toggle.active");
          if (selectBtn) {
            selectBtn.classList.remove("active");
            selectBtn.setAttribute("aria-current", "false");
          }

          this.classList.add("active");
          this.setAttribute("aria-current", "true");

          weeks.forEach(week => {
            week.classList.add("hidden");
          })

          const targetId = this.getAttribute("data-target");
          const targetWeek = document.getElementById(targetId);
          if (targetWeek) { targetWeek.classList.remove("hidden"); }
      })
  })
}

// Funzione per la gestione di tutti i dialog all'interno della pagina
function dialogsSettings() {
  const body = document.body;
  
  updateDialog(body);
  deleteDialog(body);
  infoDialog(body);
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
    error.classList.add("hidden");
  }

  btnPopUp.forEach(btn => {
    btn.addEventListener("click", (e) => {
      dialogApp.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("hidden-id").value = `${btn.dataset.id}`;
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
function deleteDialog(body) {
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

/*
 * Funzione per l'identificazione del giorno nel caso in cui si voglia visualizzare l'appuntamento,
 * possibile da link di collegamento dalla pagina di gestione-ticket
*/
function hashGiorno() {
  document.documentElement.classList.add("scroll-padding");
  const hash = window.location.hash;

  if (hash) {
    const target = hash.substring(1);
    const element = document.getElementById(target);

    if (element) {
      const parentWeek = element.closest(".lista-settimana");

      if (parentWeek) {
        document.querySelectorAll(".lista-settimana").forEach(el => {
            el.classList.add("hidden");
        });
        
        document.querySelectorAll(".btn-toggle").forEach(btn => {
            btn.classList.remove("active");
        });

        parentWeek.classList.remove("hidden");

        const weekID = parentWeek.id;
        const btn = document.querySelector(`.btn-toggle[data-target='${weekID}']`);
        
        btn.classList.add("active");
      }
    }
  }
}