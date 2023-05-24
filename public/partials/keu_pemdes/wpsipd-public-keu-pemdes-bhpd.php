<?php 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
global $wpdb;

$input = shortcode_atts( array(
    'tahun_anggaran' => '2023'
), $atts );

$data = $wpdb->get_results($wpdb->prepare("
    SELECT 
        kecamatan, 
        sum(total) as total 
    from data_bhpd_desa 
    WHERE tahun_anggaran=%d
        and active=1
    group by kecamatan 
    order by kecamatan ASC
", $input['tahun_anggaran']), ARRAY_A);

function generateRandomColor($k){
    $color = array('#f44336', '#9c27b0', '#2196f3', '#009688', '#4caf50', '#cddc39', '#ff9800', '#795548', '#9e9e9e', '#607d8b');
    return $color[$k%10];
}

$body = '';
$desa = array();
$total_all = 0;
$belum_all = 0;
$realisasi_all = 0;
$persen_all = 0;

// grafik kec
$chart_kec = array(
    'label' => array(),
    'label1' => 'Anggaran',
    'label2' => 'Realisasi',
    'data1'  => array(),
    'color1' => array(),
    'data2'  => array(),
    'color2' => array()
);
foreach($data as $i => $val){
    $realisasi = 0;
    $belum_realisasi = $val['total'] - $realisasi;
    if($realisasi == 0){
        $persen = 0;
    }else{
        $persen = ($realisasi/$val['total']) * 100;
    }
    $body .= '
    <tr>
        <td class="text-center">'.($i+1).'</td>
        <td>'.$val['kecamatan'].'</td>
        <td class="text-right">'.number_format($val['total'],0,",",".").'</td>
        <td class="text-right">'.number_format($realisasi,0,",",".").'</td>
        <td class="text-right">'.number_format($belum_realisasi,0,",",".").'</td>
        <td class="text-center">'.$persen.'%</td>
    </tr>
    ';
    $total_all += $val['total'];
    $belum_all += $belum_realisasi;
    $realisasi_all += $realisasi;

    $chart_kec['label'][] = $val['kecamatan'];
    $chart_kec['data1'][] = $val['total'];
    $chart_kec['color1'][] = generateRandomColor(1);
    $chart_kec['data2'][] = $realisasi;
    $chart_kec['color2'][] = generateRandomColor(2);
}
if($realisasi_all == 0){
    $persen_all = 0;
}else{
    $persen_all = ($realisasi_all/$total_all)*100;
}
?>

<h1 class="text-center">Bagi Hasil Pajak Daerah<br>Rekapitulasi Per Kecamatan<br>Tahun <?php echo $input['tahun_anggaran']; ?></h1>
<div class="cetak">
    <div style="padding: 10px;">
        <div class="row">
            <div class="col-md-12">
                <div style="width: 100%; margin:auto 5px;">
                    <canvas id="chart_per_kec"></canvas>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Tabel Bagi Hasil Pajak Daerah<br>Rekapitulasi Per Kecamatan<br>Tahun <?php echo $input['tahun_anggaran']; ?></h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="atas kanan bawah kiri text_tengah text_blok" style="width: 30px;">No</th>
                            <th class="atas kanan bawah text_tengah text_blok" >Kecamatan</th>
                            <th class="atas kanan bawah text_tengah text_blok">Anggaran</th>
                            <th class="atas kanan bawah text_tengah text_blok">Realisasi</th>
                            <th class="atas kanan bawah text_tengah text_blok">Belum Realisasi</th>
                            <th class="atas kanan bawah text_tengah text_blok">% Realisasi</th>
                        </tr>
                    </thead>
                    <tbody><?php echo $body; ?></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">JUMLAH</th>
                            <th class="text-right"><?php echo number_format($total_all,0,",","."); ?></th>
                            <th class="text-right"><?php echo number_format($realisasi_all,0,",","."); ?></th>
                            <th class="text-right"><?php echo number_format($belum_all,0,",","."); ?></th>
                            <th class="text-center"><?php echo $persen_all; ?>%</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
window.kec = <?php echo json_encode($chart_kec); ?>;
window.pieChartkec = new Chart(document.getElementById('chart_per_kec'), {
    type: 'bar',
    data: {
        labels: kec.label,
        datasets: [
            {
                label: kec.label1,
                data: kec.data1,
                backgroundColor: kec.color1
            },
            {
                label: kec.label2,
                data: kec.data2,
                backgroundColor: kec.color2
            }
        ]
    }
});
</script>