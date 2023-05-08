
<!-- Import Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h1>Bienvenue sur l'application Rayos ChatBot </h1>
<hr>

<!-- BAR CHART -->
<div class="card card-info">
  <div class="card-header">
    <h3 class="card-title">Les 5 questions les plus fréquentes</h3>

    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
      <button type="button" class="btn btn-tool" data-card-widget="remove">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="chart">
      <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
    </div>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "chatbot_db");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch top 5 frequent questions from unanswered table
$questions = $conn->query("SELECT id, question, no_asks FROM unanswered ORDER BY no_asks DESC LIMIT 5");

// Generate labels and data arrays for the chart
$label = array();
$data = array();
while ($row = $questions->fetch_assoc()) {
  $label[] = $row['question'];
  $data[] = $row['no_asks'];
}
?>

<script>
  // Initialize bar chart
  var barChart = new Chart(document.getElementById("barChart"), {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($label); ?>,
      datasets: [{
        label: 'Frequent Asks',
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: <?php echo json_encode($data); ?>
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines: {
            display: false,
          }
        }],
        yAxes: [{
          gridLines: {
            display: false,
          }
        }]
      }
    }
  });
</script>
