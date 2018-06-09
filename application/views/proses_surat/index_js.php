<script type="text/javascript">
$('#belum').DataTable( {
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
       placeholder: 'Masukan No Seri',
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

$('#sudah').DataTable( {
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


</script>