<?php
global $wpdb;
$input = shortcode_atts( array(
	'id_skpd' => '',
	'tahun_anggaran' => '2022'
), $atts );

if(empty($input['id_skpd'])){
	die('<h1>SKPD tidak ditemukan!</h1>');
}
$sql = $wpdb->prepare("
	select 
		* 
	from data_unit 
	where tahun_anggaran=%d
		and id_skpd =".$input['id_skpd']."
		and active=1
	order by id_skpd ASC
", $input['tahun_anggaran']);
$unit = $wpdb->get_results($sql, ARRAY_A);
$pengaturan = $wpdb->get_results($wpdb->prepare("
	select 
		* 
	from data_pengaturan_sipd 
	where tahun_anggaran=%d
", $input['tahun_anggaran']), ARRAY_A);

$awal_rpjmd = 2018;
$akhir_rpjmd = 2023;
if(!empty($pengaturan)){
	$awal_rpjmd = $pengaturan[0]['awal_rpjmd'];
	$akhir_rpjmd = $pengaturan[0]['akhir_rpjmd'];
}
$urut = $input['tahun_anggaran']-$awal_rpjmd;
$nama_pemda = $pengaturan[0]['daerah'];

$current_user = wp_get_current_user();

$bulan = date('m');
$subkeg = $wpdb->get_results($wpdb->prepare("
		select 
			k.*,
			k.id as id_sub_keg, 
			r.rak,
			r.realisasi_anggaran, 
			r.id as id_rfk, 
			r.realisasi_fisik, 
			r.permasalahan,
			r.catatan_verifikator
		from data_sub_keg_bl k
			left join data_rfk r on k.kode_sbl=r.kode_sbl
				AND k.tahun_anggaran=r.tahun_anggaran
				AND k.id_sub_skpd=r.id_skpd
				AND r.bulan=%d
		where k.tahun_anggaran=%d
			and k.active=1
			and k.id_sub_skpd=%d
		order by kode_sub_giat ASC
	", $bulan, $input['tahun_anggaran'], $unit[0]['id_skpd']), ARRAY_A);
$data_all = array(
	'total' => 0,
	'total_simda' => 0,
	'triwulan_1' => 0,
	'triwulan_2' => 0,
	'triwulan_3' => 0,
	'triwulan_4' => 0,
	'realisasi' => 0,
	'data' => array()
);
foreach ($subkeg as $kk => $sub) {
	$kd = explode('.', $sub['kode_sub_giat']);
	$kd_urusan90 = (int) $kd[0];
	$kd_bidang90 = (int) $kd[1];
	$kd_program90 = (int) $kd[2];
	$kd_kegiatan90 = ((int) $kd[3]).'.'.$kd[4];
	$kd_sub_kegiatan = (int) $kd[5];
	$nama_keg = explode(' ', $sub['nama_sub_giat']);
    unset($nama_keg[0]);
    $nama_keg = implode(' ', $nama_keg);
	$total_simda = $sub['pagu_simda'];
	$realisasi = $sub['realisasi_anggaran'];
	$total_pagu = $sub['pagu'];
	$kode = explode('.', $sub['kode_sbl']);
	$capaian_prog = $wpdb->get_results($wpdb->prepare("
		select 
			* 
		from data_capaian_prog_sub_keg 
		where tahun_anggaran=%d
			and active=1
			and kode_sbl=%s
			and capaianteks != ''
		order by id ASC
	", $input['tahun_anggaran'], $sub['kode_sbl']), ARRAY_A);

	$output_giat = $wpdb->get_results($wpdb->prepare("
		select 
			* 
		from data_output_giat_sub_keg 
		where tahun_anggaran=%d
			and active=1
			and kode_sbl=%s
		order by id ASC
	", $input['tahun_anggaran'], $sub['kode_sbl']), ARRAY_A);

	$output_sub_giat = $wpdb->get_results($wpdb->prepare("
		select 
			* 
		from data_sub_keg_indikator
		where tahun_anggaran=%d
			and active=1
			and kode_sbl=%s
		order by id DESC
	", $input['tahun_anggaran'], $sub['kode_sbl']), ARRAY_A);

	$rfk_all = $wpdb->get_results($wpdb->prepare("
		select 
			realisasi_anggaran,
			bulan
		from data_rfk
		where tahun_anggaran=%d
			and id_skpd=%d
			and kode_sbl=%s
		order by id DESC
	", $input['tahun_anggaran'], $unit[0]['id_skpd'], $sub['kode_sbl']), ARRAY_A);
	$triwulan_1 = 0;
	$triwulan_2 = 0;
	$triwulan_3 = 0;
	$triwulan_4 = 0;
	foreach ($rfk_all as $k => $v) {
		if($v['bulan'] <= 3){
			$triwulan_1 = $v['realisasi_anggaran'];
		}else if($v['bulan'] <= 6){
			$triwulan_2 = $v['realisasi_anggaran']-$triwulan_1;
		}else if($v['bulan'] <= 9){
			$triwulan_3 = $v['realisasi_anggaran']-$triwulan_2;
		}else if($v['bulan'] <= 12){
			$triwulan_4 = $v['realisasi_anggaran']-$triwulan_3;
		}
	}

	if(empty($data_all['data'][$sub['kode_urusan']])){
		$data_all['data'][$sub['kode_urusan']] = array(
			'nama'	=> $sub['nama_urusan'],
			'total' => 0,
			'triwulan_1' => 0,
			'triwulan_2' => 0,
			'triwulan_3' => 0,
			'triwulan_4' => 0,
			'total_simda' => 0,
			'realisasi' => 0,
			'data'	=> array()
		);
	}
	if(empty($data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']])){
		$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']] = array(
			'nama'	=> $sub['nama_bidang_urusan'],
			'total' => 0,
			'triwulan_1' => 0,
			'triwulan_2' => 0,
			'triwulan_3' => 0,
			'triwulan_4' => 0,
			'total_simda' => 0,
			'realisasi' => 0,
			'data'	=> array()
		);
	}
	if(empty($data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']])){
		$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']] = array(
			'nama'	=> $sub['nama_program'],
			'indikator' => $capaian_prog,
			'total' => 0,
			'triwulan_1' => 0,
			'triwulan_2' => 0,
			'triwulan_3' => 0,
			'triwulan_4' => 0,
			'total_simda' => 0,
			'realisasi' => 0,
			'data'	=> array()
		);
	}
	if(empty($data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']])){
		$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']] = array(
			'nama'	=> $sub['nama_giat'],
			'indikator' => $output_giat,
			'total' => 0,
			'triwulan_1' => 0,
			'triwulan_2' => 0,
			'triwulan_3' => 0,
			'triwulan_4' => 0,
			'total_simda' => 0,
			'realisasi' => 0,
			'data'	=> array()
		);
	}
	if(empty($data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['data'][$sub['kode_sub_giat']])){
		$nama = explode(' ', $sub['nama_sub_giat']);
		unset($nama[0]);
		$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['data'][$sub['kode_sub_giat']] = array(
			'nama'	=> implode(' ', $nama),
			'indikator' => $output_sub_giat,
			'total' => 0,
			'triwulan_1' => 0,
			'triwulan_2' => 0,
			'triwulan_3' => 0,
			'triwulan_4' => 0,
			'total_simda' => 0,
			'realisasi' => 0,
			'data'	=> $sub
		);
	}
	$data_all['total'] += $total_pagu;
	$data_all['data'][$sub['kode_urusan']]['total'] += $total_pagu;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['total'] += $total_pagu;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['total'] += $total_pagu;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['total'] += $total_pagu;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['data'][$sub['kode_sub_giat']]['total'] += $total_pagu;

	$data_all['realisasi'] += $realisasi;
	$data_all['data'][$sub['kode_urusan']]['realisasi'] += $realisasi;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['realisasi'] += $realisasi;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['realisasi'] += $realisasi;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['realisasi'] += $realisasi;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['data'][$sub['kode_sub_giat']]['realisasi'] += $realisasi;

	$data_all['total_simda'] += $total_simda;
	$data_all['data'][$sub['kode_urusan']]['total_simda'] += $total_simda;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['total_simda'] += $total_simda;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['total_simda'] += $total_simda;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['total_simda'] += $total_simda;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['data'][$sub['kode_sub_giat']]['total_simda'] += $total_simda;

	$data_all['triwulan_1'] += $triwulan_1;
	$data_all['data'][$sub['kode_urusan']]['triwulan_1'] += $triwulan_1;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['triwulan_1'] += $triwulan_1;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['triwulan_1'] += $triwulan_1;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['triwulan_1'] += $triwulan_1;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['data'][$sub['kode_sub_giat']]['triwulan_1'] += $triwulan_1;

	$data_all['triwulan_2'] += $triwulan_2;
	$data_all['data'][$sub['kode_urusan']]['triwulan_2'] += $triwulan_2;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['triwulan_2'] += $triwulan_2;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['triwulan_2'] += $triwulan_2;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['triwulan_2'] += $triwulan_2;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['data'][$sub['kode_sub_giat']]['triwulan_2'] += $triwulan_2;

	$data_all['triwulan_3'] += $triwulan_3;
	$data_all['data'][$sub['kode_urusan']]['triwulan_3'] += $triwulan_3;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['triwulan_3'] += $triwulan_3;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['triwulan_3'] += $triwulan_3;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['triwulan_3'] += $triwulan_3;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['data'][$sub['kode_sub_giat']]['triwulan_3'] += $triwulan_3;

	$data_all['triwulan_4'] += $triwulan_4;
	$data_all['data'][$sub['kode_urusan']]['triwulan_4'] += $triwulan_4;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['triwulan_4'] += $triwulan_4;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['triwulan_4'] += $triwulan_4;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['triwulan_4'] += $triwulan_4;
	$data_all['data'][$sub['kode_urusan']]['data'][$sub['kode_bidang_urusan']]['data'][$sub['kode_program']]['data'][$sub['kode_giat']]['data'][$sub['kode_sub_giat']]['triwulan_4'] += $triwulan_4;
}

$body_monev = '';
$no_program = 0;
$no_kegiatan = 0;
$no_sub_kegiatan = 0;
foreach ($data_all['data'] as $kd_urusan => $urusan) {
	foreach ($urusan['data'] as $kd_bidang => $bidang) {
		foreach ($bidang['data'] as $kd_program_asli => $program) {
			$no_program++;
			$kd_program = explode('.', $kd_program_asli);
			$kd_program = $kd_program[count($kd_program)-1];
			$capaian = 0;
			if(!empty($program['total_simda'])){
				$capaian = $this->pembulatan(($program['realisasi']/$program['total_simda'])*100);
			}
			$capaian_prog = '';
			$target_capaian_prog = '';
			$satuan_capaian_prog = '';
			if(!empty($program['indikator'])){
				$capaian_prog = $program['indikator'][0]['capaianteks'];
				$target_capaian_prog = $program['indikator'][0]['targetcapaian'];
				$satuan_capaian_prog = $program['indikator'][0]['satuancapaian'];
			}
			$body_monev .= '
				<tr class="program" data-kode="'.$kd_urusan.'.'.$kd_bidang.'.'.$kd_program.'">
		            <td class="kiri kanan bawah text_blok">'.$no_program.'</td>
		            <td class="text_tengah kanan bawah text_blok"></td>
		            <td class="text_tengah kanan bawah text_blok"></td>
		            <td class="kanan bawah text_blok">'.$kd_program_asli.'</td>
		            <td class="kanan bawah text_blok">'.$program['nama'].'</td>
		            <td class="kanan bawah text_blok">'.$capaian_prog.'</td>
		            <td class="text_tengah kanan bawah text_blok total_renstra"></td>
		            <td class="text_tengah kanan bawah text_blok total_renstra">'.$satuan_capaian_prog.'</td>
		            <td class="text_kanan kanan bawah text_blok total_renstra"></td>
		            <td class="text_tengah kanan bawah text_blok realisasi_renstra_tahun_lalu"></td>
		            <td class="text_tengah kanan bawah text_blok realisasi_renstra_tahun_lalu">'.$satuan_capaian_prog.'</td>
		            <td class="text_kanan kanan bawah text_blok realisasi_renstra_tahun_lalu"></td>
		            <td class="text_tengah kanan bawah text_blok total_renja">'.$target_capaian_prog.'</td>
		            <td class="text_tengah kanan bawah text_blok total_renja">'.$satuan_capaian_prog.'</td>
		            <td class="text_kanan kanan bawah text_blok total_renja">'.number_format($program['total_simda'],0,",",".").'</td>
		            <td class="text_tengah kanan bawah text_blok triwulan_1"></td>
		            <td class="text_tengah kanan bawah text_blok triwulan_1">'.$satuan_capaian_prog.'</td>
		            <td class="text_kanan kanan bawah text_blok triwulan_1">'.number_format($program['triwulan_1'],0,",",".").'</td>
		            <td class="text_tengah kanan bawah text_blok triwulan_2"></td>
		            <td class="text_tengah kanan bawah text_blok triwulan_2">'.$satuan_capaian_prog.'</td>
		            <td class="text_kanan kanan bawah text_blok triwulan_2">'.number_format($program['triwulan_2'],0,",",".").'</td>
		            <td class="text_tengah kanan bawah text_blok triwulan_3"></td>
		            <td class="text_tengah kanan bawah text_blok triwulan_3">'.$satuan_capaian_prog.'</td>
		            <td class="text_kanan kanan bawah text_blok triwulan_3">'.number_format($program['triwulan_3'],0,",",".").'</td>
		            <td class="text_tengah kanan bawah text_blok triwulan_4"></td>
		            <td class="text_tengah kanan bawah text_blok triwulan_4">'.$satuan_capaian_prog.'</td>
		            <td class="text_kanan kanan bawah text_blok triwulan_4">'.number_format($program['triwulan_4'],0,",",".").'</td>
		            <td class="text_tengah kanan bawah text_blok realisasi_renja"></td>
		            <td class="text_tengah kanan bawah text_blok realisasi_renja">'.$satuan_capaian_prog.'</td>
		            <td class="text_kanan kanan bawah text_blok realisasi_renja">'.number_format($program['realisasi'],0,",",".").'</td>
		            <td class="text_tengah kanan bawah text_blok capaian_renja">'.$capaian.'</td>
		            <td class="text_kanan kanan bawah text_blok capaian_renja"></td>
		            <td class="text_tengah kanan bawah text_blok realisasi_renstra_tahun_berjalan"></td>
		            <td class="text_tengah kanan bawah text_blok realisasi_renstra_tahun_berjalan">'.$satuan_capaian_prog.'</td>
		            <td class="text_kanan kanan bawah text_blok realisasi_renstra_tahun_berjalan"></td>
		            <td class="text_tengah kanan bawah text_blok capaian_renstra_tahun_berjalan"></td>
		            <td class="text_kanan kanan bawah text_blok capaian_renstra_tahun_berjalan"></td>
	        		<td class="kanan bawah text_blok">'.$unit[0]['nama_skpd'].'</td>
		        </tr>
			';
			foreach ($program['data'] as $kd_giat1 => $giat) {
				$no_kegiatan++;
				$kd_giat = explode('.', $kd_giat1);
				$kd_giat = $kd_giat[count($kd_giat)-2].'.'.$kd_giat[count($kd_giat)-1];
				$nama_page = $input['tahun_anggaran'] . ' | ' . $unit[0]['kode_skpd'] . ' | ' . $kd_giat1 . ' | ' . $giat['nama'];
				$custom_post = get_page_by_title($nama_page, OBJECT, 'post');
				$capaian = 0;
				if(!empty($giat['total_simda'])){
					$capaian = $this->pembulatan(($giat['realisasi']/$giat['total_simda'])*100);
				}
				$output_giat = '';
				$target_output_giat = '';
				$satuan_output_giat = '';
				if(!empty($giat['indikator'])){
					$output_giat = $giat['indikator'][0]['outputteks'];
					$target_output_giat = $giat['indikator'][0]['targetoutput'];
					$satuan_output_giat = $giat['indikator'][0]['satuanoutput'];
				}
				$body_monev .= '
					<tr class="kegiatan" data-kode="'.$kd_urusan.'.'.$kd_bidang.'.'.$kd_program.'.'.$kd_giat.'">
			            <td class="kiri kanan bawah text_blok">'.$no_program.'.'.$no_kegiatan.'</td>
			            <td class="text_tengah kanan bawah text_blok"></td>
			            <td class="text_tengah kanan bawah text_blok"></td>
			            <td class="kanan bawah text_blok">'.$kd_giat1.'</td>
			            <td class="kanan bawah text_blok"><a href="'.get_permalink($custom_post) . '?key=' . $this->gen_key().'" target="_blank">'.$giat['nama'].'</a></td>
			            <td class="kanan bawah text_blok">'.$output_giat.'</td>
			            <td class="text_tengah kanan bawah text_blok total_renstra"></td>
			            <td class="text_tengah kanan bawah text_blok total_renstra">'.$satuan_output_giat.'</td>
			            <td class="text_kanan kanan bawah text_blok total_renstra"></td>
			            <td class="text_tengah kanan bawah text_blok realisasi_renstra_tahun_lalu"></td>
			            <td class="text_tengah kanan bawah text_blok realisasi_renstra_tahun_lalu">'.$satuan_output_giat.'</td>
			            <td class="text_kanan kanan bawah text_blok realisasi_renstra_tahun_lalu"></td>
			            <td class="text_tengah kanan bawah text_blok total_renja">'.$target_output_giat.'</td>
			            <td class="text_tengah kanan bawah text_blok total_renja">'.$satuan_output_giat.'</td>
			            <td class="text_kanan kanan bawah text_blok total_renja">'.number_format($giat['total_simda'],0,",",".").'</td>
			            <td class="text_tengah kanan bawah text_blok triwulan_1"></td>
			            <td class="text_tengah kanan bawah text_blok triwulan_1">'.$satuan_output_giat.'</td>
			            <td class="text_kanan kanan bawah text_blok triwulan_1">'.number_format($giat['triwulan_1'],0,",",".").'</td>
			            <td class="text_tengah kanan bawah text_blok triwulan_2"></td>
			            <td class="text_tengah kanan bawah text_blok triwulan_2">'.$satuan_output_giat.'</td>
			            <td class="text_kanan kanan bawah text_blok triwulan_2">'.number_format($giat['triwulan_2'],0,",",".").'</td>
			            <td class="text_tengah kanan bawah text_blok triwulan_3"></td>
			            <td class="text_tengah kanan bawah text_blok triwulan_3">'.$satuan_output_giat.'</td>
			            <td class="text_kanan kanan bawah text_blok triwulan_3">'.number_format($giat['triwulan_3'],0,",",".").'</td>
			            <td class="text_tengah kanan bawah text_blok triwulan_4"></td>
			            <td class="text_tengah kanan bawah text_blok triwulan_4">'.$satuan_output_giat.'</td>
			            <td class="text_kanan kanan bawah text_blok triwulan_4">'.number_format($giat['triwulan_4'],0,",",".").'</td>
			            <td class="text_tengah kanan bawah text_blok realisasi_renja"></td>
			            <td class="text_tengah kanan bawah text_blok realisasi_renja">'.$satuan_output_giat.'</td>
			            <td class="text_kanan kanan bawah text_blok realisasi_renja">'.number_format($giat['realisasi'],0,",",".").'</td>
			            <td class="text_tengah kanan bawah text_blok capaian_renja">'.$capaian.'</td>
			            <td class="text_kanan kanan bawah text_blok capaian_renja"></td>
			            <td class="text_tengah kanan bawah text_blok realisasi_renstra_tahun_berjalan"></td>
			            <td class="text_tengah kanan bawah text_blok realisasi_renstra_tahun_berjalan">'.$satuan_output_giat.'</td>
			            <td class="text_kanan kanan bawah text_blok realisasi_renstra_tahun_berjalan"></td>
			            <td class="text_tengah kanan bawah text_blok capaian_renstra_tahun_berjalan"></td>
			            <td class="text_kanan kanan bawah text_blok capaian_renstra_tahun_berjalan"></td>
		        		<td class="kanan bawah text_blok">'.$unit[0]['nama_skpd'].'</td>
			        </tr>
				';
				foreach ($giat['data'] as $kd_sub_giat1 => $sub_giat) {
					$no_sub_kegiatan++;
					$kd_sub_giat = explode('.', $kd_sub_giat1);
					$kd_sub_giat = $kd_sub_giat[count($kd_sub_giat)-1];
					$capaian = 0;
					if(!empty($sub_giat['total_simda'])){
						$capaian = $this->pembulatan(($sub_giat['realisasi']/$sub_giat['total_simda'])*100);
					}
					$output_sub_giat = array();
					$target_output_sub_giat = array();
					$satuan_output_sub_giat = array();
					if(!empty($sub_giat['indikator'])){
						foreach ($sub_giat['indikator'] as $k_sub => $v_sub) {
							$output_sub_giat[] = $v_sub['outputteks'];
							$target_output_sub_giat[] = $v_sub['targetoutput'];
							$satuan_output_sub_giat[] = $v_sub['satuanoutput'];
						}
					}
					$output_sub_giat = implode('<br>', $output_sub_giat);
					$target_output_sub_giat = implode('<br>', $target_output_sub_giat);
					$satuan_output_sub_giat = implode('<br>', $satuan_output_sub_giat);
					$body_monev .= '
						<tr class="sub_kegiatan" data-kode="'.$kd_urusan.'.'.$kd_bidang.'.'.$kd_program.'.'.$kd_giat.'.'.$kd_sub_giat.'">
				            <td class="kiri kanan bawah">'.$no_program.'.'.$no_kegiatan.'.'.$no_sub_kegiatan.'</td>
				            <td class="text_tengah kanan bawah"></td>
				            <td class="text_tengah kanan bawah"></td>
				            <td class="kanan bawah">'.$kd_sub_giat1.'</td>
				            <td class="kanan bawah">'.$sub_giat['nama'].'</td>
				            <td class="kanan bawah">'.$output_sub_giat.'</td>
				            <td class="text_tengah kanan bawah total_renstra"></td>
				            <td class="text_tengah kanan bawah total_renstra">'.$satuan_output_sub_giat.'</td>
				            <td class="text_kanan kanan bawah total_renstra"></td>
				            <td class="text_tengah kanan bawah realisasi_renstra_tahun_lalu"></td>
				            <td class="text_tengah kanan bawah realisasi_renstra_tahun_lalu">'.$satuan_output_sub_giat.'</td>
				            <td class="text_kanan kanan bawah realisasi_renstra_tahun_lalu"></td>
				            <td class="text_tengah kanan bawah total_renja">'.$target_output_sub_giat.'</td>
				            <td class="text_tengah kanan bawah total_renja">'.$satuan_output_sub_giat.'</td>
				            <td class="text_kanan kanan bawah total_renja">'.number_format($sub_giat['total_simda'],0,",",".").'</td>
				            <td class="text_tengah kanan bawah triwulan_1"></td>
				            <td class="text_tengah kanan bawah triwulan_1">'.$satuan_output_sub_giat.'</td>
				            <td class="text_kanan kanan bawah triwulan_1">'.number_format($sub_giat['triwulan_1'],0,",",".").'</td>
				            <td class="text_tengah kanan bawah triwulan_2"></td>
				            <td class="text_tengah kanan bawah triwulan_2">'.$satuan_output_sub_giat.'</td>
				            <td class="text_kanan kanan bawah triwulan_2">'.number_format($sub_giat['triwulan_2'],0,",",".").'</td>
				            <td class="text_tengah kanan bawah triwulan_3"></td>
				            <td class="text_tengah kanan bawah triwulan_3">'.$satuan_output_sub_giat.'</td>
				            <td class="text_kanan kanan bawah triwulan_3">'.number_format($sub_giat['triwulan_3'],0,",",".").'</td>
				            <td class="text_tengah kanan bawah triwulan_4"></td>
				            <td class="text_tengah kanan bawah triwulan_4">'.$satuan_output_sub_giat.'</td>
				            <td class="text_kanan kanan bawah triwulan_4">'.number_format($sub_giat['triwulan_4'],0,",",".").'</td>
				            <td class="text_tengah kanan bawah realisasi_renja"></td>
				            <td class="text_tengah kanan bawah realisasi_renja">'.$satuan_output_sub_giat.'</td>
				            <td class="text_kanan kanan bawah realisasi_renja">'.number_format($sub_giat['realisasi'],0,",",".").'</td>
				            <td class="text_tengah kanan bawah capaian_renja">'.$capaian.'</td>
				            <td class="text_kanan kanan bawah capaian_renja"></td>
				            <td class="text_tengah kanan bawah realisasi_renstra_tahun_berjalan"></td>
				            <td class="text_tengah kanan bawah realisasi_renstra_tahun_berjalan">'.$satuan_output_sub_giat.'</td>
				            <td class="text_kanan kanan bawah realisasi_renstra_tahun_berjalan"></td>
				            <td class="text_tengah kanan bawah capaian_renstra_tahun_berjalan"></td>
				            <td class="text_kanan kanan bawah capaian_renstra_tahun_berjalan"></td>
			        		<td class="kanan bawah">'.$unit[0]['nama_skpd'].'</td>
				        </tr>
					';
				}
			}
		}
	}
}
?>
<style type="text/css">
	table th {
		vertical-align: middle;
	}
	body {
		overflow: auto;
	}
</style>
<input type="hidden" value="<?php echo carbon_get_theme_option( 'crb_api_key_extension' ); ?>" id="api_key">
<input type="hidden" value="<?php echo $input['tahun_anggaran']; ?>" id="tahun_anggaran">
<input type="hidden" value="<?php echo $unit[0]['id_skpd']; ?>" id="id_skpd">
<h4 style="text-align: center; margin: 0; font-weight: bold;">Monitoring dan Evaluasi Rencana Kerja <br><?php echo $unit[0]['kode_skpd'].'&nbsp;'.$unit[0]['nama_skpd'].'<br>Tahun '.$input['tahun_anggaran'].' '.$nama_pemda; ?></h4>
<div id="cetak" title="Laporan MONEV RENJA" style="padding: 5px; overflow: auto; height: 80vh;">
	<table cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; font-size: 70%; border: 0; table-layout: fixed;" contenteditable="true">
		<thead>
			<tr>
				<th rowspan="5" style="width: 60px;" class='atas kiri kanan bawah text_tengah text_blok'>No</th>
				<th rowspan="2" style="width: 200px;" class='atas kanan bawah text_tengah text_blok'>Tujuan</th>
				<th rowspan="2" style="width: 200px;" class='atas kanan bawah text_tengah text_blok'>Sasaran</th>
				<th rowspan="2" style="width: 100px;" class='atas kanan bawah text_tengah text_blok'>Kode</th>
				<th rowspan="2" style="width: 300px;" class='atas kanan bawah text_tengah text_blok'>Program, Kegiatan, Sub Kegiatan</th>
				<th rowspan="2" style="width: 200px;" class='atas kanan bawah text_tengah text_blok'>Indikator Kinerja Tujuan, Sasaran, Program(outcome) dan Kegiatan (output), Sub Kegiatan</th>
				<th rowspan="2" colspan="3" style="width: 300px;" class='atas kanan bawah text_tengah text_blok'>Target Renstra SKPD pada Tahun <?php echo $awal_rpjmd; ?> s/d <?php echo $akhir_rpjmd; ?> (periode Renstra SKPD)</th>
				<th rowspan="2" colspan="3" style="width: 300px;" class='atas kanan bawah text_tengah text_blok'>Realisasi Capaian Kinerja Renstra SKPD sampai dengan Renja SKPD Tahun Lalu</th>
				<th rowspan="2" colspan="3" style="width: 300px;" class='atas kanan bawah text_tengah text_blok'>Target kinerja dan anggaran Renja SKPD Tahun Berjalan Tahun <?php echo $input['tahun_anggaran']; ?> yang dievaluasi</th>
				<th colspan="12" style="width: 1200px;" class='atas kanan bawah text_tengah text_blok'>Realisasi Kinerja Pada Triwulan</th>
				<th rowspan="2" colspan="3" style="width: 300px;" class='atas kanan bawah text_tengah text_blok'>Realisasi Capaian Kinerja dan Anggaran Renja SKPD yang dievaluasi</th>
				<th rowspan="2" colspan="2" style="width: 200px;" class='atas kanan bawah text_tengah text_blok'>Tingkat Capaian Kinerja dan Realisasi Anggaran Renja yang dievaluasi (%)</th>
				<th rowspan="2" colspan="3" style="width: 300px;" class='atas kanan bawah text_tengah text_blok'>Realisasi Kinerja dan Anggaran Renstra SKPD s/d Tahun <?php echo $input['tahun_anggaran']; ?> (Akhir Tahun Pelaksanaan Renja SKPD)</th>
				<th rowspan="2" colspan="2" style="width: 200px;" class='atas kanan bawah text_tengah text_blok'>Tingkat Capaian Kinerja dan Realisasi Anggaran Renstra SKPD s/d tahun <?php echo $input['tahun_anggaran']; ?> (%)</th>
				<th rowspan="2" style="width: 200px;" class='atas kanan bawah text_tengah text_blok'>Unit OPD Penanggung Jawab</th>
			</tr>
			<tr>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>I</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>II</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>III</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>VI</th>
			</tr>
			<tr>
				<th rowspan="3" class='atas kanan bawah text_tengah text_blok'>0</th>
				<th rowspan="3" class='atas kanan bawah text_tengah text_blok'>1</th>
				<th rowspan="3" class='atas kanan bawah text_tengah text_blok'>2</th>
				<th rowspan="3" class='atas kanan bawah text_tengah text_blok'>3</th>
				<th rowspan="3" class='atas kanan bawah text_tengah text_blok'>4</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>5</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>6</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>7</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>8</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>9</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>10</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>11</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>12 = 8+9+10+11</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>13 = 12/7x100</th>
				<th colspan="3" class='atas kanan bawah text_tengah text_blok'>14 = 6 + 12</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>15 = 14/5 x100</th>
				<th rowspan="3" class='atas kanan bawah text_tengah text_blok'>16</th>
			</tr>
			<tr>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th colspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>K</th>
				<th rowspan="2" class='atas kanan bawah text_tengah text_blok'>Rp</th>
			</tr>
			<tr>
				<th class='atas kanan bawah text_tengah text_blok'>Volume</th>
				<th class='atas kanan bawah text_tengah text_blok'>Satuan</th>
				<th class='atas kanan bawah text_tengah text_blok'>Volume</th>
				<th class='atas kanan bawah text_tengah text_blok'>Satuan</th>
				<th class='atas kanan bawah text_tengah text_blok'>Volume</th>
				<th class='atas kanan bawah text_tengah text_blok'>Satuan</th>
				<th class='atas kanan bawah text_tengah text_blok'>Volume</th>
				<th class='atas kanan bawah text_tengah text_blok'>Satuan</th>
				<th class='atas kanan bawah text_tengah text_blok'>Volume</th>
				<th class='atas kanan bawah text_tengah text_blok'>Satuan</th>
				<th class='atas kanan bawah text_tengah text_blok'>Volume</th>
				<th class='atas kanan bawah text_tengah text_blok'>Satuan</th>
				<th class='atas kanan bawah text_tengah text_blok'>Volume</th>
				<th class='atas kanan bawah text_tengah text_blok'>Satuan</th>
				<th class='atas kanan bawah text_tengah text_blok'>Volume</th>
				<th class='atas kanan bawah text_tengah text_blok'>Satuan</th>
				<th class='atas kanan bawah text_tengah text_blok'>Volume</th>
				<th class='atas kanan bawah text_tengah text_blok'>Satuan</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $body_monev; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	run_download_excel();
</script>