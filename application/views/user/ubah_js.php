<script type="text/javascript">
$('#simpan_ubah').on('click', function(){
  $("#submit_ubah").click();
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
            data: "bidang_id=<?php echo $data['user']->bidang_id; ?>",
            timeout: 5000,
            success: function(data) {
            	$("#bidang").html(data);
        		$("#bidang").prop('disabled', false);
            	$("#bidang").select2();
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

$('#simpan_ubah_password').click(function(){
  $("#submit_ubah_password").click();
});

$('#form_ubah_password').submit(function() {
	if ($("#password").val() != $("#password2").val()) {
		swal("Error !!!", "Password Tidak Sama !!!", "error");
		return false;
	} else {
		$("#form_ubah_password").submit();			
	}
});
</script>