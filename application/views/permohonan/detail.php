<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<div id="notification">
</div>
<div class="row" style="background:#FAFAFA">
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="<?php if(!isset($_GET['tab'])) echo "active"; ?>"><a href="#tab_1" data-toggle="tab">1. Data Permohonan Sertifikasi</a></li>
      <li class="<?php if(isset($_GET['tab']) && $_GET['tab']=='tab_2') echo "active"; ?>"><a href="#tab_2" data-toggle="tab">2. Daftar Sertifikasi Komoditi</a></li>
    </ul>
    <div class="tab-content">


      <div class="tab-pane <?php if(!isset($_GET['tab'])) echo "active"; ?>" id="tab_1">    
        <div class="row">
          <div class="col-md-6 ">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-info-circle"></i> Informasi Pemohon Sertifikasi</h3>
              </div>
              <div class="box-body">
                <strong>Nama : <?php echo $nama; ?></strong><br>
                Jabatan : <?php echo $jabatan; ?><br>
                Telepon : <?php echo $phone_number; ?><br>
                Email : <?php echo $email; ?><br>
                Alamat : <?php echo $address; ?><br>
                Desa  : <?php echo $desa; ?><br>
                Kecamatan  : <?php echo $kecamatan; ?><br>
                Kabupaten  : <?php echo $kota; ?><br>
              </div>
            </div>
          </div>
          <div class="col-md-6">
              <div class="row">
              <div class="col-md-12">
                <div class="box box-success">
                  <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-check-circle-o"></i> Detail Sertifikat</h3>
                  </div>
                  <div class="box-body">
                  <form name="permohonan-detail" id="permohonan-detail">
                    <div class="form-group">
                      <label>Nomor Permohonan :</label>
                      <input type="text" class="form-control" placeholder="PS.201504.001" name="" value="{nomor_permohonan}" disabled/>
                    </div>
                    <div class="form-group">
                      <label>Tanggal Permohonan :</label>
                      <input type="text" class="form-control" placeholder="Tanggal Permohonan" name="tgl_permohonan"  id="datemask" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value='<?php echo date('d/m/Y')?>' />
                    </div>
                    <div class="form-group">
                      <button type="button" id="btn-save" class="btn btn-warning btn-social"><i class="fa fa-folder-open"></i> Simpan Permohonan</button>
                      <button type="button" id="btn-send" class="btn btn-danger btn-social"><i class="fa fa-check-square-o"></i> Kirim Permohonan</button>
                      <button type="button" id="btn-back" class="btn btn-primary btn-social"><i class="fa fa-reply"></i> Kembali</button>
                    </div>
                    </form>
                  </div>
                </div>

                </div>
              </div>
          </div>
        </div>
        <form action="#" method="post">
         <div class="row">
          <div class="col-md-12">
              <div class="row">
                  <p class="login-box-msg">
                    <ul>
                      <li>Silahkan tentukan tanggal permohonan sertifikasi </li>
                      <li>Simpan draft permohonan sebelum melanjutkan pengisian data sertifikat per komoditi</li>
                      <li>Lengkapi daftar benih yang akan disertifikasi</li>
                      <li>Simpan draft permohonan jika data yang anda input belum lengkap</li>
                      <li>Kirim permohonan jika seluruh data telah anda lengkapi</li>
                      <li>Anda tidak dapat melakukan perubahan setelah mengirim permohonan</li>
                    </ul>
                  </p>
              </div>
          </div>

          </div>
            
        </form>        
      </div>

      <div class="tab-pane <?php if(isset($_GET['tab']) && $_GET['tab']=='tab_2') echo "active"; ?>" id="tab_2">    
        <br>
        <div class="row">
        	<div class="col-md-12">
            <div class="box box-success">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-check-circle-o"></i> Daftar Komoditi</h3>
              </div>
              <form action="<?php echo base_url().'permohonan/delete_komoditi'."/".$kode_permohonan; ?>" method="POST">
              <div class="box-body">
                  <div class="col-md-12 col-md-offset-4">
                      <button type="button" id="btn-addkomoditi" class="btn btn-primary ">Tambah Komoditi</button>
                      <button type="submit" id="btn-delkomoditi" class="btn btn-danger " onClick="if(!confirm('Hapus semua data yang dipilih?')) return false;">Hapus Komoditi</button>
                  </div>
                  <br><br>
                  <table id="dataTable" class="table table-bordered table-hover">
                    <thead>
                      <tr>
            						<th>&nbsp;</th>
            						<th>No</th>
                        <th>Sertifikat</th>
            						<th>Komoditi</th>
                        <th>Varietas</th>
                        <th>Bentuk Benih</th>
                        <th>Jumlah Diajukan</th>
                        <th>Satuan</th>
                        <th>Asal</th>
            						<th>Detail</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $start=1;
                      foreach($komoditi as $row):?>
                        <tr>
                          <td><input type="checkbox" name="komoditi[]" value="<?php  echo $row['kode_permohonan']."__".$row['kode_varietas']."__".$row['kode_komoditi'];?>" /></td>
                          <td><?php  echo $start++; ?>&nbsp;</td>
                          <td><?php  echo $row['sertifikat']; ?>&nbsp;</td>
                          <td><?php  echo $row['komoditi']; ?>&nbsp;</td>
                          <td><?php  echo $row['varietas']; ?>&nbsp;</td>
                          <td><?php  echo $row['bentuk_benih']; ?>&nbsp;</td>
                          <td><?php  echo $row['jml']; ?>&nbsp;</td>
                          <td><?php  echo $row['satuan']; ?>&nbsp;</td>
                          <td><?php  echo $row['asal']; ?>&nbsp;</td>
                          <td align="center"><a class="btn btn-primary btn-xs" href="<?php echo base_url()."permohonan/detail_komoditi/".$row['kode_permohonan']."/".$row['kode_varietas']."/".$row['kode_komoditi'];?>" title="Detail">Detail</a></td>
                        </tr>
                      <?php endforeach;?>    
          				  </tbody>
                  </table>
            </div>
            </form>
          	</div>
        </div>
      </div>
    </div>
  </div><!-- /.form-box -->
</div><!-- /.register-box -->

<script src="<?php echo base_url()?>public/themes/disbun/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>public/themes/disbun/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>public/themes/disbun/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $("#menu_dashboard_permohonan").addClass("active");
    $("#menu_dashboard").addClass("active");

    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    $("#dataTable").dataTable();

    $("#btn-back").click(function(){
    	document.location.href="<?php echo base_url()?>permohonan";
    });
    // $("#btn-save").click(function(){
    //   document.location.href="<?php echo base_url()?>permohonan/permohonan_update/{kode_permohonan}";
    // });
    $("#btn-send").click(function(){
      if (confirm('Anda yakin data telah lengkap? anda tidak dapat mengubah data yang telah dikirim.')) {
        document.location.href="<?php echo base_url()?>permohonan/kirim_permohonan/{kode_permohonan}";
      };
    });

    $('#btn-save').click(function(){
      $.ajax({ 
        type: "POST",
        url: "<?php echo base_url().'permohonan/permohonan_update/{kode_permohonan}';?>",
        data: $('#permohonan-detail').serialize(),
        success: function(response){
          $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
          $('#notification span').html(response);
              $('html, body').animate({
                  scrollTop: $("#top").offset().top
              }, 300);
        }
       });    
    });
    // $('#btn-send').click(function(){
    //   $.ajax({ 
    //     type: "GET",
    //     url: "<?php echo base_url().'permohonan/kirim_permohonan/{kode_permohonan}';?>",
    //     success: function(response){
    //       $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
    //       if (response=="1") {
    //           $('#notification span').html("Permohonan berhasil dikirim");
    //           $('html, body').animate({
    //               scrollTop: $("#top").offset().top
    //           }, 300);
    //           $('#btn-send').addClass('disabled');
    //           $('#btn-send').remove();
    //       } else {
    //           $('#notification span').html(response);
    //           $('html, body').animate({
    //               scrollTop: $("#top").offset().top
    //           }, 300);
    //       }
    //     }
    //    });    
    // });
    

    $("#btn-addkomoditi").click(function(){
      document.location.href="<?php echo base_url()?>permohonan/add_komoditi/{kode_permohonan}";
    });


  });
</script>
