<?php
$date_year          = $this->input->get('date_year');
$invoice_type       = $this->input->get('invoice_type');
$date_month         = $this->input->get('date_month');

$date_year_options = [];

// $date_month_options = [
//     ''  => '- Bulannya Ga Mau Muncul Ini Gimana Ya -',
// ];

for ($dy = intval(date('Y')); $dy >= 2015; $dy--) {
    $date_year_options[$dy] = $dy;
}

$invoice_type_options = [
    ''  => '- Filter Kategori Faktur -',
    'credit' => 'Kredit',
    'cash' => 'Tunai',
    'online' => 'Online',
    'showroom' => 'Showroom'
];
?>

<link
    rel="stylesheet"
    href="<?= base_url('assets/vendor/chart.js/new/Chart.min.css'); ?>"
>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-url/2.5.3/url.js"></script>
<header class="page-title-bar">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>"><span class="fa fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a
                    href="<?= base_url('earning'); ?>"
                    class="text-muted"
                >Pendapatan</a>
            </li>
            <li class="breadcrumb-item active">
                <a class="text-muted">Pendapatan Faktur</a>
            </li>
        </ol>
    </nav>
</header>

<div class="page-section">
    <div class="row">
        <div class="col-12">
            <section class="card card-fluid">
                <div class="card-body p-0">
                    <header class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a
                                    class="nav-link active"
                                    href="<?= base_url('earning/'); ?>"
                                >Pendapatan Faktur</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link"
                                    href="<?= base_url('earning/detail'); ?>"
                                >Detail Pendapatan</a>
                            </li>
                        </ul>
                    </header>
                    <div class="p-3">
                        <?= form_open($pages, ['method' => 'GET']); ?>
                        <div class="row">
                            <div class="col">
                                <?= form_dropdown('date_year', $date_year_options, $date_year, 'id="date_year" class="form-control custom-select d-block" title="Filter Tahun Cetak"'); ?>
                            </div>
                            <div class="col">
                                <?= form_dropdown('invoice_type', $invoice_type_options, $invoice_type, 'id="invoice_type" class="form-control custom-select d-block" title="Filter Tipe Invoice"'); ?>
                            </div>
                            <div class="col">
                                <div
                                    class="btn-group btn-block"
                                    role="group"
                                    aria-label="Filter button"
                                >
                                    <button
                                        class="btn btn-secondary"
                                        type="button"
                                        onclick="location.href = '<?= base_url('earning/'); ?>'"
                                    > Reset</button>
                                    <button
                                        class="btn btn-primary"
                                        type="submit"
                                        value="Submit"
                                    ><i class="fa fa-filter"></i> Filter</button>
                                    <!-- <button
                                        class="btn btn-success"
                                        type="submit"
                                        id="excel"
                                        name="excel"
                                        value="1"
                                    >Excel</button> -->
                                </div>
                            </div>
                        </div>
                        <?= form_close(); ?>
                        <hr>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div
                                class="col-md-12"
                                style="text-align: center;"
                            >
                                <b>
                                    <h5>PENDAPATAN FAKTUR</h5>
                                </b>
                                <!-- <b>
                                    <p><?= $this->input->get('date_year'); ?></p>
                                </b> -->
                            </div>
                            <div class="col-md-12">
                                <canvas id="total_year"></canvas>
                            </div>
                            <!-- <div
                            id="table_laporan"
                            name="table_laporan"
                            style="display:none;"
                        >
                            <hr>
                            <div style="text-align: center;">
                                <b>
                                    <p id="month_year">

                                    </p>
                                </b>
                            </div>
                            <div style="text-align: left;">
                                <b>
                                    <p>LAPORAN JUDUL BUKU YANG BERHASIL DI CETAK</p>
                                </b>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                scope="col"
                                                class="pl-4 align-middle text-center"
                                            >No</th>
                                            <th
                                                scope="col"
                                                class="align-middle text-center"
                                            >Judul Buku</th>
                                            <th
                                                scope="col"
                                                class="align-middle text-center"
                                            >Kategori Cetak</th>
                                            <th
                                                scope="col"
                                                class="align-middle text-center"
                                            >Jumlah Pesanan</th>
                                            <th
                                                scope="col"
                                                class="align-middle text-center"
                                            >Jumlah Hasil Cetak</th>
                                            <th class="align-middle text-center">Ref</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        id="to_fill"
                                        class="align-middle text-center"
                                    >
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>  -->
                        </div>
                    </div>
            </section>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/vendor/chart.js/new/Chart.bundle.min.js'); ?>"></script>

<script>
<?php if ($filter_invoice_type == false) : ?>
    var total_earning_cash = [];
    var total_earning_showroom = [];
    var total_earning_credit = [];
    var total_earning_online = [];
    <?php for ($i = 1; $i <= 12; $i++) { ?>
        total_earning_cash[<?= $i - 1 ?>] = '<?= $total_earning['cash'][$i] ?>'
        total_earning_showroom[<?= $i - 1 ?>] = '<?= $total_earning['showroom'][$i] ?>'
        total_earning_credit[<?= $i - 1 ?>] = '<?= $total_earning['credit'][$i] ?>'
        total_earning_online[<?= $i - 1 ?>] = '<?= $total_earning['online'][$i] ?>'
    <?php } ?>

    var ctx = document.getElementById("total_year").getContext('2d')
    var total_year = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
            datasets: [{
                label: 'Pendapatan Tunai',
                data: total_earning_cash,
                backgroundColor: 'rgba(254, 250, 8, 0.8)',
                borderWidth: 1
            }, {
                label: 'Pendapatan Showroom',
                data: total_earning_showroom,
                backgroundColor: 'rgba(193, 80, 76, 0.8)',
                borderWidth: 1
            }, {
                label: 'Pendapatan Kredit',
                data: total_earning_credit,
                backgroundColor: 'rgba(156, 188, 89, 0.8)',
                borderWidth: 1
            }, {
                label: 'Pendapatan Online',
                data: total_earning_online,
                backgroundColor: 'rgba(77, 126, 177, 0.8)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            },
            legend: {
                position: 'bottom'
            }
        }
    });

<?php else : ?>
    var total_earning = [];
    <?php for ($i = 1; $i <= 12; $i++) { ?>
        total_earning[<?= $i - 1 ?>] = '<?= $total_earning[$filter_invoice_type][$i] ?>'
    <?php } ?>
    var ctx = document.getElementById("total_year").getContext('2d')
    var total_year = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
            datasets: [{
                label: 'Pendapatan "<?= $filter_invoice_type ?>"',
                data: total_earning,
                backgroundColor: 'rgba(7, 146, 187, 0.8)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            },
            legend: {
                position: 'bottom'
            }
        }
    });
<?php endif ?>
</script>
