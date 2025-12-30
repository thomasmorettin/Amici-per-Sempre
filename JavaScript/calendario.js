document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".btn-toggle");
    const weeks = document.querySelectorAll(".lista-settimana");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            const selectBtn = document.querySelector(".btn-toggle.active");
            if (selectBtn) { selectBtn.classList.remove("active"); }

            this.classList.add("active");

            weeks.forEach(week => {
              week.classList.add("hidden");
            });

            const targetId = this.getAttribute("data-target");
            const targetWeek = document.getElementById(targetId);
            if (targetWeek) { targetWeek.classList.remove("hidden"); }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;

  const dialogApp = document.getElementById("dia-appuntamento");
  const dialogDel = document.getElementById("dia-cancellazione");

  const btnChiudiApp = dialogApp.querySelector(".btn-close");
  const btnChiudiDel = dialogDel.querySelector(".btn-close");
  
  const btnPopUp = document.querySelectorAll(".btn-popup-app");
  const btnDel = document.querySelectorAll(".btn-elimina-app");

  btnPopUp.forEach(btn => {
    btn.addEventListener("click", () => {
      dialogApp.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("hidden-id").value = `${btn.dataset.id}`;
      document.getElementById("data-appuntamento").value = `${btn.dataset.data}`;
      document.getElementById("ora-appuntamento").value = `${btn.dataset.ora}`;

      dialogApp.showModal();
      body.classList.add("no-scroll");
    });
  });

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
  });

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

  const chiudiDialogApp = () => {
    dialogApp.close();
    body.classList.remove("no-scroll");
  };

  const chiudiDialogDel = () => {
    dialogDel.close();
    body.classList.remove("no-scroll");
  };

  btnChiudiApp.addEventListener("click", chiudiDialogApp);
  btnChiudiDel.addEventListener("click", chiudiDialogDel);
});

function pageScroll() {
  const floating = document.getElementById("btns-flottanti");

  if (floating) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 200) {
        floating.classList.add("scrolled");
      } else {
        floating.classList.remove("scrolled");
      }
    });
  }
}

pageScroll();