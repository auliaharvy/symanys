  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6 mt-2">
            <h1 class="m-0 text-dark" style="text-shadow: 2px 2px 4px gray;"><i class="nav-icon fas fa-th"></i></i> <?php echo $judul; ?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home-lg-alt"></i> Home</a></li>
              <li class="breadcrumb-item active"><?php echo $judul; ?></li>
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content animated fadeInUp ">
      <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
          <div class=" col-md-12">
            <div class="card card-info card-outline">
              <div class="card-header col-md-12">

                <a><i class="fa fa-file-search text-info"> </i> Laporan Penggunaan DAS</a>
                <form role="form" action="<?php echo base_url(); ?>laporan/proses_tampil_das_harian" method="post">
                            <div class="row">
                                 <div class="col-md-4">
                                  <div class="form-group">
                                    <label>Tahun Ajaran</label>
                                    <select class="form-control select2" type="text" name="tahun_ajaran" required>
                                            <?php echo $combo_tahun_ajaran; ?>
                                        </select>
                                  </div>
                                </div>
                                <?php if ($this->session->userdata("hak_akses") != 'das') { ?>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Pilih User</label>
                                            <select class="form-control select2" type="text" name="id_user" required>
                                                <?php echo $combo_user; ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label>Tampil Data</label><br>
                                    <button class="btn btn-info"><i class="fa fa-search"> </i> Tampilkan Data</button>
                                </div>
                                </div>
                            </div>
                        </form>
              </div>
              <!-- /.card-header -->
                <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-right mt-2 mr-2">
                        <div class="btn-group btn-group-sm">
                        <a class="btn btn-sm btn-warning" href="<?php echo base_url() . 'laporan/das_harian_excel/' . $id_user . '/' . str_replace("/", "-", $tahun_ajaran); ?>" target="_blank"><i class="fa fa-download"> </i> Export Excel</a>
                        <button class="btn btn-sm btn-danger" onclick="printDiv('cetak')"><i class="fa fa-print"> </i> Cetak Di Web</button>
                    </div>
                    </div>
                    <!-- /.card-header -->
                    <div id="cetak" class="card-body">

                        <center>
                            <h3 style="margin:0;">SMK FARMASICENDIKIA HUSADA</h3>
                            <h3 style="margin:0;">LAPORAN REALISASI ANGGARAN</h3>
                        </center>
                        <br>
                        <?php foreach ($laporan_das->result_array() as $dt) {

                        ?>
                            <div class="row bg-cyan" >
                                <div class="col-md-6">
                                    <p style="margin:0;font-weight:bold;"><?php echo $dt['nama'] . ' - ' . $dt['nama_jabatan']; ?></p>
                                    <p style="margin:0;font-weight:bold;"><?php echo $dt['jenis_dana'] . ' - ' . $dt['tahun_ajaran']; ?></p>
                                    <p style="margin:0;font-weight:bold;">Kode Anggaran : <?php echo $dt['no_das']; ?></p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p style="margin:0;font-weight:bold;">Total Dana : Rp. <?php echo number_format($dt['total_terima']); ?></p>
                                    <p style="margin:0;font-weight:bold;">Terpakai : Rp. <?php echo number_format($dt['total_terima'] - $dt['sisa_saldo']); ?></p>
                                    <p style="margin:0;font-weight:bold;">Sisa Dana : Rp. <?php echo number_format($dt['sisa_saldo']); ?></p>
                                </div>
                            </div>
                            <table class="table table-bordered table-sm text-sm table-responsive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Dana</th>
                                        <th>Rincian Pengeluaran</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Nota </th>
                                        <th>Bku</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $total_pengeluaran = 0;
                                    $q = $this->db->query("SELECT * FROM das_user_output 
                             WHERE id_das_user = '$dt[id_das_user]' ORDER BY id_das_user DESC");
                                    foreach ($q->result_array() as $data) {
                                        $total_pengeluaran += $data['jumlah'] ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $data['jenis_das']; ?></td>
                                            <td><?php echo $data['keterangan']; ?></td>
                                            <td><?php echo date("d-m-Y", strtotime($data['tanggal'])); ?></td>
                                            <td><?php echo 'Rp. ' . number_format($data['jumlah']); ?></td>
                                            <td><?php if ($data['ada_nota'] == '1') echo '<label class="label label-success">Ada</label>';
                                                else echo '<label class="label label-warning">Tidak Ada</label>'; ?></td>
                                            <td><?php if ($data['ada_bku'] == '1') echo '<label class="label label-success">Ada</label>';
                                                else echo '<label class="label label-warning">Tidak Ada</label>'; ?></td>
                                        <?php $no++;
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="font-weight:bold;text-align:center;" colspan="3">Total</td>
                                        <td style="font-weight:bold;">Rp. <?php echo number_format($total_pengeluaran); ?></td>
                                        <td colspan="2"></td>
                                    </tr>
                            </table>
                            <hr />
                        <?php } ?>
                        <br><br>
                        <?php if($this->session->userdata("hak_akses") == "das") { ?>
                        <div class="col-md-6" style="float:left">
                            <p style="margin:0;">&nbsp;</p>

                            <p style="margin:0;">Mengetahui</p>
                            <br><br><br><br><br>
                            <p style="margin:0;">(Bendahara)</p>
                        </div>
                        <div class="col-md-6" style="float:right">
                            <p style="margin:0;">Bekasi, <?php echo tgl_indo(date("Y-m-d")); ?></p>
                            <p style="margin:0;">Penyelenggara Anggaran</p>
                            <br><br><br><br>
                            <p style="margin:0;"><?php echo $this->session->userdata("nama"); ?></p>
                            <p style="margin:0;">(<?php echo $this->session->userdata("nama_jabatan"); ?>)</p>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.box -->
            </div>
            </div>
            <!-- /.col -->
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
  </div>
  </section>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script>
    $('#cari-siswa').typeahead({
        source: function(query, result) {
            $.ajax({
                url: "<?php echo base_url(); ?>pembayaran/ajax_siswa",
                data: 'query=' + query,
                dataType: "json",
                type: "POST",
                success: function(data) {
                    result($.map(data, function(item) {
                        return item;
                    }));
                }
            });
        }
    });
</script>