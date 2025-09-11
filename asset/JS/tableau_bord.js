document.addEventListener("DOMContentLoaded", () => {
    fetch('script/dashboard_data.php')
        .then(res => {
            if(!res.ok) throw new Error("Erreur réseau");
            return res.json();
        })
        .then(data => {
            if(data.error) {
            alert("Erreur serveur : " + data.error);
            return;
            }
        document.getElementById('cardUsers').querySelector('strong').textContent = data.total_users;
        document.getElementById('cardCovoits').querySelector('strong').textContent = data.total_covoiturages;
        document.getElementById('cardRevenus').querySelector('strong').textContent = data.revenu_total.toFixed(2) + " €";

        const dates = data.covoits_jour.map(item => item.jour);
        const nbCovoits = data.covoits_jour.map(item => parseInt(item.nombre));

        const jours = data.revenus_jour.map(item => item.jour);
        const revenus = data.revenus_jour.map(item => parseFloat(item.revenu));

        new Chart(document.getElementById('chartCovoit'), {
        type: 'line',
        data: {
          labels: dates,
          datasets: [{
            label: 'Covoiturages',
            data: nbCovoits,
            backgroundColor: 'rgba(0, 128, 0, 0.2)',
            borderColor: '#28a745',
            fill: false,
            tension: 0.1
          }]
        },
        options: {
          scales: { y: { beginAtZero: true } },
          responsive: true
        }
      });

        new Chart(document.getElementById('chartRevenus'), {
        type: 'bar',
        data: {
          labels: jours,
          datasets: [{
            label: 'Revenus (€)',
            data: revenus,
            borderColor: '#28a745',
            backgroundColor: '#0b7e34',
            fill: true,
          }]
        },
        options: {
          scales: { y: { beginAtZero: true } },
          responsive: true
        }
      });
    })
    .catch(e => {
      alert("Erreur lors du chargement des données : " + e.message);
      console.error(e);
    });
});
