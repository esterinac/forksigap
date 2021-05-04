<?php
$level              = check_level();
?>
<!-- BREADCUMB,TITLE -->
<header class="page-title-bar mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>"><span class="fa fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('Book_request'); ?>">Pesanan Buku</a>
            </li>
            <li class="breadcrumb-item">
                <a class="text-muted"><?= $book_request->number; ?></a>
            </li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center my-3">
        <div class="page-title mb-0 pb-0 h1"> Pesanan Buku </div>

        <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal"
            data-target="#modal-edit-<?= $book_request->invoice_id; ?>"><i class="fa fa-edit fa-fw"></i> Edit Permintaan
            Buku</button>

    </div>
    <div class="text-left">
        <div class="modal modal-alert fade" id="modal-edit-<?= $book_request->invoice_id; ?>" tabindex="-1"
            role="dialog" aria-labelledby="modal-edit-<?= $book_request->invoice_id; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-pencil-alt text-black mr-1"></i>
                            Edit Status
                        </h5>
                    </div>
                    <form action="<?=base_url('book_request/edit_book_request/')?>" method='post'>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="number" class="font-weight-bold">Nomor Pesanan</label>
                                <input type="text" name="number" id="number" class="form-control"
                                    value=<?=$book_request->number ?> disabled />
                                <input type="hidden" name="invoice_id" id="invoice_id" class="form-control"
                                    value=<?=$book_request->invoice_id ?> />
                            </div>
                            <div class="form-group">
                                <label for="status" class="font-weight-bold">Status Pesanan</label>
                                <?= form_dropdown('status', get_book_request_status_edit(), $book_request->status, 'id="status" class="form-control custom-select d-block" title="Edit Status"'); ?>
                                <small>Status pesanan sekarang =
                                    <?= get_book_request_status()[$book_request->status]; ?></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" id="submit" />
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- BREADCUMB,TITLE -->

<!-- DETAIL -->
<section class="card" id="detail_book_request">
    <header class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link active show" data-toggle="tab" href="#book-request-data-wrapper"><i
                        class="fa fa-info-circle"></i> Detail Pesanan Buku</a>
            </li>
        </ul>
    </header>

    <div class="card-body">
        <div class="tab-content">
            <!-- DATA INFO -->
            <div id="book-request-data-wrapper" class="tab-pane fade active show">
                <div id="book-request">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0 nowrap">
                            <tbody>
                                <tr>
                                    <td width="200px"> Nomor Order </td>
                                    <td>
                                        <?= $book_request->number;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="200px"> Jenis Faktur </td>
                                    <td>
                                        <?= $book_request->type . ' ' . $book_request->source?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="200px"> Tanggal Faktur Masuk Gudang </td>
                                    <td><?= format_datetime($book_request->confirm_date)?>
                                    </td>
                                </tr>
                                <!-- <?php //if($book_request->preparing_start_date) : ?>
                                <tr>
                                    <td width="200px"> Tanggal Mulai Penyiapan Buku </td>
                                    <td><?= format_datetime($book_request->preparing_start_date) ?>
                                    </td>
                                </tr>
                                <?php //endif?>
                                <?php //if($book_request->preparing_deadline) : ?>
                                <tr>
                                    <td width="200px"> Deadline Penyiapan Buku </td>
                                    <td><?= format_datetime($book_request->preparing_deadline) ?>
                                    </td>
                                </tr> -->
                                <?php //endif?>
                                <?php if($book_request->preparing_end_date) : ?>
                                <tr>
                                    <td width="200px"> Tanggal Selesai Penyiapan Buku </td>
                                    <td><?= format_datetime($book_request->preparing_end_date) ?>
                                    </td>
                                </tr>
                                <?php endif?> 
                                <tr>
                                    <td width="200px"> Tanggal Selesai Faktur</td>
                                    <td><?= format_datetime($book_request->finish_date) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="200px"> Status </td>
                                    <td>
                                        <?= get_book_request_status()[$book_request->status]?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="200px"> File Faktur </td>
                                    <td>
                                        <button class="btn btn-info" 
                                        onclick="location.href = '<?= base_url('invoice/generate_pdf/' . $book_request->invoice_id . '/' . $book_request->delivery_fee); ?>'">
                                            <i class="fa fa-download mr-3"></i>Download file faktur
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="200px"> Buku yang dipesan </td>
                                    <td>
                                        <table class="table table-striped mb-0 table-responsive">
                                            <tbody>
                                                <tr class="text-center">
                                                    <th scope="col">No</th>
                                                    <th scope="col" style="width:300px;">Judul Buku</th>
                                                    <th scope="col">Jumlah</th>
                                                </tr>
                                                <?php $i = 0; ?>
                                                <?php foreach ($invoice_books as $invoice_book) : ?>
                                                <?php $i++; ?>
                                                <tr>
                                                    <td class="align-middle text-center pl-4">
                                                        <?= $i ?>
                                                    </td>
                                                    <td>
                                                        <?= $invoice_book->book_title ?>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <?= $invoice_book->qty ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- DATA INFO -->
        </div>
    </div>
</section>
<!-- DETAIL -->

<!-- BOOK PREPARING PROGRESS -->
<?php if(($book_request->type=='cash' && $book_request->source=='library') || $book_request->type=='showroom') : ?>
<?php else : ?>
<?php
$is_preparing_started       = format_datetime($book_request->preparing_start_date);
$is_preparing_finished      = format_datetime($book_request->preparing_end_date);
$is_preparing_deadline_set  = format_datetime($book_request->preparing_deadline);
$staff_gudang               = $this->book_request->get_staff_gudang_by_progress('preparing', $book_request->invoice_id);
?>
<section id="preparing-progress-wrapper" class="card">
    <div id="preparing-progress">
        <header class="card-header">
            <div class="d-flex align-items-center"><span class="mr-auto">Penyiapan Buku</span>
                <?php if(!$is_preparing_finished) :
                    $this->load->view('book_request/view/common/input_staff_modal', [
                    'progress' => 'preparing',
                    'staff_gudang' => $staff_gudang
                    ]);        
                ?>
                <div class="card-header-control">
                    <button id="btn-start-preparing" title="Mulai proses preparing" type="button" class="d-inline btn 
                        <?= !$is_preparing_started ? 'btn-warning' : 'btn-secondary'; ?> <?= ($is_preparing_started || !$is_preparing_deadline_set) ? 'btn-disabled' : ''; ?>
                        " <?= ($is_preparing_started || !$is_preparing_deadline_set) ? 'disabled' : ''; ?>><i
                            class="fas fa-play"></i><span class="d-none d-lg-inline"> Mulai</span></button>
                    <button id="btn-finish-preparing" title="Selesai proses preparing" type="button"
                        class="d-inline btn btn-secondary <?= !$is_preparing_started ? 'btn-disabled' : '' ?>"
                        <?= !$is_preparing_started || $is_preparing_finished ? 'disabled' : '' ?>><i class="fas fa-stop"></i><span
                            class="d-none d-lg-inline"> Selesai</span></button>
                </div>
                <?php endif?>
            </div>
        </header>

        <!-- ALERT -->
        <?php 
            $this->load->view('book_request/view/common/progress_alert');?>

        <div class="list-group list-group-flush list-group-bordered" id="list-group-preparing">
            <div class="list-group-item justify-content-between">
                <span class="text-muted">Status</span>
                <span class="font-weight-bold">
                    <?php if ($book_request->status=='preparing_finish') : ?>
                    <span class="text-success">
                        <i class="fa fa-check"></i>
                        <span>Selesai</span>
                    </span>

                    <?php elseif ($book_request->status=='preparing') : ?>
                    <span class="text-primary">
                        <span>Sedang Diproses</span>
                    </span>
                    <?php endif ?>
                </span>
            </div>

            <div class="list-group-item justify-content-between">
                <span class="text-muted">Tanggal mulai</span>
                <strong>
                    <?= format_datetime($book_request->preparing_start_date); ?></strong>
            </div>
            <div class="list-group-item justify-content-between">
                <?php if (($_SESSION['level'] == 'superadmin' || ($_SESSION['level'] == 'admin_gudang' && empty($book_request->preparing_deadline))) && $staff_gudang && !$is_preparing_finished) : ?>
                <a href="#" id="btn-modal-deadline-preparing" title="Ubah deadline" data-toggle="modal"
                    data-target="#modal-deadline-preparing">Deadline <i class="fas fa-edit fa-fw"></i></a>
                <?php else : ?>
                <span class="text-muted">Deadline</span>
                <?php endif ?>
                <strong><?= format_datetime($book_request->preparing_deadline); ?></strong>
            </div>
            <?php if($staff_gudang) : ?>
            <div class="list-group-item justify-content-between">
                <span class="text-muted">Staff Bertugas</span>
                <strong>
                    <?php foreach ($staff_gudang as $staff) : ?>
                        <span class="badge badge-info p-1"><?= $staff->username; ?></span>
                    <?php endforeach; ?>
                </strong>
                <!-- <?php //if (($_SESSION['level'] == 'superadmin' || ($_SESSION['level'] == 'admin_gudang' && !$book_request->preparing_deadline))) : ?>
                <a href="#" id="btn-modal-staff-preparing" title="Staff Bertugas" data-toggle="modal"
                    data-target="#modal-staff-preparing">Staff Bertugas <i class="fas fa-edit fa-fw"></i></a>
                <?php //else : ?>
                <span class="text-muted">Staff Bertugas</span>
                <?php //endif ?>
                <strong>
                    <span><?//= $book_request->preparing_staff ?></span>
                </strong> -->
            </div>
            <?php endif?>
            <div class="list-group-item justify-content-between">
                <span class="text-muted">Tanggal selesai</span>
                <strong>
                    <?= format_datetime($book_request->preparing_end_date); ?></strong>
            </div>
            <!-- <div class="m-3">
                <div class="text-muted pb-1">Catatan Admin</div>
                <?//= $book_request->preparing_notes_admin ?>
            </div>
 -->
        </div>

        <?php    

            // modal deadline
            $this->load->view('book_request/view/common/deadline_modal', [
                'progress' => 'preparing'
            ]);
            // $this->load->view('book_request/view/common/input_staff_modal');

            // modal action
            // $this->load->view('book_request/view/common/action_modal', [
            //     'progress' => 'preparing',
            // ]);

            // modal note
            // $this->load->view('book_request/view/common/notes_modal', [
            //     'progress' => 'preparing',
            // ]);
            ?>
    </div>
</section>

<script>
$(document).ready(function() {
    const book_request_id = '<?= $book_request->invoice_id ?>'

    // inisialisasi segment
    reload_preparing_segment()

    // ketika load segment, re-initialize call function-nya
    function reload_preparing_segment() {
        $('#preparing-progress-wrapper').load(' #preparing-progress', function() {
            // reinitiate modal after load
            initFlatpickrModal()
        });
    }

    // mulai penyiapan buku
    $('#preparing-progress-wrapper').on('click', '#btn-start-preparing', function() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('book_request/api_start_preparing/'); ?>" + book_request_id,
            datatype: "JSON",
            data: {
                progress: 'preparing'
            },
            success: function(res) {
                showToast(true, res.data);
            },
            error: function(err) {
                showToast(false, err.responseJSON.message);
            },
            complete: function() {
                // reload segmen preparing
                reload_preparing_segment()
                // reload data 
                $('#book-request-data-wrapper').load(' #book-request');
            },
        })
    })

    // selesai penyiapan
    $('#preparing-progress-wrapper').on('click', '#btn-finish-preparing', function() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('book_request/api_finish_preparing/'); ?>" + book_request_id,
            datatype: "JSON",
            data: {
                progress: 'preparing'
            },
            success: function(res) {
                showToast(true, res.data);
            },
            error: function(err) {
                showToast(false, err.responseJSON.message);
            },
            complete: function() {
                // reload segmen preparing
                reload_preparing_segment()
                // reload data
                $('#book-request-data-wrapper').load(' #book-request');
            },
        })
    })
})
</script>
<!-- BOOK PREPARING PROGRESS -->

<?php endif?>