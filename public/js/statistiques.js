// Chargement de l'API Google Charts
google.charts.load('current', {'packages':['corechart', 'bar']});
google.charts.setOnLoadCallback(fetchData);

function fetchData() {
    // Réclamations fréquentes
    fetch('/admin/api/statistiques/reclamations-frequentes')
        .then(response => response.json())
        .then(data => {
            drawReclamationsChart(data);
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données:', error);
            document.getElementById('reclamations_chart').innerHTML = 
                '<div class="alert alert-danger">Erreur lors du chargement des données</div>';
        });
}

function drawReclamationsChart(reclamationsData) {
    if (!reclamationsData || reclamationsData.length === 0) {
        document.getElementById('reclamations_chart').innerHTML = 
            '<div class="alert alert-info">Aucune donnée disponible</div>';
        return;
    }
    
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Type de réclamation');
    data.addColumn('number', 'Nombre');
    
    reclamationsData.forEach(function(item) {
        data.addRow([item.Objet, parseInt(item.count)]);
    });

    var options = {
        title: 'Réclamations les plus fréquentes',
        height: 500,
        is3D: true,
        legend: { position: 'none' },
        colors: ['#3366CC', '#DC3912', '#FF9900', '#109618', '#990099'],
        animation: {
            startup: true,
            duration: 1000,
            easing: 'out'
        },
        hAxis: {
            title: 'Type de réclamation',
            titleTextStyle: {
                color: '#333',
                fontName: 'Arial',
                fontSize: 14,
                bold: true,
                italic: false
            }
        },
        vAxis: { 
            title: 'Nombre de réclamations',
            minValue: 0,
            titleTextStyle: {
                color: '#333',
                fontName: 'Arial',
                fontSize: 14,
                bold: true,
                italic: false
            }
        }
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('reclamations_chart'));
    chart.draw(data, options);
}
