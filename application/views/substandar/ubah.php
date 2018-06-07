<div class="app-title">
  <div>
    <h1><i class="fa fa-users"></i> Substandar</h1>
  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item" data-toggle="tooltip" title="<?php echo $data['versi']->nama . ' (' . $data['versi']->tahun . ')'; ?>" style="cursor: pointer;" onclick="window.location = '<?php echo base_url(); ?>versi'">Standar Akreditasi</li>
    <li class="breadcrumb-item" data-toggle="tooltip" title="<?php echo $data['standar']->nomor . '. ' . $data['standar']->nama; ?>" style="cursor: pointer;" onclick="window.location = '<?php echo base_url(); ?>standar/index/<?php echo $data['versi']->id; ?>'">Standar</li>
    <li class="breadcrumb-item">Substandar</li>
  </ul>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title">Ubah Substandar</h3>
      <div class="tile-body">
        <form method="post" action="<?php echo base_url('substandar/aksi_ubah'); ?>">
          
          <input type="hidden" name="where[id]" value="<?php echo $data['substandar']->id; ?>">
          <input type="hidden" name="data[standar_id]" value="<?php echo $data['standar']->id; ?>">

          <div class="form-group">
            <label class="control-label">Nomor</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text"><?php echo $data['standar']->nomor; ?>.</span></div>
              <input class="form-control" type="number" min="1" max="100" required placeholder="Masukan Nomor" name="data[nomor]" value="<?php echo $data['substandar']->nomor; ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label">Nama</label>
            <input class="form-control" type="text" required placeholder="Masukan Nama" name="data[nama]" value="<?php echo $data['substandar']->nama; ?>">
          </div>
          
          </div>
          <div class="tile-footer">
            <button id="simpan" class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Simpan</button>
            &nbsp;&nbsp;&nbsp;
            <a class="btn btn-secondary" href="<?php echo base_url('substandar/index/' . $data['standar']->id); ?>"><i class="fa fa-fw fa-lg fa-times-circle"></i>Batal</a> <input type="submit" style="visibility: hidden;">
          </div>
        </form>
    </div>
  </div>
</div>