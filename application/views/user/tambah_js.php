<script type="text/javascript">
$('#simpan').click(function(){
  $("input[type='submit']").click();
});

$('.select2').select2();

$('#level').change(function() {
	cek_bidang();
});

function cek_bidang() {
	if ($("#level").val() == 4) {
		$.ajax({
            type: "get", 
            url: "<?php echo base_url('user/ajax_bidang/') ?>",
            timeout: 5000,
            success: function(data) {
            	$("#bidang").html(data);
        		$("#bidang").prop('disabled', false);
				$("#bidang").prop('selectedIndex', 0).change();
            },
            error: function(data) {
                swal('ERROR !!!', 'Terjadi Kesalahan !!!', 'error');
                
                console.log(data)
            }
        });
	} else {
		$("#bidang").html('');
		$("#bidang").prop('disabled', true);
		$("#bidang").prop('selectedIndex', -1).change();
	}
}

$(function() {
	cek_bidang();
});
</script>