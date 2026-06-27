document.addEventListener("DOMContentLoaded", function() {
    console.log("Page chargée, filtres initialisés.");
    
    const inputs = document.querySelectorAll('#filterForm select, #filterForm input');
    inputs.forEach(input => {
        input.addEventListener('input', filterMenus);
        input.addEventListener('change', filterMenus);
    });

    filterMenus();
});

function filterMenus() {
    const themeSelect = document.querySelector('select[name="theme"]');
    const prixInput = document.querySelector('input[name="prix_max"]');
    const persInput = document.querySelector('input[name="pers_min"]');
    const allergeneSelect = document.querySelector('select[name="allergene"]');

    if (!themeSelect) return;

    const theme = themeSelect.value;
    const prixMax = prixInput.value ? parseFloat(prixInput.value) : null;
    const persMinReq = persInput.value ? parseInt(persInput.value) : null;
    const allergenePasVoulu = allergeneSelect ? allergeneSelect.value.toLowerCase() : "";

    const items = document.querySelectorAll('#menu-container .menu-item');
    let hasResults = false;

    items.forEach(item => {
        const itemTheme = item.getAttribute('data-theme');
        const itemPrix = parseFloat(item.getAttribute('data-prix'));
        const itemPersMin = parseInt(item.getAttribute('data-pers-min'));
        const itemAllergenes = item.getAttribute('data-allergenes') ? item.getAttribute('data-allergenes').toLowerCase() : "";

        let isVisible = true;

        if (theme !== "" && itemTheme !== theme) isVisible = false;
        if (prixMax !== null && itemPrix > prixMax) isVisible = false;
        if (persMinReq !== null && itemPersMin < persMinReq) isVisible = false;
        
        if (allergenePasVoulu !== "" && itemAllergenes.includes(allergenePasVoulu)) {
            isVisible = false;
        }

        item.style.display = isVisible ? "block" : "none";
        if (isVisible) hasResults = true;
    });

    const noResult = document.getElementById('no-result-message');
    if (noResult) {
        noResult.style.display = hasResults ? "none" : "block";
    }
}