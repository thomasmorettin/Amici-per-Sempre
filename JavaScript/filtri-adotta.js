document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle pannello filtri
    const filtraBtn = document.getElementById('filtra-btn');
    const filterPanel = document.querySelector('.filter-panel');
    
    if (filtraBtn && filterPanel) {
        filtraBtn.addEventListener('click', function() {
            filterPanel.classList.toggle('active');
        });
    }
    
    // Gestione accordion
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const accordion = this.parentElement;
            const content = accordion.querySelector('.content');
            
            // Toggle questo accordion
            accordion.classList.toggle('active');
            
            // Anima l'apertura/chiusura
            if (accordion.classList.contains('active')) {
                content.style.maxHeight = content.scrollHeight + 'px';
            } else {
                content.style.maxHeight = '0';
            }
        });
    });
    
    // Gestione slider (peso ed età)
    const sliders = document.querySelectorAll('input[type="range"]');
    
    sliders.forEach(slider => {
        const display = slider.previousElementSibling;
        
        // Aggiorna valore quando muovi lo slider
        slider.addEventListener('input', function() {
            display.value = this.value;
        });
    });
});