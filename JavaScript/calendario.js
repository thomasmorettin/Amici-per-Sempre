import { checkInput, updateDialogAppuntamento, deleteDialogAppuntamento, infoDialog } from "./genera-dialogs.js";

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
  
  updateDialogAppuntamento(body);
  deleteDialogAppuntamento(body);
  infoDialog(body);
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