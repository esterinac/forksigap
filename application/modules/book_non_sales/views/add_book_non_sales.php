<header class="page-title-bar">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>"><span class="fa fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('book_non_sales'); ?>">Permintaan Buku Non Penjualan</a>
            </li>
            <li class="breadcrumb-item">
                <a class="text-muted">Form Tambah</a>
            </li>
        </ol>
    </nav>
</header>
<div class="page-section">
    <div class="row">
        <div class="col-md-12">
            <section class="card">
                <div class="card-body">
                    <form id="form_non_sales" action="<?= base_url("book_non_sales/add"); ?>" method="post">
                        <fieldset>
                            <legend>Form Buku Non Penjualan</legend>
                            <!-- <div class="form-group">
                                <label for="number">Nomor Permintaan Non Penjualan<abbr title="Required">*</abbr></label>
                                <input type="text" class="form-control" id="number">
                            </div> -->
                            <div class="form-group">
                                <label for="type">Tipe Permintaan Non Penjualan<abbr title="Required">*</abbr></label>
                                <select name="type" id="type" class="form-control custom-select d-block">
                                    <option value="presentgift">Present Gift</option>
                                    <option value="doorprize">Doorprize</option>
                                </select>
                            </div>
                            <!-- <div class="form-group" id="input-perpustakaan" style="display:none">
                                <label for="library-id">Tujuan Perpustakaan
                                    <abbr title="Required">*</abbr>
                                </label>
                                <?//= form_dropdown('library_id', get_dropdown_list_library(), $input->library_id, 'id="library-id" class="form-control custom-select d-block"'); ?>
                                <?//= form_error('library_id'); ?>
                            </div> -->
                            <div class="row">
                                <div class="form-group col-10 mb-0">
                                    <label for='book-id'>Judul Buku</label>
                                    <?= form_dropdown('book_id', get_dropdown_list_book(), $input->book_id, 'id="book-id" class="form-control custom-select d-block"'); ?>
                                    <?= form_error('book_id'); ?>

                                    <!-- <select class="form-control" name="book_tittle" id="book_tittle" required>
                                        <option value="">No Selected</option>
                                        <?php //foreach($book as $row):?>
                                        <option value="<?php //echo $row->book_id;?>"><?php echo $row->book_tittle;?></option>
                                        <?php //endforeach;?>
                                    </select> -->
                                </div>
                                <div class="form-group col-2 mb-0">
                                    <label for="add-book">Tambah Buku</label>
                                    <button disabled type="button" id="add-book" name="add-book" 
                                    class="form-control btn btn-primary text-white">
                                    Tambah Buku</button>
                                </div>
                                <small id="no-stock-warning" class="form-group col-12 text-danger" style="display:inline"></small>
                            </div>
                            <br>
                            <table class="table table-striped" id="book-list">
		                    	<thead>
		                    		<tr>
		                    			<th style="text-align: center; width:5%">No</th>
		                    			<th style="text-align: center; width:40%">Judul Buku</th>
                                        <th style="text-align: center; width:10%">Penulis</th>
                                        <th style="text-align: center; width:15%">Stok</th>
		                    			<th style="text-align: center; width:15%">Jumlah</th>
		                    			<th style="text-align: center; width:15%">Aksi</th>
		                    		</tr>
		                    	</thead>
		                    	<tbody id="book-list-content">

		                    	</tbody>
		                    </table>
                        </fieldset>
                        <hr>
                            <!-- button -->
                        <input type="submit" class="btn btn-primary ml-auto" value="Submit" />
                        <!-- <a class="btn btn-secondary" href="<?php// echo base_url('book_non_sales') ?>" role="button">Back</a> -->
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#book-id").select2({
        placeholder: '-- Pilih --',
        dropdownParent: $('#app-main')
    });

    // $("#library-id").select2({
    //     placeholder: '-- Pilih --',
    //     dropdownParent: $('#app-main')
    // });

    // $('#destination').change(function(){
    //     if ($("#destination").val()=="library"){
    //         $("#input-perpustakaan").show()
    //     }
    //     else {
    //         $("#input-perpustakaan").hide()
    //     }
    // })
    $('#book-id').change(function(){
        if ($("#book-id").val()){
            $('#add-book').prop('disabled', false)
        }
    })
    $('#add-book').click(function(){
        if ($("#book-id").val()){
            $("#no-stock-warning").html('')
            var number = $("#book-list-content tr").length+1
            var book_name = $('#book-id option:selected').text()
            var book_id = $('#book-id').val()
            $.ajax({
                type: "GET",
                url: "<?= base_url('book_stock/api_get_by_book_id/'); ?>" + book_id,
                datatype: "JSON",
                success: function(res) {
                    if (!res.data || res.data.warehouse_present<1){
                        $("#no-stock-warning").html('Stok buku tersebut kosong')
                        // console.log("Stok kosong")
                    }
                    else {
                        var stock = res.data.warehouse_present
                        var author = res.data.author_name
                        var row1 = "<tr><th style='vertical-align: middle;text-align: center'>"+ number +"</th>"
                        var row2 = "<td style='vertical-align: middle'>" + book_name + "<input type='text' hidden name='book_id' class='book_id' value='"+book_id+"'></td>"
                        var row3 = "<td style='vertical-align: middle'>" + author + "</td>"
                        var row4 = "<td style='vertical-align: middle;text-align: center' class='stock'>"+stock+"</td>"
                        var row5 = "<td style='vertical-align: middle'><input type='number' value=0 min=1 max='"+stock+"'class='form-control quantity' name='quantity'></td>"
                        var row6 = "<td style='vertical-align: middle;text-align: center'></button><button type='button' class='btn btn-danger btn-md remove-book'>Hapus</td></tr>"
                        var html = row1+row2+row3+row4+row5+row6
                        $("#book-list-content").append(html);
                    }
                },
                error: function(err) {
                    console.log(err);
                },
            });
        }
    })
    
    $("#book-list-content").on('click','.remove-book', function(event){
        $(this).closest('tr').remove();
        $("#book-list-content tr").each(function(index){
            $(this).children('th').html(index+1)
        });
    });

    $("#form_non_sales").submit(function(e) {
        e.preventDefault();
        data = {
            'type': $("#type").val(),
            // 'number': $("#number").val(),
            'book_list': []
        }
        $("#book-list-content tr").each(function(){
            book_data = {
                'book_id': $(this).find("input.book_id").val(),
                'qty': Number($(this).find("input.quantity").val())
            }
            data['book_list'].push(book_data)
        })
        console.log(data)
        $.ajax({
            type: "POST",
            url: "<?= base_url("book_non_sales/add"); ?>",
            data: data,
            datatype: 'JSON',
            success: function(result) {
                location.href = "<?= base_url('book_non_sales'); ?>";
                console.log("sukses")
            },
            error: function(req, err) {
                console.log(err)
            }
        });
    })
});
</script>