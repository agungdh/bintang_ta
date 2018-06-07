<script type="text/javascript">
var table = $('.datatable').DataTable( {
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "<?php echo base_url('prodi/ajax/' . $data['fakultas']->id); ?>",
        "type": "POST"
    },
    columnDefs: [
        { targets: [1], orderable: false}
    ],
    "scrollX": true,
    "autoWidth": false,
});

function hapus(id) {
    swal({
        title: 'Apakah anda yakin?',
        text: "Data akan dihapus!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus!'
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                type: "get", 
                url: "<?php echo base_url('prodi/aksi_hapus/') ?>" + id,
                timeout: 5000,
                success: function() {
                    table.ajax.reload();
                },
                error: function(data) {
                    swal('ERROR !!!', 'Terjadi Kesalahan !!!', 'error');
                    
                    console.log(data)
                }
            });
        }
    });
};

$('body').tooltip({selector: '[data-toggle="tooltip"]'});
</script>