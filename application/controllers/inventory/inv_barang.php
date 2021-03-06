<?php
class Inv_barang extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('mst/invbarang_model');
	}
	function permohonan_export(){
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		//[data_tabel.no;block=tbs:row]	[data_tabel.tgl]	[data_tabel.ruangan]	[data_tabel.jumlah]	[data_tabel.keterangan]	[data_tabel.status]
		
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'status_sertifikat_tanggal') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {

					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		if($this->session->userdata('filter_cl_phc') != ''){
			$kodeplch = $this->session->userdata('filter_cl_phc');
			$this->db->where("id_cl_phc",$kodeplch);
		}

		if($this->session->userdata('filterHAPUS') != ''){
			$this->db->where("pilihan_status_invetaris","3");
		}
		if (($this->session->userdata('filterHAPUS') == '') ||($this->session->userdata('filterGIB') != '')) {
				$this->db->where("pilihan_status_invetaris !=","3");
			}	
		$rows_all = $this->inv_barang_model->get_data_golongan_A();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'status_sertifikat_tanggal') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		if($this->session->userdata('filter_cl_phc') != ''){
			$kodeplch = $this->session->userdata('filter_cl_phc');
			$this->db->where("id_cl_phc",$kodeplch);
		}

		if($this->session->userdata('filterHAPUS') != ''){
			$this->db->where("pilihan_status_invetaris","3");
		}
		if (($this->session->userdata('filterHAPUS') == '') ||($this->session->userdata('filterGIB') != '')) {
				$this->db->where("pilihan_status_invetaris !=","3");
			}
		$rows = $this->inv_barang_model->get_data_golongan_A();
		
		$data_tabel = array();
		$no=1;
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'						=> $no++,
				'id_inventaris_barang'   	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'			=> $act->id_mst_inv_barang,
				'uraian'					=> $act->uraian,
				'id_pengadaan'		   		=> $act->id_pengadaan,
				'barang_kembar_proc'		=> $act->barang_kembar_proc,
				'satuan'					=> $act->satuan,
				'id_ruangan'				=> $act->id_ruangan,
				'hak'						=> $act->hak,
				'id_cl_phc'					=> $act->id_cl_phc,
				'register'					=> $act->register,
				'asal_usul'					=> $act->asal_usul,
				'keterangan_pengadaan'		=> $act->keterangan_pengadaan,
				'harga'						=> number_format($act->harga,2),
				'jumlah'					=> $act->jumlah,
				'jumlah_satuan'				=> $act->jumlah.' '.$act->satuan,
				'penggunaan'				=> $act->penggunaan,
				'luas' 						=> $act->luas,
				'alamat' 					=> $act->alamat,
				'pilihan_satuan_barang' 	=> $act->pilihan_satuan_barang,
				'pilihan_status_hak' 		=> $act->pilihan_status_hak,
				'status_sertifikat_tanggal' => date("d-m-Y",strtotime($act->status_sertifikat_tanggal)),
				'status_sertifikat_nomor'	=> $act->status_sertifikat_nomor,
				'pilihan_penggunaan' 		=> $act->pilihan_penggunaan,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		
		if(empty($this->input->post('puskes')) or $this->input->post('puskes') == 'Pilih Puskesmas'){
			$namapus = 'Semua Data Puskesmas';
		}else{
			$namapus = $this->input->post('puskes');
		}
		if(empty($this->input->post('ruang')) or $this->input->post('ruang') == 'Pilih Ruangan'){
			$namaruang = 'Semua Data Ruangan';
		}else{
			$namaruang = $this->input->post('ruang');
		}
		$data_puskesmas[] = array('nama_puskesmas' => $namapus,'nama_puskesmas' => $namaruang);
		$template = dirname(__FILE__).'\..\..\..\public\files\template\inventory\kiba.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = dirname(__FILE__).'\..\..\..\public\files\hasil\hasil_export_'.$code.'.xlsx';
		$TBS->Show(OPENTBS_FILE, $output_file_name); // Also merges all [onshow] automatic fields.
		
		echo base_url().'public/files/hasil/hasil_export_'.$code.'.xlsx' ;
		
	}
	function autocomplite_barang(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("query=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->like("code",$search);
		$this->db->or_like("uraian",$search);
		$this->db->order_by('code','asc');
		$this->db->limit(10,0);
		$query= $this->db->get("mst_inv_barang")->result();
		foreach ($query as $q) {
			$s = array();
			$s[0] = substr($q->code, 0,2);
			$s[1] = substr($q->code, 2,2);
			$s[2] = substr($q->code, 4,2);
			$s[3] = substr($q->code, 6,2);
			$s[4] = substr($q->code, 8,2);
			$barang[] = array(
				'code_tampil' 	=> implode(".", $s), 
				'code' 			=> $q->code , 
				'uraian' 		=> $q->uraian, 
			);
		}
		echo json_encode($barang);
	}
	
	function filter_golongan_invetaris(){
		if($_POST) {
			if($this->input->post('golongan_invetaris') != '') {
				$this->session->set_userdata('filter_golongan_invetaris',$this->input->post('golongan_invetaris'));
				$this->session->set_userdata('filterGIB','');
				$this->session->set_userdata('filterHAPUS','');
				$this->session->set_userdata('filter_cl_phc','');
				$this->session->set_userdata('filterruangan','');
			}else{
				$this->session->set_userdata('filter_golongan_invetaris','');
				$this->session->set_userdata('filterGIB','');
				$this->session->set_userdata('filterHAPUS','');
				$this->session->set_userdata('filter_cl_phc','');
				$this->session->set_userdata('filterruangan','');
			}
		}
	}
	function filterGIB(){
		if($_POST) {
			if($this->input->post('filterGIB_') != '') {
				$this->session->set_userdata('filterGIB',$this->input->post('filterGIB_'));
				$this->session->set_userdata('filterHAPUS','');
			}else{
				$this->session->set_userdata('filterGIB','');
			}
		}
	}
	function get_ruangan_puskesmas(){
		if($_POST) {
			if($this->input->post('idmstinvruangan') != '') {
				$this->session->set_userdata('filterruangan',$this->input->post('idmstinvruangan'));
			}else{
				$this->session->set_userdata('filterruangan','');
			}
		}
	}
	function filterHAPUS(){
		if($_POST) {
			if($this->input->post('filterHAPUS_') != '') {
				$this->session->set_userdata('filterHAPUS',$this->input->post('filterHAPUS_'));
				$this->session->set_userdata('filterGIB','');
			}else{
				$this->session->set_userdata('filterHAPUS','');
			}
		}
	}
	function json(){
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_pengadaan') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_cl_phc') != ''){
			$kodeplch = $this->session->userdata('filter_cl_phc');
			$filter_clphc="JOIN inv_inventaris_distribusi 
                                              ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_distribusi.id_inventaris_barang
                                                 AND inv_inventaris_distribusi.id_cl_phc = \"".$kodeplch."\")";
		}else{
			$filter_clphc='';
		}
		$rows_all = $this->inv_barang_model->get_data($filter_clphc);


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_pengadaan') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		$rows = $this->inv_barang_model->get_data($filter_clphc,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_inventaris_barang'   		=> $act->id_inventaris_barang,
				'id_mst_inv_barang'   			=> $act->id_mst_inv_barang,
				'id_pengadaan'		   			=> $act->id_pengadaan,
				'nama_barang'					=> $act->nama_barang,
				'jumlah'						=> $act->jumlah,
				'harga'							=> number_format($act->harga,2),
				'totalharga'					=> number_format($act->totalharga,2),
				'keterangan_pengadaan'			=> $act->keterangan_pengadaan,
				'pilihan_status_invetaris'		=> $act->pilihan_status_invetaris,
				'barang_kembar_proc'			=> $act->barang_kembar_proc,
				'tanggal_diterima'				=> $act->tanggal_diterima,
				'waktu_dibuat'					=> $act->waktu_dibuat,
				'terakhir_diubah'				=> $act->terakhir_diubah,
				'value'				=> $act->value,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}


		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	
	function index(){
		$this->authentication->verify('inventory','edit');

		$data['title_group'] = "Inventory";
		$data['title_form'] = "Daftar Inventaris Barang";
		$data['kodestatus_inv'] = $this->inv_barang_model->pilih_data_status('status_inventaris');

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,7));
		}else {
			$this->db->like('code','P'.$kodepuskesmas);
		}
		$this->session->set_userdata('filter_cl_phc','');
		$this->session->set_userdata('filterruangan','');
		$data['filter_golongan_invetaris'] = $this->session->userdata('filter_golongan_invetaris');
		$data['filterHAPUS'] = $this->session->userdata('filterHAPUS');
		$data['filterGIB'] = $this->session->userdata('filterGIB');
		$data['datapuskesmas'] 	= $this->inv_barang_model->get_data_puskesmas();
		$data['get_data_tanah'] 	=  array(
											array("0100000000" , "KIB A"),
											array("0200000000" , "KIB B"),
											array("0300000000" , "KIB C"),
											array("0400000000" , "KIB D"),
											array("0500000000" , "KIB E"),
											array("0600000000" , "KIB F")
											);
		$data['content'] = $this->parser->parse("inventory/inv_barang/show",$data,true);
		$this->template->show($data,"home");
	}

	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_inv_ruangan = $this->input->post('id_mst_inv_ruangan');

			$kode 	= $this->inv_ruangan_model->getSelectedData('mst_inv_ruangan',$code_cl_phc)->result();
			
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_cl_phc',$this->input->post('code_cl_phc'));
				$this->session->set_userdata('filterruangan','');
			}else{
				$this->session->set_userdata('filter_cl_phc','');
				$this->session->set_userdata('filterruangan','');
			}
			echo "<option value=\"\">Pilih Ruangan</option>";
			foreach($kode as $kode) :
				echo $select = $kode->id_mst_inv_ruangan == $id_mst_inv_ruangan ? 'selected' : '';
				echo '<option value="'.$kode->id_mst_inv_ruangan.'" '.$select.'>' . $kode->nama_ruangan . '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}
	public function get_nama()
	{
		if($this->input->is_ajax_request()) {
			$code = $this->input->post('code');

			$this->db->where("code",$code);
			$kode 	= $this->invbarang_model->getSelectedData('mst_inv_barang',$code)->row();

			if(!empty($kode)) echo $kode->uraian;

			return TRUE;
		}

		show_404();
	}

	function add(){
		$data['action']			= "add";
        $this->form_validation->set_rules('id_mst_inv_barang', 'Kode Barang', 'trim|required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
        $this->form_validation->set_rules('harga', 'Harga Satuan', 'trim|required');
        $this->form_validation->set_rules('keterangan_pengadaan', 'Keterangan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['kodebarang']		= $this->inv_barang_model->get_databarang();
			$data['notice']			= validation_errors();

			die($this->parser->parse('inventory/inv_barang/form', $data));
		}else{
			$jumlah =$this->input->post('jumlah');
			$id_barang = $this->input->post('id_mst_inv_barang');
			$kode_proc = $this->inv_barang_model->barang_kembar_proc($id_barang);
			for($i=1;$i<=$jumlah;$i++){
				$values = array(
					'id_mst_inv_barang'=> $id_barang,
					'nama_barang' => $this->input->post('nama_barang'),
					'harga' => $this->input->post('harga'),
					'keterangan_pengadaan' => $this->input->post('keterangan_pengadaan'),
					'barang_kembar_proc' => $kode_proc,
					'id_pengadaan' => 0,
				);
				$simpan=$this->db->insert('inv_inventaris_barang', $values);
				$id_= $this->db->insert_id();
			}
			if($simpan==true){
				die("OK|$id_|$kode_proc");
			}else{
				 die("Error|Proses data gagal");
			}
			
		}
	}

	public function edit_barang($id_barang=0,$kd_proc=0,$kd_inventaris=0,$id_pengadaan=0)
	{
		$data['action']			= "edit";
		$data['kode']			= $kd_inventaris;
		$this->form_validation->set_rules('id_mst_inv_barang', 'Kode Barang', 'trim|required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
        $this->form_validation->set_rules('harga', 'Harga Satuan', 'trim|required');
        $this->form_validation->set_rules('keterangan_pengadaan', 'Keterangan', 'trim|required');
      	/*validasi kode barang*/
	    $kodebarang_ = substr($id_barang, 0,2);
	    if($kodebarang_=='01') {
	    	$this->form_validation->set_rules('luas', 'Luas', 'trim|required');
	    	$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_satuan_barang', 'Pilihan Satuan Barang', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_status_hak', 'Pilihan Status Hak', 'trim|required');
	    	$this->form_validation->set_rules('status_sertifikat_tanggal', 'Tanggal Status Sertifikat', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_penggunaan', 'Pilihan Penggunaan', 'trim|required');
	    	$this->form_validation->set_rules('status_sertifikat_nomor', 'Nomor Sertifikat', 'trim|required');
	    }else if($kodebarang_=='02') {	
	    	$this->form_validation->set_rules('merek_type', 'Merek Tipe', 'trim|required');
	    	$this->form_validation->set_rules('identitas_barang', 'Identitas Barang', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_bahan', 'Pilihan Bahan', 'trim|required');
	    	$this->form_validation->set_rules('ukuran_barang', 'Ukuran Barang', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_satuan', 'Pilihan Satuan', 'trim|required');
	    	$this->form_validation->set_rules('tanggal_bpkb', 'Tanggal BPKB', 'trim|required');
	    	$this->form_validation->set_rules('nomor_bpkb', 'Nomor BPKB', 'trim|required');
	    	$this->form_validation->set_rules('no_polisi', 'No Polisi', 'trim|required');
	    	$this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'trim|required');
	    }else if($kodebarang_=='03') {
	    	$this->form_validation->set_rules('luas_lantai', 'Luas Lantai', 'trim|required');
	    	$this->form_validation->set_rules('letak_lokasi_alamat', 'Letak Lokasi Alamat', 'trim|required');
	    	$this->form_validation->set_rules('pillihan_status_hak', 'Pillihan Status Hak', 'trim|required');
	    	$this->form_validation->set_rules('nomor_kode_tanah', 'Nomor Kode Tanah', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_kons_tingkat', 'Pilihan Kontruksi Tingkat', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_kons_beton', 'Pilihan Konstruksi Beton', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_tanggal', 'Tanggal Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_nomor', 'Nomor Dokumen', 'trim|required');
	    }else if($kodebarang_=='04') {
	    	$this->form_validation->set_rules('konstruksi', 'Konstruksi', 'trim|required');
	    	$this->form_validation->set_rules('panjang', 'Panjang', 'trim|required');
	    	$this->form_validation->set_rules('lebar', 'Lebar', 'trim|required');
	    	$this->form_validation->set_rules('luas', 'Luas', 'trim|required');
	    	$this->form_validation->set_rules('letak_lokasi_alamat', 'Lokasi Alamat', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_tanggal', 'Tanggal Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_nomor', 'Nomor Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_status_tanah', 'Pilihan Status Tanah', 'trim|required');
	    	$this->form_validation->set_rules('nomor_kode_tanah', 'Nomor Kode Tanah', 'trim|required');
	    }else if($kodebarang_=='05') {
	    	$this->form_validation->set_rules('buku_judul_pencipta', 'Judul Buku Pencipta', 'trim|required');
	    	$this->form_validation->set_rules('buku_spesifikasi', 'Spesifikasi Buku', 'trim|required');
	    	$this->form_validation->set_rules('budaya_asal_daerah', 'Budaya Asal Daerah', 'trim|required');
	    	$this->form_validation->set_rules('budaya_pencipta', 'Pencipta Budaya', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_budaya_bahan', 'pilihan Budaya Bahan', 'trim|required');
	    	$this->form_validation->set_rules('flora_fauna_jenis', 'Jenis Flora Fauna', 'trim|required');
	    	$this->form_validation->set_rules('flora_fauna_ukuran', 'Ukuran Flora Fauna', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_satuan', 'Pilihan Satuan', 'trim|required');
	    	$this->form_validation->set_rules('tahun_cetak_beli', 'Tahun Cetak Beli', 'trim|required');
	    }else if($kodebarang_=='06') {
	    	$this->form_validation->set_rules('bangunan', 'Bangunan', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_konstruksi_bertingkat', 'Pilihan Konstruksi Bertingkat', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_konstruksi_beton', 'Pilihan Konstruksi Beton', 'trim|required');
	    	$this->form_validation->set_rules('luas', 'Luas', 'trim|required');
	    	$this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_tanggal', 'Tanggal Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_nomor', 'Nomor Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('tanggal_mulai', 'Mulai Tanggal', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_status_tanah', 'Pilihan Status Tanah', 'trim|required');
	    }
		/*end validasi kode barang*/
		if($this->form_validation->run()== FALSE){
			
			
			/*mengirim status pada masing2 form*/

			$kodebarang_ = substr($id_barang, 0,2);
	   		if($kodebarang_=='01') {
	   			$data = $this->inv_barang_model->get_data_barang_edit_table($id_barang,$kd_inventaris,'inv_inventaris_barang_a'); 
	   			$data['pilihan_satuan_barang_']			= $this->inv_barang_model->get_data_pilihan('satuan');
	   			$data['pilihan_status_hak_']			= $this->inv_barang_model->get_data_pilihan('status_hak');
	   			$data['pilihan_penggunaan_']			= $this->inv_barang_model->get_data_pilihan('penggunaan');
	   		}else if($kodebarang_=='02') {
	   			$data = $this->inv_barang_model->get_data_barang_edit_table($id_barang,$kd_inventaris,'inv_inventaris_barang_b'); 
	   			$data['pilihan_bahan_']				= $this->inv_barang_model->get_data_pilihan('bahan');
	   			$data['pilihan_satuan_']				= $this->inv_barang_model->get_data_pilihan('satuan');
	   		}else if($kodebarang_=='03') {
	   			$data = $this->inv_barang_model->get_data_barang_edit_table($id_barang,$kd_inventaris,'inv_inventaris_barang_c'); 
	   			$data['pillihan_status_hak_']		= $this->inv_barang_model->get_data_pilihan('status_hak');
	   			$data['pilihan_kons_tingkat_']		= $this->inv_barang_model->get_data_pilihan('kons_tingkat');
	   			$data['pilihan_kons_beton_']			= $this->inv_barang_model->get_data_pilihan('kons_beton');
	   		}else if($kodebarang_=='04') {
	   			$data = $this->inv_barang_model->get_data_barang_edit_table($id_barang,$kd_inventaris,'inv_inventaris_barang_d'); 
	   			$data['pilihan_status_tanah_']		= $this->inv_barang_model->get_data_pilihan('status_hak');
	   		}else if($kodebarang_=='05') {
	   			$data = $this->inv_barang_model->get_data_barang_edit_table($id_barang,$kd_inventaris,'inv_inventaris_barang_e'); 
	   			$data['pilihan_budaya_bahan_']		= $this->inv_barang_model->get_data_pilihan('bahan');
	   			$data['pilihan_satuan_']				= $this->inv_barang_model->get_data_pilihan('satuan');
   			}else if($kodebarang_=='06') {
   				$data = $this->inv_barang_model->get_data_barang_edit_table($id_barang,$kd_inventaris,'inv_inventaris_barang_f'); 
   				$data['pilihan_konstruksi_bertingkat_']= $this->inv_barang_model->get_data_pilihan('kons_tingkat');
	   			$data['pilihan_konstruksi_beton_']	= $this->inv_barang_model->get_data_pilihan('kons_beton');
	   			$data['pilihan_status_tanah_']		= $this->inv_barang_model->get_data_pilihan('status_hak');
   			}
   			$data['pilihan_asal_usul_']		= $this->inv_barang_model->get_data_pilihan('asal_usul');
   			$data['kodebarang']		= $this->inv_barang_model->get_databarang();
   			$data['kodestatus_inv'] = $this->inv_barang_model->pilih_data_status('status_inventaris');
			$data['action']			= "edit";
			$data['kode']			= $kd_inventaris;
			$data['id_barang']		= $id_barang;
			$data['kd_proc']		= $kd_proc;
			$data['id_pengadaan']		= $id_pengadaan;
			$data['disable']		= "disable";
			$data['notice']			= validation_errors();
   			/*end mengirim status pada masing2 form*/
			die($this->parser->parse('inventory/inv_barang/barang_form_edit', $data));
		}else{
			$jumlah =$this->input->post('jumlah');
			$tanggalterima = explode("/",$this->input->post('tanggal_diterima'));
			$kodebarang_ = substr($id_barang, 0,2);
			$id_barang = $this->input->post('id_mst_inv_barang');
			$kode_proc = $this->inv_barang_model->barang_kembar_proc($id_barang);
			$tanggal_diterima = $tanggalterima[2].'-'.$tanggalterima[1].'-'.$tanggalterima[0];
			$simpan = $this->dodelpermohonan($id_barang,$kd_proc);
			$pilihan_tanah =$this->input->post('pilihan_asal_usul');
			for($i=1;$i<=$jumlah;$i++){
				$id = $this->inv_barang_model->insert_data_from($id_barang,$kode_proc,$tanggal_diterima,$id_pengadaan);
					/*simpan pada bedadatabase*/
		   		if($kodebarang_=='01') {	
		   				$tanggal = explode("/",$this->input->post('status_sertifikat_tanggal'));
		   				$status_sertifikat_tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
		   				$values = array(
						'id_inventaris_barang' 	=> $id,
						'id_mst_inv_barang'		=> $id_barang,
						'luas' 					=> $this->input->post('luas'),
						'alamat' 				=> $this->input->post('alamat'),
						'pilihan_satuan_barang' => $this->input->post('pilihan_satuan_barang'),
						'pilihan_status_hak' 	=> $this->input->post('pilihan_status_hak'),
						'status_sertifikat_tanggal' => $status_sertifikat_tanggal,
						'status_sertifikat_nomor'=> $this->input->post('status_sertifikat_nomor'),
						'pilihan_penggunaan' 	=> $this->input->post('pilihan_penggunaan'),
					);
					$simpan=$this->db->insert('inv_inventaris_barang_a', $values);
		   		}else if($kodebarang_=='02') {
		   			$tanggal = explode("/",$this->input->post('tanggal_bpkb'));
		   			$tanggal_bpkb = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
		   			$tanggal_ = explode("/",$this->input->post('tanggal_perolehan'));
		   			$tanggal_perolehan = $tanggal_[2].'-'.$tanggal_[1].'-'.$tanggal_[0];
		   			$values = array(
						'id_inventaris_barang' 	=> $id,
						'id_mst_inv_barang'		=> $id_barang,
						'merek_type' 			=> $this->input->post('merek_type'),
						'identitas_barang' 		=> $this->input->post('identitas_barang'),
						'pilihan_bahan' 		=> $this->input->post('pilihan_bahan'),
						'ukuran_barang' 		=> $this->input->post('ukuran_barang'),
						'pilihan_satuan' 		=> $this->input->post('pilihan_satuan'),
						'tanggal_bpkb'			=> $tanggal_bpkb,
						'nomor_bpkb'		 	=> $this->input->post('nomor_bpkb'),
						'no_polisi'		 		=> $this->input->post('no_polisi'),
						'tanggal_perolehan'	 	=> $tanggal_perolehan,
					);
					$simpan=$this->db->insert('inv_inventaris_barang_b', $values);
		   		}else if($kodebarang_=='03') {
		   			$tanggal = explode("/",$this->input->post('dokumen_tanggal'));
		   			$dokumen_tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
		   			$values = array(
						'id_inventaris_barang' 	=> $id,
						'id_mst_inv_barang'		=> $id_barang,
						'luas_lantai' 			=> $this->input->post('luas_lantai'),
						'letak_lokasi_alamat' 	=> $this->input->post('letak_lokasi_alamat'),
						'pillihan_status_hak' 	=> $this->input->post('pillihan_status_hak'),
						'nomor_kode_tanah' 		=> $this->input->post('nomor_kode_tanah'),
						'pilihan_kons_tingkat' 	=> $this->input->post('pilihan_kons_tingkat'),
						'pilihan_kons_beton'	=> $this->input->post('pilihan_kons_beton'),
						'dokumen_tanggal'		=> $dokumen_tanggal,
						'dokumen_nomor'		 	=> $this->input->post('dokumen_nomor'),
					);
					$simpan=$this->db->insert('inv_inventaris_barang_c', $values);
		   		}else if($kodebarang_=='04') {
		   			$tanggal = explode("/",$this->input->post('dokumen_tanggal'));
		   			$dokumen_tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
		   			$values = array(
						'id_inventaris_barang' 	=> $id,
						'id_mst_inv_barang'		=> $id_barang,
						'konstruksi' 			=> $this->input->post('konstruksi'),
						'panjang' 				=> $this->input->post('panjang'),
						'lebar' 				=> $this->input->post('lebar'),
						'luas' 					=> $this->input->post('luas'),
						'letak_lokasi_alamat' 	=> $this->input->post('letak_lokasi_alamat'),
						'dokumen_tanggal'		=> $dokumen_tanggal,
						'dokumen_nomor'			=> $this->input->post('dokumen_nomor'),
						'pilihan_status_tanah'	=> $this->input->post('pilihan_status_tanah'),
						'nomor_kode_tanah'		=> $this->input->post('nomor_kode_tanah'),
					);
					$simpan=$this->db->insert('inv_inventaris_barang_d', $values);
		   		}else if($kodebarang_=='05') {
		   			$tanggal = explode("/",$this->input->post('tahun_cetak_beli'));
		   			$tahun_cetak_beli = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
		   			$values = array(
						'id_inventaris_barang' 	=> $id,
						'id_mst_inv_barang'		=> $id_barang,
						'buku_judul_pencipta' 	=> $this->input->post('buku_judul_pencipta'),
						'buku_spesifikasi' 		=> $this->input->post('buku_spesifikasi'),
						'budaya_asal_daerah' 	=> $this->input->post('budaya_asal_daerah'),
						'budaya_pencipta' 		=> $this->input->post('budaya_pencipta'),
						'pilihan_budaya_bahan' 	=> $this->input->post('pilihan_budaya_bahan'),
						'flora_fauna_jenis'		=> $this->input->post('flora_fauna_jenis'),
						'flora_fauna_ukuran'	=> $this->input->post('flora_fauna_ukuran'),
						'pilihan_satuan'		=> $this->input->post('pilihan_satuan'),
						'tahun_cetak_beli'		=> $tahun_cetak_beli,
					);
					$simpan=$this->db->insert('inv_inventaris_barang_e', $values);
				}else if($kodebarang_=='06') {
					$tanggal = explode("/",$this->input->post('tanggal_mulai'));
		   			$tanggal_mulai = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
		   			$tanggal_dokumen = explode("/",$this->input->post('dokumen_tanggal'));
		   			$tanggal_dokumen1 = $tanggal_dokumen[2].'-'.$tanggal_dokumen[1].'-'.$tanggal_dokumen[0];
		   			$values = array(
						'id_inventaris_barang' 	=> $id,
						'id_mst_inv_barang'		=> $id_barang,
						'bangunan' 				=> $this->input->post('bangunan'),
						'pilihan_konstruksi_bertingkat' => $this->input->post('pilihan_konstruksi_bertingkat'),
						'pilihan_konstruksi_beton' 	=> $this->input->post('pilihan_konstruksi_beton'),
						'luas' 					=> $this->input->post('luas'),
						'lokasi' 				=> $this->input->post('lokasi'),
						'dokumen_tanggal'		=> $tanggal_dokumen1,
						'dokumen_nomor'			=> $this->input->post('dokumen_nomor'),
						'tanggal_mulai'			=> $tanggal_mulai,
						'pilihan_status_tanah'	=> $this->input->post('pilihan_status_tanah'),
					);
					$simpan=$this->db->insert('inv_inventaris_barang_f', $values);
				}
				/*end simpan pada bedadatabase form*/
			}
			if($simpan==true){
				die("OK|$id_barang");
			}else{
				 die("Error|Proses data gagal");
			}
		}
		
	}
	function dodel($kode=0){
		$this->authentication->verify('inventory','del');

		if($this->inv_barang_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."inventory/inv_barang");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."inventory/pengadaanbarang");
		}
	}
	function updatestatus_barang(){
		$this->authentication->verify('inventory','edit');
		$this->inv_barang_model->update_status();				
	}
	function dodelpermohonan($id_barang="",$kd_proc=0){
		$this->authentication->verify('inventory','del');

		if($this->inv_barang_model->delete_entryitem($id_barang,$kd_proc)){
				
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}
	
	function dodel_barang($kode=0,$id_barang="",$table=0){
		$this->authentication->verify('inventory','del');

		if($this->inv_barang_model->delete_entryitem_table($kode,$id_barang,$table)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

	
	public function get_autonama() {
        $kode = $this->input->post('code_mst_inv_barang',TRUE); //variabel kunci yang di bawa dari input text id kode
        $query = $this->mkota->get_allkota(); //query model
 
        $kota       =  array();
        foreach ($query as $d) {
            $kota[]     = array(
                'label' => $d->nama_kota, //variabel array yg dibawa ke label ketikan kunci
                'nama' => $d->nama_kota , //variabel yg dibawa ke id nama
                'ibukota' => $d->ibu_kota, //variabel yang dibawa ke id ibukota
                'keterangan' => $d->keterangan //variabel yang dibawa ke id keterangan
            );
        }
        echo json_encode($kota);      //data array yang telah kota deklarasikan dibawa menggunakan json
    }

}
