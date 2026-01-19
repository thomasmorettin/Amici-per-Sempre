document.addEventListener("DOMContentLoaded", () => {
  loadIndex();
  gestioneFAQs();
})

// Funzione per inizializzazione dell'apertura di tutti i dettagli delle FAQs in homepage
function loadIndex() {
  const allFaqs = document.querySelectorAll("#section-faqs details");
  allFaqs.forEach(detail => { detail.removeAttribute("open", ""); });
}

// Funzione per la gestione di apertura/chisura di tag details per le FAQs in homepage
function gestioneFAQs() {
  const allFaqs = document.querySelectorAll("#section-faqs details");

  allFaqs.forEach(detail => {
    const summary = detail.querySelector("summary");
    const contenuto = detail.querySelector(".faq-answer");

    summary.addEventListener("click", (event) => {
      event.preventDefault();   // Prevenzione dell'azione di default

      if (detail.classList.contains("is-expanded")) {    // È aperto = si vuole chiudere
        detail.classList.remove("is-expanded");    // Permette animazione fluida della freccia
        
        contenuto.addEventListener("transitionend", () => {
          if (!detail.classList.contains("is-expanded")) { detail.removeAttribute("open", ""); }
        }, { once: true } );
      }
      
      else {    // È chiuso = si vuole aprire
        allFaqs.forEach(faq => {
          if (faq != detail && faq.classList.contains("is-expanded")) {
            faq.classList.remove("is-expanded");    // Permette animazione fluida della freccia
            faq.querySelector(".faq-answer").addEventListener("transitionend", () => {
              if (!faq.classList.contains("is-expanded")) { faq.removeAttribute("open"); }
            }, { once: true } );
          }
        });

        detail.setAttribute("open", "");   // Necessario per il calcolo delle dimensioni
        detail.classList.add("is-expanded");

        requestAnimationFrame(() => {
          requestAnimationFrame(() => { detail.classList.add("is-expanded"); });
        });
      }
    })
  })
}