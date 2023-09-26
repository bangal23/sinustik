<?php 
include "koneksi.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SINUSTIK</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
  </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="dist/img/Sicon_W.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">SINUSTIK</span>
    </a>

   <?php include "sidemenu.php" ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col">
            <h3>
              <?php
              
              #Atur Waktu agar tidak berubah hari ketika diatas jam 00.00
                $waktu = date('Y-m-d H:i:s');
                $target = date('Y-m-d 05:00:00');
                
                if($waktu > $target){
                    $waktu_awal = date('Y-m-d 05:00');
                    $waktu_akhir = date('Y-m-d 05:00',strtotime("+1 days"));
                }else{
                    $waktu_awal = date('Y-m-d 05:00',strtotime("-1 days"));
                    $waktu_akhir = date('Y-m-d 05:00');
                }

              $awalbulan = date('Y-m-01 05:00');
              $awaltahun = date('Y-01-01 05:00');

              if(isset($_GET['hal'])){
                $hal = $_GET['hal'];
                if($hal=="td"){
                  echo "To Date Report <br><h6>($waktu_awal until $waktu_akhir)</h6>";
                }elseif($hal=="mtd"){
                  echo "Month to Date Report <br><h6>($awalbulan until $waktu_akhir)</h6>";
                }elseif($hal=="ytd"){
                  echo "Year to Date Report <br><h6>($awaltahun until $waktu_akhir)</h6>";
                }else{
                  header("Location: ?hal=td");
                }
              }
              ?>
            </h3>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

<?php


if(isset($_GET['hal'])){

  $halaman = $_GET['hal'];
  if($halaman == 'td'){
    $query = "SELECT COUNT(*) as total_kamar, SUM(person) as total_guest, SUM(rate) as total_rate
    FROM tbl_laporan WHERE `check_in` BETWEEN '$waktu_awal' AND '$waktu_akhir'";
    
  }elseif($halaman == 'mtd'){
    $query = "SELECT DATE(check_in) as cekin, COUNT(DATE(check_in)) as total_kamar, SUM(person) as total_guest, SUM(rate) as total_rate 
    FROM tbl_laporan WHERE check_in 
    BETWEEN '$awalbulan' AND '$waktu_akhir' 
    GROUP BY cekin";

  }elseif($halaman == 'ytd'){

    $query = "SELECT MONTH(check_in) as cekin, COUNT(MONTH(check_in)) as total_kamar, SUM(person) as total_guest, SUM(rate) as total_rate 
    FROM tbl_laporan WHERE check_in 
    BETWEEN '$awaltahun' AND '$waktu_akhir' 
    GROUP BY cekin";


  }else{
    header("Location: ?hal=td");
  }

}else{
  header("Location: ?hal=td");
}

@$result = $koneksi->query($query);

$data = array();

if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
// Lakukan sesuatu dengan data yang diambil dari database
    $data[] = $row;
}
} else {
  echo "<p class='text-center'>Tidak ada data ditemukan.</p>";
}

// Jangan lupa untuk menutup koneksi setelah selesai digunakan
$koneksi->close();

if(isset($_GET['hal'])){
  $nocekin = 0;
  $h = $_GET['hal'];

    if($h == 'ytd'){
        foreach($data as $row){
            $cekin[] = Formatbulan($row['cekin']);
            $kamar[] = $row['total_kamar'];
            $guest[] = $row['total_guest'];
            $rate[] = $row['total_rate'];
            
        }
    }
    elseif ($h == 'td'){
        $nocekin = 1;
        foreach($data as $row){
            $kamar[] = $row['total_kamar'];
            $guest[] = $row['total_guest'];
            $rate[] = $row['total_rate'];
        }
    }else{
      foreach($data as $row){
        $cekin[] = $row['cekin'];
        $kamar[] = $row['total_kamar'];
        $guest[] = $row['total_guest'];
        $rate[] = $row['total_rate'];
      }
    }
}
?>


    <!-- Main content -->
    <section class="content">

      <div class="info-box">
        <span class="info-box-icon bg-warning"><i class="fas fa-home"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><h5>Room</h5></span>
          <span class="info-box-number"><h3>
            <?php $t_room = 0; foreach ($data as $row){ $t_room += $row['total_kamar'];} echo titik($t_room); ?>
          </h3></span>
        </div>
        <!-- /.info-box-content -->
      </div>

      <div class="info-box">
        <span class="info-box-icon bg-purple"><i class="fas fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><h5>Guest</h5></span>
          <span class="info-box-number"><h3>
            <?php $t_guest = 0; foreach ($data as $row){ $t_guest += $row['total_guest'];} echo titik($t_guest); ?>
          </h3></span>
        </div>
        <!-- /.info-box-content -->
      </div>

      <div class="info-box">
        <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><h5>Rate</h5></span>
          <span class="info-box-number"><h3>
            <?php $t_rate = 0; foreach ($data as $row){ $t_rate += $row['total_rate'];} echo rupiah($t_rate); ?>
          </h3></span>
        </div>
        <!-- /.info-box-content -->
      </div>

      <?php

        if($hal == "td"){

          #Tidak menampilkan Chart ketika Todate
          
        }else{

      ?>

      <!-- CHART SESSION -->
      <div class="row">

        <div class="col-sm-12">
              <!-- Line chart -->
              <div class="card card-warning collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="far fa-chart-bar"></i>
                    Room Chart
                  </h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>

                </div>
                <div class="card-body">
                  <div>
                    <canvas id="RoomChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                </div>
                <!-- /.card-body-->
              </div>
              <!-- /.card -->
        </div>

          <div class="col-sm-12">
            <!-- Line chart -->
            <div class="card card-purple collapsed-card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Guest Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                  </button>
                </div>

              </div>
              <div class="card-body">
                <div>
                  <canvas id="GuestChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-sm-12">
            <!-- Line chart -->
            <div class="card card-success collapsed-card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Rate Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                  </button>
                </div>

              </div>
              <div class="card-body">
                <div>
                  <canvas id="RateChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->
        </div>

      </div>

      <?php } ?>

    </section>
<!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- ChartJS -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

    var cekin = <?php 
    if($nocekin == 0){
        echo json_encode($cekin);
    }else{
        echo '0';
    } ?>;
    var kamar = <?php echo json_encode($kamar); ?>;
    var guest = <?php echo json_encode($guest); ?>;
    var rate = <?php echo json_encode($rate); ?>;

const rc = document.getElementById('RoomChart');
const gc = document.getElementById('GuestChart');
const rtc = document.getElementById('RateChart');

new Chart(rc, {
  type: 'line',
  data: {
    labels: cekin,
    datasets: [{
      label: 'Room',
      data: kamar,
      borderColor: '#ffc107',
      backgroundColor: '#ffc107',
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

new Chart(gc, {
  type: 'line',
  data: {
    labels: cekin,
    datasets: [{
      label: 'Guest',
      data: guest,
      borderColor: '#6f42c1',
      backgroundColor: '#6f42c1',
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

new Chart(rtc, {
  type: 'line',
  data: {
    labels: cekin,
    datasets: [{
      label: 'Rate',
      data: rate,
      borderColor: '#28a745',
      backgroundColor: '#28a745',
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
</script>

</body>
</html>
