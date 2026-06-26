document.addEventListener("DOMContentLoaded", () => {
    let monGraphique = null;

    // 1. Initialisation de Chart.js
    const ctx = document.getElementById('chartMenus');
    if (ctx && typeof labelsMenus !== 'undefined' && typeof donneesVentes !== 'undefined') {
        monGraphique = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labelsMenus,
                datasets: [{
                    label: 'Nombre de menus vendus',
                    data: donneesVentes,
                    backgroundColor: '#198754',
                    borderColor: '#146c43',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    // 2. Interception du formulaire de filtrage (Ajax)
    // 2. Interception du formulaire de filtrage (Ajax)
    const formFiltre = document.getElementById('formFiltre');
    if (formFiltre) {
        console.log("L'espion JS : Le formulaire a bien été trouvé dans la page !");

        formFiltre.addEventListener('submit', (e) => {
            e.preventDefault(); // Empêche le rechargement physique de la page

            const dateDebut = document.getElementById('date_debut').value;
            const dateFin = document.getElementById('date_fin').value;

            console.log("Clic détecté ! Dates envoyées :", { dateDebut, dateFin });

            // CORRECTION : On pointe vers index.php et on inclut la page dans le JSON pour le routeur
            // Ou on garde l'URL propre si ton routeur lit le GET en même temps que le POST
            fetch('index.php?page=admin-api-filtre', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    date_debut: dateDebut, 
                    date_fin: dateFin 
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erreur HTTP, statut : " + response.status);
                }
                return response.json();
            })
            .then(res => {
                console.log("Réponse brute reçue de la passerelle :", res);

                if (res.status === 'success') {
                    // Mise à jour du Chiffre d'Affaires textuel
                    const affichageCa = document.getElementById('affichage-ca');
                    if (affichageCa) {
                        affichageCa.innerText = res.ca;
                    }

                    // Mise à jour du graphique Chart.js
                    if (monGraphique) {
                        monGraphique.data.labels = res.labels;
                        monGraphique.data.datasets[0].data = res.donnees;
                        monGraphique.update(); // Redessine le graphique avec les nouvelles barres
                        console.log("Graphique mis à jour avec succès !");
                    }
                } else {
                    console.error("Erreur retournée par le PHP:", res.message);
                }
            })
            .catch(err => console.error("Erreur Fetch mécanique:", err));
        });
    } else {
        console.log("L'espion JS : Formulaire INTROUVABLE.");
    }
}); 