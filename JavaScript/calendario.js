document.addEventListener("DOMContentLoaded", () => {
  toggleWeeks();
  dialogsSettings();
  pageScroll();
  hashGiorno();
})

// Funzione per la gestione del meccanismo toggle dei bottoni delle settimane
function toggleWeeks() {
  const buttons = document.querySelectorAll(".btn-toggle");
    const weeks = document.querySelectorAll(".lista-settimana");

    buttons.forEach(button => {
      button.addEventListener("click", function() {
          const selectBtn = document.querySelector(".btn-toggle.active");
          if (selectBtn) { selectBtn.classList.remove("active"); }

          this.classList.add("active");

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
  const body = document.body();
  
  updateDialog(body);
  deleteDialog(body);
  infoDialog(body);
}

// Funzione per la gestione del dialog di modifica dei dati dell'appuntamento
function updateDialog(body) {
  const dialogApp = document.getElementById("dia-appuntamento");
  const btnChiudiApp = dialogApp.querySelector(".btn-close");
  const btnPopUp = document.querySelectorAll(".btn-popup-app");

  btnPopUp.forEach(btn => {
    btn.addEventListener("click", () => {
      dialogApp.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("hidden-id").value = `${btn.dataset.id}`;
      document.getElementById("data-appuntamento").value = `${btn.dataset.data}`;
      document.getElementById("ora-appuntamento").value = `${btn.dataset.ora}`;

      dialogApp.showModal();
      body.classList.add("no-scroll");
    })
  })

  // Gestione dell'input nel caso errato
  dialogApp.addEventListener("submit", (e) => {
    const data = document.getElementById("data-appuntamento").value;
    const ora = document.getElementById("ora-appuntamento").value;

    // Data corrente
    const oggiObj = new Date();
    const anno = oggiObj.getFullYear();
    const mese = String(oggiObj.getMonth() + 1).padStart(2, '0');
    const giorno = String(oggiObj.getDate()).padStart(2, '0');
    
    const currentData = `${anno}-${mese}-${giorno}`;

    if (data < currentData || ora < "08:30" || ora > "19:30") {
      e.preventDefault();
      document.getElementById("msg-errore").classList.remove("hidden");
    }
  })

  const chiudiDialogApp = () => {
    dialogApp.close();
    body.classList.remove("no-scroll");
  }

  btnChiudiApp.addEventListener("click", chiudiDialogApp);
}

// Funzione per la gestione del dialog di eliminazione del ticket
function deleteDialog(body) {
  const dialogDel = document.getElementById("dia-cancellazione");
  const btnChiudiDel = dialogDel.querySelector(".btn-close");
  const btnDel = document.querySelectorAll(".btn-elimina-app");

  btnDel.forEach(btn => {
    btn.addEventListener("click", () => {
      dialogDel.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("data-app").innerHTML = `${btn.dataset.dataDisplay}`;
      document.getElementById("ora-app").innerHTML = `${btn.dataset.ora}`;

      document.getElementById("hidden-id-del").value = `${btn.dataset.id}`;
      document.getElementById("hidden-data-del").value = `${btn.dataset.data}`;
      document.getElementById("hidden-ora-del").value = `${btn.dataset.ora}`;

      dialogDel.showModal();
      body.classList.add("no-scroll");
    })
  })

  const chiudiDialogDel = () => {
    dialogDel.close();
    body.classList.remove("no-scroll");
  }

  btnChiudiDel.addEventListener("click", chiudiDialogDel);
}

// Funzione per la gestione del dialog per eventuali informazioni sulla richiesta
function infoDialog(body) {
  const dialogInfo = document.getElementById("dia-info");
  const btnChiudiInfo = dialogInfo.querySelector(".btn-close");
  const btnInfo = document.querySelectorAll(".btn-info");

  btnInfo.forEach(btn => {
    btn.addEventListener("click", () => {
      dialogInfo.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("info-rich").innerHTML = `${btn.dataset.info}`;

      dialogInfo.showModal();
      body.classList.add("no-scroll");
    })
  })

  const chiudiDialogInfo = () => {
    dialogInfo.close();
    body.classList.remove("no-scroll");
  }

  btnChiudiInfo.addEventListener("click", chiudiDialogInfo);
}

// Funzione per l'ancoraggio del pannello di comandi on-top
function pageScroll() {
  const floating = document.getElementById("btns-flottanti");

  if (floating) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 200) {
        floating.classList.add("scrolled");
      }
      
      else {
        floating.classList.remove("scrolled");
      }
    })
  }
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