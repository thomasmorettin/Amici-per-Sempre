document.addEventListener("DOMContentLoaded", () => {
  const details = document.querySelectorAll("details.dtl-animale");

  details.forEach(detail => {
    const summary = detail.querySelector("summary");
    const contenuto = detail.querySelector(".contenuto-nascosto");

    summary.addEventListener("click", (event) => {
      event.preventDefault();   // Prevenzione dell'azione di default

      if (detail.open) {    // È aperto = si vuole chiudere
        detail.classList.remove("dtl-aperto");    // Permette animazione fluida della freccia

        const animeChiusura = contenuto.animate({gridTemplateRows: ["1fr", "0fr"]}, {
          duration: 300,
          easing: "ease"
        });

        animeChiusura.onfinish = () => {
          detail.removeAttribute("open");
        };

      } else {    // È chiuso = si vuole aprire
        detail.classList.add("dtl-aperto");    // Permette animazione fluida della freccia
        detail.setAttribute("open", "");
        
        contenuto.animate({gridTemplateRows: ["0fr", "1fr"]}, {
          duration: 300,
          easing: "ease"
        });
      }
    });
  });

  currentNumRich();
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

      document.getElementById("hidden-id-del").value = `${btn.dataset.id}`;

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

function currentNumRich() {
  const numRich = document.querySelectorAll(".num-rich");
  const status = document.querySelectorAll(".richieste");

  numRich.forEach((num, index) => {
    const currentStat = status[index];

    (parseInt(num.textContent.trim()) == 1) ? currentStat.innerHTML = "nuova richiesta" : null;
  });
}