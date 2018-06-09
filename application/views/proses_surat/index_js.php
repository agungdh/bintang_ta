<script type="text/javascript">
var table_belum = $('#belum').DataTable( {
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "<?php echo base_url('proses_surat/ajax_kadis_belum'); ?>",
        "type": "POST"
    },
    columnDefs: [
        { targets: [4, 5], orderable: false},
        { targets: [5], width: "25%"}
    ],
    "scrollX": true,
    "autoWidth": false,
    "drawCallback": function(settings) {
       $('.bidang').select2({
       minimumInputLength: 0,
       allowClear: true,
       placeholder: 'Pilih Bidang',
       ajax: {
          dataType: 'json',
          url: '<?php echo base_url('proses_surat/ajax_bidang'); ?>',
          delay: 300,
          data: function(params) {
            return {
              search: params.term
            }
          },
          processResults: function (data, page) {
          return {
            results: data
          };
        },
      }
    });
    },
});

var table_sudah = $('#sudah').DataTable( {
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "<?php echo base_url('proses_surat/ajax_kadis_sudah'); ?>",
        "type": "POST"
    },
    columnDefs: [
        { targets: [4], orderable: false}
    ],
    "scrollX": true,
    "autoWidth": false,
});

$('body').tooltip({selector: '[data-toggle="tooltip"]'});

function disposisi(id, status) {
    var bidang_id = $("#bidang" + id).val();
    // alert(id + ' ' + status + ' ' + bidang_id);
    $.ajax({
        type: "post",
        data: {id: id, status: status, bidang_id: bidang_id}, 
        url: "<?php echo base_url('proses_surat/aksi_disposisi/') ?>",
        timeout: 5000,
        success: function() {
            table_sudah.ajax.reload();
            table_belum.ajax.reload();
        },
        error: function(data) {
            swal('ERROR !!!', 'Terjadi Kesalahan !!!', 'error');
            
            console.log(data)
        }
    });
}
</script>