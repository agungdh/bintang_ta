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
      <div class="tile-body">
        <div class="tile-title-w-btn">
          <h3 class="title">Data Substandar</h3>
          <p><a class="btn btn-primary icon-btn" href="<?php echo base_url('substandar/tambah/' . $data['standar']->id); ?>"><i class="fa fa-plus"></i>Substandar</a></p>
        </div>
        <table class="table table-hover table-bordered datatable">
          <thead>
            <tr>
              <th>Nomor</th>
              <th>Nama</th>
              <th>Proses</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  </div>
</div>