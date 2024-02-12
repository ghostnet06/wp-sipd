<?php

class Wpsipd_Public_RKA
{

    public function input_rka_pendapatan_sipd($atts)
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-input-rka-pendapatan-sipd.php';
    }

    public function input_rka_pembiayaan_sipd($atts)
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-input-rka-pembiayaan-sipd.php';
    }

    public function input_rka_sipd($atts)
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-input-rka-sipd.php';
    }

    public function verifikasi_rka()
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-verifikasi-rka.php';
    }

    public function verifikasi_rka_lokal()
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }

        $tipe_rka = 'rka_lokal';

        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-verifikasi-rka.php';
    }

    public function rekap_longlist_per_jenis_belanja($atts)
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-longlist-per-jenis-belanja.php';
    }
    
    public function apbd_perda_lampiran_4($atts)
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-apbdperda-4.php';
    }

    public function rekap_longlist_per_jenis_belanja_all_skpd($atts)
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-longlist-per-jenis-belanja-all-skpd.php';
    }

    public function user_verikasi_rka()
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-user-verifikasi-rka.php';
    }

    public function user_pptk()
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-user-pptk.php';
    }

    public function nota_pencairan_dana_panjar()
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-nota-pencairan-dana-panjar.php';
    }

    public function daftar_nota_pencairan_dana_panjar()
    {
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-daftar-nota-pencairan-dana-panjar.php';
    }

    public function laporan_panjar_npd($atts)
    {
        // untuk disable render shortcode di halaman edit page/post
        if (!empty($_GET) && !empty($_GET['post'])) {
            return '';
        }
        require_once WPSIPD_PLUGIN_PATH . 'public/partials/penganggaran/wpsipd-public-laporan-panjar-npd.php';
    }

    function tambah_user_verifikator()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil tambah user!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                //validasi tidak boleh kosong
                if (empty($_POST['username'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Username tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['nama'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'nama tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['nomorwa'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'nomorwa tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['email'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'email tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['role'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'role tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['fokus_uraian'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'fokus uraian tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['nama_bidang_skpd'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'nama bidang skpd tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $password = '';
                if (
                    empty($_POST['id_user'])
                    && empty($_POST['password'])
                ) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Password tidak boleh kosong!';
                    die(json_encode($ret));
                } else {
                    $password = $_POST['password'];
                }

                //
                $username = $_POST['username'];
                $nama = $_POST['nama'];
                $nomorwa = $_POST['nomorwa'];
                $email = $_POST['email'];
                $role = $_POST['role'];
                $fokus_uraian = $_POST['fokus_uraian'];
                $nama_bidang = $_POST['nama_bidang_skpd'];

                //validasi input
                if (strlen($username) < 5) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Username harus minimal 5 karakter.';
                    die(json_encode($ret));
                }
                if (!empty($password)) {
                    if (strlen($password) < 8) {
                        $ret['status'] = 'error';
                        $ret['message'] = 'Password harus minimal 8 karakter.';
                        die(json_encode($ret));
                    }
                    if (!preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z\d]/', $password)) {
                        $ret['status'] = 'error';
                        $ret['message'] = 'Password harus mengandung angka dan karakter unik.';
                        die(json_encode($ret));
                    }
                }
                if (!is_email($email)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Format email tidak valid.';
                    die(json_encode($ret));
                }
                if (!preg_match('/^\+62\d{9,15}$/', $nomorwa)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Nomor WhatsApp harus dimulai dengan +62, masukan 9 - 15 karakter!.';
                    die(json_encode($ret));
                }

                //
                if (!empty($_POST['id_user'])) {
                    $insert_user = $_POST['id_user'];
                    $current_user = get_userdata($insert_user);
                    if (empty($current_user)) {
                        $ret['status'] = 'error';
                        $ret['message'] = 'User dengan id=' . $insert_user . ', tidak ditemukan!';
                        die(json_encode($ret));
                    }
                } else {
                    $insert_user = username_exists($username);
                }

                $option = array(
                    'user_login' => $username,
                    'user_pass' => $password,
                    'user_email' => $email,
                    'first_name' => $nama,
                    'display_name' => $nama,
                    'role' => $role
                );
                //proses tambah user
                if (!$insert_user) {
                    $insert_user = wp_insert_user($option);
                    update_user_meta($insert_user, 'nomor_wa', $nomorwa);
                    update_user_meta($insert_user, 'fokus_uraian', $fokus_uraian);
                    update_user_meta($insert_user, 'nama_bidang_skpd', $nama_bidang);

                    if (is_wp_error($insert_user)) {
                        $ret['status'] = 'error';
                        $ret['message'] = $insert_user->get_error_message();
                    } else {
                        $ret['status'] = 'success';
                        $ret['message'] = 'User berhasil ditambahkan.';
                    }
                } else {
                    if (
                        !empty($_POST['id_user'])
                        && ($current_user->user_login == $username
                            || !username_exists($username)
                        )
                    ) {
                        // update password jika password tidak kosong
                        if (
                            empty($password)
                        ) {
                            unset($option['user_pass']);
                        } else {
                            wp_set_password($password, $_POST['id_user']);
                        }

                        // update data meta user
                        $option['ID'] = $_POST['id_user'];
                        wp_update_user($option);

                        // update username jika namanya beda
                        $new_user_login = $username;
                        if ($current_user->user_login != $username) {
                            $wpdb->update(
                                $wpdb->users,
                                array('user_login' => $new_user_login),
                                array('ID' => $_POST['id_user'])
                            );
                        }

                        // update user meta
                        update_user_meta($_POST['id_user'], 'nomor_wa', $nomorwa);
                        update_user_meta($insert_user, 'fokus_uraian', $fokus_uraian);
                        update_user_meta($insert_user, 'nama_bidang_skpd', $nama_bidang);

                        $ret['message'] = 'Berhasil update data!';
                    } else {
                        $ret['status'] = 'error';
                        $ret['message'] = 'Username sudah ada!';
                    }
                }
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function get_user_verifikator()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get user!';
        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                $user_id = um_user('ID');
                $user_meta = get_userdata($user_id);
                $params = $columns = $totalRecords = $data = array();
                $params = $_REQUEST;
                $roles = $this->role_verifikator();
                $args = array(
                    'role__in' => $roles,
                    'orderby' => 'user_nicename',
                    'order'   => 'ASC'
                );

                $users = array();
                // get data user harus login sebagai admin
                if (in_array("administrator", $user_meta->roles)) {
                    if (!empty($params['search']['value'])) {
                        $search_value = sanitize_text_field($params['search']['value']);
                        $args['search'] = "*{$search_value}*";
                        $users = get_users($args);
                    } else {
                        $users = get_users($args);
                    }
                }

                $data_user = array();
                foreach ($users as $recKey => $recVal) {
                    $btn = '<a class="btn btn-sm btn-warning" onclick="edit_data(\'' . $recVal->ID . '\'); return false;" href="#" style="margin-right: 10px;" title="Edit Data">
                    <i class="dashicons dashicons-edit"></i></a>';
                    if (in_array("administrator", $user_meta->roles)) {
                        $btn .= '<a class="btn btn-sm btn-danger" onclick="delete_data(\'' . $recVal->ID . '\'); return false;" href="#" title="Hapus Data">
                    <i class="dashicons dashicons-trash"></i></a>';
                    }
                    $data_user[$recKey]['aksi'] = $btn;
                    $data_user[$recKey]['user'] = $recVal->user_login;
                    $data_user[$recKey]['nama'] = $recVal->display_name;
                    $data_user[$recKey]['nomorwa'] = get_user_meta($recVal->ID, 'nomor_wa');
                    $data_user[$recKey]['nama_bidang_skpd'] = get_user_meta($recVal->ID, 'nama_bidang_skpd');
                    $data_user[$recKey]['fokus_uraian'] = get_user_meta($recVal->ID, 'fokus_uraian');
                    $data_user[$recKey]['role'] = implode(', ', $recVal->roles);
                }

                $json_data = array(
                    "draw"            => intval($params['draw']),
                    "recordsTotal"    => intval(count($data_user)),
                    "recordsFiltered" => intval(count($data_user)),
                    "data"            => $data_user
                );

                die(json_encode($json_data));
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function role_verifikator()
    {
        $daftar_user = $this->get_carbon_multiselect('crb_daftar_user_verifikator');
        $daftar_user_list = array();
        foreach ($daftar_user as $v) {
            $daftar_user_list[] = $v['value'];
        }
        return $daftar_user_list;
    }

    function get_user_verifikator_by_id()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get user by id!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['id'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id user tidak boleh kosong!';
                    die(json_encode($ret));
                }
                $user = get_userdata($_POST['id']);
                if (!empty($user)) {
                    $roles = $this->role_verifikator();
                    $cek_role = false;
                    foreach ($roles as $role) {
                        if (in_array($role, $user->roles)) {
                            $cek_role = true;
                        }
                    }
                    if ($cek_role) {
                        $new_user = array();
                        $new_user['id_user'] = $user->ID;
                        $new_user['user_login'] = $user->data->user_login;
                        $new_user['display_name'] = $user->data->display_name;
                        $new_user['user_email'] = $user->data->user_email;
                        $new_user['nomorwa'] = get_user_meta($user->ID, 'nomor_wa');
                        $new_user['nama_bidang_skpd'] = get_user_meta($user->ID, 'nama_bidang_skpd');
                        $new_user['fokus_uraian'] = get_user_meta($user->ID, 'fokus_uraian');
                        $new_user['roles'] = $user->roles;
                        $ret['data'] = $new_user;
                    } else {
                        $ret['status'] = 'error';
                        $ret['message'] = 'Grup user verifikator tidak ditemukan!';
                    }
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'User tidak ditemukan!';
                }
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function delete_user_verifikator()
    {
        global $wpdb;

        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil hapus data!',
            'data' => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {

                $allowed_roles = $this->role_verifikator();

                $current_user = wp_get_current_user();
                if (!in_array('administrator', $current_user->roles)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Akses ditolak - hanya administrator yang dapat mengakses fitur ini!';
                    die(json_encode($ret));
                }

                if (empty($_POST['id'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id user tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $current_user = get_userdata($_POST['id']);
                if (!empty($current_user)) {
                    $user_roles = $current_user->roles;
                    $is_allowed = false;
                    foreach ($user_roles as $role) {
                        if (in_array($role, $allowed_roles)) {
                            $is_allowed = true;
                            break;
                        }
                    }
                    if ($is_allowed) {
                        if ($current_user->ID) {
                            wp_delete_user($current_user->ID);
                        } else {
                            $ret['status']  = 'error';
                            $ret['message'] = 'User tidak ditemukan!';
                        }
                    } else {
                        $ret['status']  = 'error';
                        $ret['message'] = 'User ini tidak dapat dihapus!';
                    }
                } else {
                    $ret['status']  = 'error';
                    $ret['message'] = 'User tidak ditemukan!';
                }
            } else {
                $ret['status']  = 'error';
                $ret['message'] = 'Api key tidak ditemukan!';
            }
        } else {
            $ret['status']  = 'error';
            $ret['message'] = 'Format Salah!';
        }

        die(json_encode($ret));
    }

    function get_data_verifikasi_rka()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get data verifikasi!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['kode_sbl'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'kode sbl tidak boleh kosong!';
                    die(json_encode($ret));
                } else if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                } else if (empty($_POST['tipe_rka'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tipe RKA tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $kode_sbl = $_POST['kode_sbl'];
                $tahun_anggaran = $_POST['tahun_anggaran'];
                $tipe_rka = $_POST['tipe_rka'];
                $jadwal_terpilih = (!empty($_POST['jadwal_terpilih'])) ? $_POST['jadwal_terpilih'] : 0;
                
                if($tipe_rka == 'rka_lokal'){
                    $prefix = '_lokal';
                    $where_rka = '';
                }else{
                    $prefix = '';
                    $where_rka = '_sipd';
                }
                
                $data_jadwal_terpilih = array();
                if($jadwal_terpilih != 0){
                    $data_jadwal_terpilih = $wpdb->get_row(
                        "SELECT *
                        FROM data_jadwal_lokal
                        WHERE id_jadwal_lokal=".$jadwal_terpilih."
                        AND tahun_anggaran=".$tahun_anggaran
                    ,ARRAY_A);
                }

                $prefix_history = '';
                $where_history = '';
                if(!empty($data_jadwal_terpilih)){
                    if($data_jadwal_terpilih['status'] == 0){
                        $cek_jadwal = $this->validasi_jadwal_perencanaan('verifikasi_rka'.$where_rka,$tahun_anggaran);
                        $jadwal_lokal = $cek_jadwal['data'];
                    }else{
                        $jadwal_lokal = $data_jadwal_terpilih;
                        $prefix_history = '_history';
                        $where_history = ' AND id_jadwal='.$data_jadwal_terpilih['id_jadwal_lokal'];
                    }
                }else{
                    $cek_jadwal = $this->validasi_jadwal_perencanaan('verifikasi_rka'.$where_rka,$tahun_anggaran);
                    $jadwal_lokal = $cek_jadwal['data'];
                }
                $dateTime = new DateTime();
                $time_now = $dateTime->format('Y-m-d H:i:s');
                
                $datas = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                        *
                    FROM data_verifikasi_rka".$prefix."".$prefix_history."
                    WHERE kode_sbl = %s
                        AND tahun_anggaran = %d
                        AND active = 1
                        ".$where_history."
                    ORDER BY update_at DESC", $kode_sbl, $tahun_anggaran), ARRAY_A);
                $new_data = array();
                foreach ($datas as $data) {
                    if (empty($new_data[$data['fokus_uraian']])) {
                        $new_data[$data['fokus_uraian']] = array();
                    }
                    $new_data[$data['fokus_uraian']][] = $data;
                }
                $id_skpd_rka = explode('.', $kode_sbl);
                $id_skpd_rka = $id_skpd_rka[1];

                $current_user = wp_get_current_user();
                $btn_tanggapan = false;
                if (
                    in_array('PA', $current_user->roles)
                    || in_array('KPA', $current_user->roles)
                    || in_array('PLT', $current_user->roles)
                ) {
                    $nipkepala = get_user_meta($current_user->ID, '_nip');
                    $skpd_db = $wpdb->get_results($wpdb->prepare("
                        SELECT 
                            nama_skpd, 
                            id_skpd, 
                            kode_skpd,
                            is_skpd
                        from data_unit 
                        where nipkepala=%s 
                            and tahun_anggaran=%d
                        group by id_skpd", $nipkepala[0], $tahun_anggaran), ARRAY_A);
                    foreach ($skpd_db as $skpd) {
                        if ($skpd['id_skpd'] == $id_skpd_rka) {
                            $btn_tanggapan = true;
                        }
                    }
                } else if (in_array('pptk', $current_user->roles)) {
                    $pptk_sub_keg = $wpdb->get_row($wpdb->prepare("
                        SELECT
                            p.*
                        FROM data_pptk_sub_keg".$prefix."".$prefix_history." p
                        WHERE active=1
                            and tahun_anggaran=%d
                            and kode_sbl=%s
                    ", $tahun_anggaran, $kode_sbl), ARRAY_A);
                    if ($pptk_sub_keg['id_user'] == $current_user->ID) {
                        $btn_tanggapan = true;
                    }
                }

                $ret['html'] = '';
                foreach ($new_data as $key => $data) {
                    foreach ($data as $val) {
                        $ret['html'] .= '
                            <tr>
                                <th>' . $key . '</th>
                                <td>' . $val['catatan_verifikasi'] . '</td>
                                <td>' . $val['nama_verifikator'] . ' ' . $val['update_at'] . '</td>
                                <td>' . $val['tanggapan_opd'] . '</td>
                                <td class="aksi">';
                        
                        if(!empty($jadwal_lokal)){
                            if($time_now > $jadwal_lokal[0]['waktu_awal'] && $time_now <  $jadwal_lokal[0]['waktu_akhir']){
                                // tampilkan tombol verifikator edit dan hapus
                                if ($current_user->ID == $val['id_user']) {
                                    $ret['html'] .= '
                                        <a class="btn btn-sm btn-warning" onclick="edit_data(\'' . $val['id'] . '\'); return false;" href="#" title="Edit Data"><i class="dashicons dashicons-edit"></i></a>';
                                    $ret['html'] .= '
                                        <a class="btn btn-sm btn-danger" onclick="delete_data(\'' . $val['id'] . '\'); return false;" href="#" title="delete data"><i class="dashicons dashicons-trash"></i></a>';
                                }

                                // tampilkan tombol tanggapan untuk PA dan PPTK
                                if ($btn_tanggapan) {
                                    $ret['html'] .= '<a class="btn btn-sm btn-warning" onclick="tambah_tanggapan(\'' . $val['id'] . '\'); return false;" href="#" title="Tanggapi"><i class="dashicons dashicons-editor-quote"></i></a>';
                                }
                            }
                        }

                        $ret['html'] .= '</td></tr>';
                    }
                }
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function tambah_catatan_verifikator()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil tambah data catatan verifikator!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {

                $current_user = wp_get_current_user();
                if ($current_user) {
                    $nama_user = $current_user->display_name;
                    $id_user = $current_user->ID;
                    $nama_bidang = get_user_meta($current_user->ID, 'nama_bidang_skpd', true);
                }

                if (empty($_POST['kode_sbl'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'kode_sbl catatan tidak boleh kosong';
                    die(json_encode($ret));
                }

                if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                }

                if (empty($_POST['catatan_verifikasi'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'catatan verifikasi field harus diisi!';
                    die(json_encode($ret));
                }

                if (empty($_POST['tipe_rka'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tipe rka tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $id_catatan = $_POST['id_catatan'];
                $kode_sbl = $_POST['kode_sbl'];
                $tahun_anggaran = $_POST['tahun_anggaran'];
                $fokus_uraian = $_POST['fokus_uraian'];
                $catatan_verifikasi = $_POST['catatan_verifikasi'];
                $tipe_rka = $_POST['tipe_rka'];

                if($tipe_rka == 'rka_lokal'){
                    $prefix = '_lokal';
                }else{
                    $prefix = '';
                }

                $data = array(
                    'tahun_anggaran' => $tahun_anggaran,
                    'kode_sbl' => $kode_sbl,
                    'id_user' => $id_user,
                    'nama_verifikator' => $nama_user,
                    'fokus_uraian' => $fokus_uraian,
                    'catatan_verifikasi' => $catatan_verifikasi,
                    'active' => 1,
                    'create_at' => current_time('mysql')
                );

                $tabel_verif = 'data_verifikasi_rka'.$prefix;
                if (empty($id_catatan)) {
                    // Insert new data
                    $result = $wpdb->insert($tabel_verif, $data);
                } else {
                    // Update existing data and set update_at
                    $data['update_at'] = current_time('mysql');
                    $result = $wpdb->update($tabel_verif, $data, array('id' => $id_catatan));
                    $ret['message'] = 'Berhasil Update Catatan Verifikasi';
                }

                if ($result !== false) {
                    // Insert or Update data in second table
                    $data_validasi = array(
                        'id_user' => $id_user,
                        'kode_sbl' => $kode_sbl,
                        'nama_bidang' => $nama_bidang,
                        'tahun_anggaran' => $tahun_anggaran
                    );

                    $tabel_validasi = 'data_validasi_verifikasi_rka'.$prefix;

                    $existing_validasi = $wpdb->get_row($wpdb->prepare(
                        "
                        SELECT * 
                        FROM ".$tabel_validasi." 
                        WHERE kode_sbl = %s 
                          AND tahun_anggaran = %d
                          AND id_user = %d",
                        $kode_sbl,
                        $tahun_anggaran,
                        $id_user
                    ));

                    if ($existing_validasi) {
                        // Update existing data
                        $data_validasi['update_at'] = current_time('mysql');
                        $result_validasi = $wpdb->update($tabel_validasi, $data_validasi, array('id' => $existing_validasi->id));
                        if ($result_validasi === false) {
                            $ret['status'] = 'error';
                            $ret['message'] = 'Gagal memperbarui data validasi di database.';
                            die(json_encode($ret));
                        }
                    } else {
                        // Insert new data
                        $result_validasi = $wpdb->insert($tabel_validasi, $data_validasi);
                        if ($result_validasi === false) {
                            $ret['status'] = 'error';
                            $ret['message'] = 'Gagal menambahkan data validasi ke database.';
                            die(json_encode($ret));
                        }
                    }
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = empty($id_catatan) ? 'Gagal menambahkan data ke database.' : 'Gagal memperbarui data di database.';
                    die(json_encode($ret));
                }
            } else {
                $ret['status']  = 'error';
                $ret['message'] = 'Api key tidak valid!';
            }
        } else {
            $ret['status']  = 'error';
            $ret['message'] = 'Format Salah!';
        }

        die(json_encode($ret));
    }

    function tambah_data_tanggapan()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil simpan tanggapan PPTK!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {

                if (empty($_POST['id_catatan'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id catatan tidak boleh kosong';
                    die(json_encode($ret));
                }
                // Validasi user_pptk
                $user_pptk_status = $_POST['user_pptk_status'] ?? ''; // Gunakan null coalescing operator untuk menghindari undefined index
                if (empty($user_pptk_status) || $user_pptk_status == 'User PPTK belum disetting!') {
                    $ret['status'] = 'error';
                    $ret['message'] = 'PPTK belum di set! Harap diset terlebih dahulu!';
                    die(json_encode($ret));
                }
                if (empty($_POST['tanggapan_verifikasi'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tanggapan verifikasi harus diisi!';
                    die(json_encode($ret));
                }
                if (empty($_POST['tipe_rka'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tipe rka harus diisi!';
                    die(json_encode($ret));
                }

                $tipe_rka = $_POST['tipe_rka'];

                if($tipe_rka == 'rka_lokal'){
                    $prefix = '_lokal';
                }else{
                    $prefix = '';
                }
                $current_user = wp_get_current_user();
                $allowed_roles = array('pptk', 'PA', 'KPA', 'PLT');
                // Periksa apakah ada perpotongan antara peran yang diizinkan dan peran pengguna saat ini.
                if (empty(array_intersect($allowed_roles, $current_user->roles))) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
                    die(json_encode($ret));
                }
                $id_catatan = $_POST['id_catatan'];
                $tanggapan_verifikasi = $_POST['tanggapan_verifikasi'];

                // Cek apakah id_catatan ada di database
                $id_tanggapan = $wpdb->get_var($wpdb->prepare("
                    SELECT 
                        id
                    FROM data_verifikasi_rka".$prefix."
                    WHERE id = %d 
                    and active = 1", $id_catatan));

                if ($id_tanggapan) {
                    // Update tabel data_verifikasi_rka
                    $result = $wpdb->update(
                        'data_verifikasi_rka'.$prefix,
                        array(
                            'tanggapan_opd' => $tanggapan_verifikasi,
                            'update_at_tanggapan' => current_time('mysql')
                        ),
                        array('id' => $id_catatan) // WHERE condition
                    );

                    if ($result == false) {
                        $ret['status']  = 'error';
                        $ret['message'] = 'gagal menanggapi catatan verifikasi!';
                        die(json_encode($ret));
                    }
                }
            } else {
                $ret['status']  = 'error';
                $ret['message'] = 'Api key tidak valid!';
            }
        } else {
            $ret['status']  = 'error';
            $ret['message'] = 'Format Salah!';
        }

        die(json_encode($ret));
    }


    function verifikasi_tanpa_catatan()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil verifikasi tanpa catatan!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['kode_sbl'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'kode_sbl catatan tidak boleh kosong';
                    die(json_encode($ret));
                }
                if (empty($_POST['tipe_rka'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tipe rka tidak boleh kosong';
                    die(json_encode($ret));
                }

                $user_id = um_user('ID');
                $user_meta = get_userdata($user_id);
                $roles = $this->role_verifikator();
                $cek_role = false;
                foreach ($roles as $role) {
                    if (in_array($role, $user_meta->roles)) {
                        $cek_role = true;
                    }
                }
                if (!$cek_role) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Anda tidak memiliki hak akses untuk melakukan ini!';
                    die(json_encode($ret));
                }

                $kode_sbl = $_POST['kode_sbl'];
                $tahun_anggaran = $_POST['tahun_anggaran'];
                $tipe_rka = $_POST['tipe_rka'];

                if($tipe_rka == 'rka_lokal'){
                    $prefix = '_lokal';
                }else{
                    $prefix = '';
                }
                
                $data = array(
                    'kode_sbl' => $kode_sbl,
                    'tahun_anggaran' => $tahun_anggaran,
                    'id_user' => $user_id,
                    'nama_bidang' => get_usermeta($user_id, 'nama_bidang_skpd'),
                    'update_at' => current_time('mysql')
                );

                $tabel = 'data_validasi_verifikasi_rka'.$prefix;
                $cek_data = $wpdb->get_var($wpdb->prepare("
                    SELECT
                        id
                    FROM ".$tabel."
                    WHERE kode_sbl = %s
                        AND tahun_anggaran= %d
                ", $kode_sbl, $tahun_anggaran));
                if (empty($cek_data)) {
                    $wpdb->insert($tabel, $data);
                } else {
                    $wpdb->update($tabel, $data, array(
                        'id' => $cek_data
                    ));
                }
            } else {
                $ret['status']  = 'error';
                $ret['message'] = 'Api key tidak valid!';
            }
        } else {
            $ret['status']  = 'error';
            $ret['message'] = 'Format Salah!';
        }

        die(json_encode($ret));
    }


    function get_catatan_verifikasi_by_id()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get catatan verifikasi!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['id'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id catatan tidak boleh kosong';
                    die(json_encode($ret));
                }
                if (empty($_POST['tipe_rka'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tipe rka tidak boleh kosong';
                    die(json_encode($ret));
                }

                $tipe_rka = $_POST['tipe_rka'];

                if($tipe_rka == 'rka_lokal'){
                    $prefix = '_lokal';
                }else{
                    $prefix = '';
                }

                $ret['data'] = $wpdb->get_row($wpdb->prepare('
                    SELECT
                        *
                    FROM data_verifikasi_rka'.$prefix.'
                    WHERE id=%d
                     AND active=1
                ', $_POST['id']), ARRAY_A);
            } else {
                $ret['status']  = 'error';
                $ret['message'] = 'Api key tidak valid!';
            }
        } else {
            $ret['status']  = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function hapus_catatan_verifikasi()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil hapus catatan verifikasi!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['id'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id catatan tidak boleh kosong';
                    die(json_encode($ret));
                }
                if (empty($_POST['tipe_rka'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tipe rka tidak boleh kosong';
                    die(json_encode($ret));
                }

                $tipe_rka = $_POST['tipe_rka'];

                if($tipe_rka == 'rka_lokal'){
                    $prefix = '_lokal';
                }else{
                    $prefix = '';
                }

                $id = intval($_POST['id']);
                $data = $wpdb->get_row($wpdb->prepare("
                    SELECT
                        id_user,
                        kode_sbl,
                        tahun_anggaran
                    FROM data_verifikasi_rka".$prefix."
                    WHERE id = %d", $id));

                if ($data) {
                    // Update tabel data_verifikasi_rka
                    $result = $wpdb->update(
                        'data_verifikasi_rka'.$prefix,
                        array('active' => 0),
                        array('id' => $id)
                    );

                    if ($result !== false) {
                        // Update tabel data_validasi_verifikasi_rka
                        $result_validasi = $wpdb->update(
                            'data_validasi_verifikasi_rka'.$prefix,
                            array('update_at' => current_time('mysql')),
                            array(
                                'id_user' => $data->id_user,
                                'kode_sbl' => $data->kode_sbl,
                                'tahun_anggaran' => $data->tahun_anggaran
                            )
                        );

                        if ($result_validasi === false) {
                            $ret['status'] = 'error';
                            $ret['message'] = 'Gagal hapus data di database.';
                        }
                    }
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Gagal menemukan data di database.';
                }
            } else {
                $ret['status']  = 'error';
                $ret['message'] = 'Api key tidak valid!';
            }
        } else {
            $ret['status']  = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function tambah_user_pptk()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil tambah user!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                //validasi tidak boleh kosong
                if (empty($_POST['username'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Username tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['nama'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'nama tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['nomorwa'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'nomorwa tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['email'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'email tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['roles'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'role tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['skpd'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'pilihan SKPD tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $password = '';
                if (
                    empty($_POST['id_user'])
                    && empty($_POST['password'])
                ) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Password tidak boleh kosong!';
                    die(json_encode($ret));
                } else {
                    $password = $_POST['password'];
                }

                //
                $username = $_POST['username'];
                $nama = $_POST['nama'];
                $nomorwa = $_POST['nomorwa'];
                $email = $_POST['email'];
                $role = $_POST['roles'];
                $skpd = $_POST['skpd'];

                //validasi input
                if (strlen($username) < 5) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Username harus minimal 5 karakter.';
                    die(json_encode($ret));
                }
                if (!empty($password)) {
                    if (strlen($password) < 8) {
                        $ret['status'] = 'error';
                        $ret['message'] = 'Password harus minimal 8 karakter.';
                        die(json_encode($ret));
                    }
                    if (!preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z\d]/', $password)) {
                        $ret['status'] = 'error';
                        $ret['message'] = 'Password harus mengandung angka dan karakter unik.';
                        die(json_encode($ret));
                    }
                }
                if (!is_email($email)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Format email tidak valid.';
                    die(json_encode($ret));
                }
                if (!preg_match('/^\+62\d{9,15}$/', $nomorwa)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Nomor WhatsApp harus dimulai dengan +62, masukan 9 - 15 karakter!.';
                    die(json_encode($ret));
                }

                //
                if (!empty($_POST['id_user'])) {
                    $insert_user = $_POST['id_user'];
                    $current_user = get_userdata($insert_user);
                    if (empty($current_user)) {
                        $ret['status'] = 'error';
                        $ret['message'] = 'User dengan id=' . $insert_user . ', tidak ditemukan!';
                        die(json_encode($ret));
                    }
                } else {
                    $insert_user = username_exists($username);
                }

                $option = array(
                    'user_login' => $username,
                    'user_pass' => $password,
                    'user_email' => $email,
                    'first_name' => $nama,
                    'display_name' => $nama,
                    'role' => $role
                );
                //proses tambah user
                if (!$insert_user) {
                    $insert_user = wp_insert_user($option);
                    update_user_meta($insert_user, 'nomor_wa', $nomorwa);
                    update_user_meta($insert_user, 'skpd', $skpd);

                    if (is_wp_error($insert_user)) {
                        $ret['status'] = 'error';
                        $ret['message'] = $insert_user->get_error_message();
                    } else {
                        $ret['status'] = 'success';
                        $ret['message'] = 'User berhasil ditambahkan.';
                    }
                } else {
                    if (
                        !empty($_POST['id_user'])
                        && ($current_user->user_login == $username
                            || !username_exists($username)
                        )
                    ) {
                        // update password jika password tidak kosong
                        if (
                            empty($password)
                        ) {
                            unset($option['user_pass']);
                        } else {
                            wp_set_password($password, $_POST['id_user']);
                        }

                        // update data meta user
                        $option['ID'] = $_POST['id_user'];
                        wp_update_user($option);

                        // update username jika namanya beda
                        $new_user_login = $username;
                        if ($current_user->user_login != $username) {
                            $wpdb->update(
                                $wpdb->users,
                                array('user_login' => $new_user_login),
                                array('ID' => $_POST['id_user'])
                            );
                        }

                        // update user meta
                        update_user_meta($_POST['id_user'], 'nomor_wa', $nomorwa);

                        $user_id = um_user('ID');
                        $user_meta = get_userdata($user_id);

                        // cek jika user SKPD, maka skpd existing lain tetap disimpan ulang
                        if (!in_array("administrator", $user_meta->roles)) {

                            // get data id skpd per tahun anggaran sesuai dengan user yang login
                            $nipkepala = get_user_meta($user_id, '_nip');
                            $data_skpd = array();
                            $idtahun = $wpdb->get_results("select distinct tahun_anggaran from data_unit order by tahun_anggaran DESC", ARRAY_A);
                            foreach ($idtahun as $val) {
                                $s = $wpdb->get_results($wpdb->prepare("
                                    SELECT 
                                        nama_skpd, 
                                        id_skpd, 
                                        kode_skpd,
                                        nipkepala,
                                        is_skpd
                                    FROM data_unit 
                                    WHERE active=1
                                        AND nipkepala=%s
                                        AND tahun_anggaran=%d
                                    group by id_skpd
                                ", $nipkepala[0], $val['tahun_anggaran']), ARRAY_A);
                                foreach ($s as $v) {
                                    if (empty($data_skpd[$val['tahun_anggaran']])) {
                                        $data_skpd[$val['tahun_anggaran']] = array();
                                    }
                                    $data_skpd[$val['tahun_anggaran']][$v['id_skpd']] = $v['kode_skpd'] . ' ' . $v['nama_skpd'];
                                }
                            }

                            // pengecekan data skpd user login dengan skpd existing user pptk
                            $skpd_pptk_existing = get_user_meta($insert_user, 'skpd');
                            foreach ($skpd_pptk_existing as $s) {
                                foreach ($s as $tahun => $id_skpd) {
                                    if (empty($data_skpd[$tahun][$id_skpd])) {
                                        $skpd[$tahun] = $id_skpd;
                                    }
                                }
                            }
                        }
                        ksort($skpd);
                        // update data skpd setelah variable $skpd disesuaikan
                        update_user_meta($insert_user, 'skpd', $skpd);

                        $ret['message'] = 'Berhasil update data!';
                    } else {
                        $ret['status'] = 'error';
                        $ret['message'] = 'Username sudah ada!';
                    }
                }
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function get_user_pptk()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get user!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                $user_id = um_user('ID');
                $user_meta = get_userdata($user_id);
                $params = $columns = $totalRecords = $data = array();
                $params = $_REQUEST;
                $roles = array('pptk');
                $args = array(
                    'role__in' => $roles,
                    'orderby' => 'user_nicename',
                    'order'   => 'ASC'
                );
                if (!empty($params['search']['value'])) {
                    $args['search'] = $params['search']['value'];
                }

                $idtahun = $wpdb->get_results("select distinct tahun_anggaran from data_unit", ARRAY_A);
                $users = array();
                $data_skpd = array();
                $skpd_user = array();
                // get data user harus login sebagai admin
                if (in_array("administrator", $user_meta->roles)) {
                    $users = get_users($args);

                    foreach ($idtahun as $val) {
                        $skpd = $wpdb->get_results($wpdb->prepare("
                            SELECT 
                                nama_skpd, 
                                id_skpd, 
                                kode_skpd,
                                is_skpd
                            FROM data_unit 
                            WHERE active=1
                                AND tahun_anggaran=%d
                            group by id_skpd", $val['tahun_anggaran']), ARRAY_A);
                        foreach ($skpd as $v) {
                            if (empty($data_skpd[$val['tahun_anggaran']])) {
                                $data_skpd[$val['tahun_anggaran']] = array();
                            }
                            $data_skpd[$val['tahun_anggaran']][$v['id_skpd']] = $v['kode_skpd'] . ' ' . $v['nama_skpd'];
                        }
                    }
                } else if (
                    in_array("PA", $user_meta->roles)
                    || in_array("KPA", $user_meta->roles)
                    || in_array("PLT", $user_meta->roles)
                ) {
                    $args['meta_query'] = array();
                    $args['meta_query']['relation'] = 'OR';
                    $nipkepala = get_user_meta($user_id, '_nip');
                    foreach ($idtahun as $val) {
                        $skpd = $wpdb->get_results($wpdb->prepare("
                            SELECT 
                                nama_skpd, 
                                id_skpd, 
                                kode_skpd,
                                nipkepala,
                                is_skpd
                            FROM data_unit 
                            WHERE active=1
                                AND tahun_anggaran=%d
                            group by id_skpd
                        ", $val['tahun_anggaran']), ARRAY_A);
                        foreach ($skpd as $v) {
                            if (empty($data_skpd[$val['tahun_anggaran']])) {
                                $data_skpd[$val['tahun_anggaran']] = array();
                            }
                            $data_skpd[$val['tahun_anggaran']][$v['id_skpd']] = $v['kode_skpd'] . ' ' . $v['nama_skpd'];
                            if ($v['nipkepala'] == $nipkepala[0]) {
                                $args['meta_query'][] = array(
                                    'key' => 'skpd',
                                    'value' => 'i:' . $val['tahun_anggaran'] . ';s:4:"' . $v['id_skpd'] . '"',
                                    'compare' => 'LIKE'
                                );
                                $skpd_user[$v['id_skpd']] = $v['kode_skpd'] . ' ' . $v['nama_skpd'];
                            }
                        }
                    }
                    $args['meta_query'][] = array(
                        'key' => 'skpd',
                        'value' => 'i:' . date('Y') . ';s:4:',
                        'compare' => 'NOT LIKE'
                    );
                    $users = get_users($args);
                }
                $sql_user = $wpdb->last_query . ' | ' . json_encode($args['meta_query']);
                $data_user = array();
                foreach ($users as $recKey => $recVal) {
                    $btn = '<a class="btn btn-sm btn-warning" onclick="edit_data(\'' . $recVal->ID . '\'); return false;" href="#" style="margin-right: 10px;" title="Edit Data">
                    <i class="dashicons dashicons-edit"></i></a>';

                    $skpd_html = array();
                    $skpd = get_user_meta($recVal->ID, 'skpd');
                    foreach ($skpd[0] as $tahun => $id_skpd) {
                        $skpd_html[] = $data_skpd[$tahun][$id_skpd] . ' ( ' . $tahun . ' )';
                    }
                    $data_user[$recKey]['skpd'] = implode('<br>', $skpd_html);

                    if (in_array("administrator", $user_meta->roles)) {
                        $btn .= '<a class="btn btn-sm btn-danger" onclick="delete_data(\'' . $recVal->ID . '\'); return false;" href="#" title="Hapus Data"><i class="dashicons dashicons-trash"></i></a>';
                    }else{
                        $cek_skpd_lain = false;
                        foreach ($skpd[0] as $tahun => $id_skpd) {
                            if(empty($skpd_user[$id_skpd])){
                                $cek_skpd_lain = true;
                            }
                        }
                        if(false == $cek_skpd_lain){
                            $btn .= '<a class="btn btn-sm btn-danger" onclick="delete_data(\'' . $recVal->ID . '\'); return false;" href="#" title="Hapus Data"><i class="dashicons dashicons-trash"></i></a>';
                        }
                    }
                    $data_user[$recKey]['aksi'] = $btn;
                    $data_user[$recKey]['user'] = $recVal->user_login;
                    $data_user[$recKey]['nama'] = $recVal->display_name;
                    $data_user[$recKey]['nomorwa'] = get_user_meta($recVal->ID, 'nomor_wa');
                }

                $json_data = array(
                    "draw"            => intval($params['draw']),
                    "recordsTotal"    => intval(count($data_user)),
                    "recordsFiltered" => intval(count($data_user)),
                    "data"            => $data_user,
                    "sql"             => $sql_user
                );

                die(json_encode($json_data));
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function delete_user_pptk()
    {
        global $wpdb;

        $ret = array(
            'status' => 'success',
            'message' => 'Berhasil hapus data!',
            'data' => array()
        );

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {

                $allowed_roles = array('pptk');
                $current_user = wp_get_current_user();
                if (!in_array('administrator', $current_user->roles)) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Akses ditolak - hanya administrator yang dapat mengakses fitur ini!';
                    die(json_encode($ret));
                }

                if (empty($_POST['id'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id user tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $current_user = get_userdata($_POST['id']);
                if (!empty($current_user)) {
                    $user_roles = $current_user->roles;
                    $is_allowed = false;
                    foreach ($user_roles as $role) {
                        if (in_array($role, $allowed_roles)) {
                            $is_allowed = true;
                            break;
                        }
                    }
                    if ($is_allowed) {
                        if ($current_user->ID) {
                            wp_delete_user($current_user->ID);
                        } else {
                            $ret['status']  = 'error';
                            $ret['message'] = 'User tidak ditemukan!';
                        }
                    } else {
                        $ret['status']  = 'error';
                        $ret['message'] = 'User ini tidak dapat dihapus!';
                    }
                } else {
                    $ret['status']  = 'error';
                    $ret['message'] = 'User tidak ditemukan!';
                }
            } else {
                $ret['status']  = 'error';
                $ret['message'] = 'Api key tidak ditemukan!';
            }
        } else {
            $ret['status']  = 'error';
            $ret['message'] = 'Format Salah!';
        }

        die(json_encode($ret));
    }

    function get_user_pptk_by_id()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get user by id!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['id'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id user tidak boleh kosong!';
                    die(json_encode($ret));
                }
                $user = get_userdata($_POST['id']);
                if (!empty($user)) {
                    $roles = array('pptk');
                    $cek_role = false;
                    foreach ($roles as $role) {
                        if (in_array($role, $user->roles)) {
                            $cek_role = true;
                        }
                    }
                    if ($cek_role) {
                        $new_user = array();
                        $new_user['id_user'] = $user->ID;
                        $new_user['user_login'] = $user->data->user_login;
                        $new_user['display_name'] = $user->data->display_name;
                        $new_user['user_email'] = $user->data->user_email;
                        $new_user['nomorwa'] = get_user_meta($user->ID, 'nomor_wa');
                        $new_user['skpd'] = get_user_meta($user->ID, 'skpd');
                        $new_user['roles'] = $user->roles;
                        $ret['data'] = $new_user;
                    } else {
                        $ret['status'] = 'error';
                        $ret['message'] = 'Grup user verifikator tidak ditemukan!';
                    }
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'User tidak ditemukan!';
                }
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function get_sub_keg_pptk()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get user PPTK!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['id_skpd'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id skpd tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                }
                // cek role user existing harus administrator atau PA, PLT, KPA
                $current_user = wp_get_current_user();
                $allowed_roles = array('administrator', 'PA', 'KPA', 'PLT');

                // Periksa apakah ada perpotongan antara peran yang diizinkan dan peran pengguna saat ini.
                if (empty(array_intersect($allowed_roles, $current_user->roles))) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
                    die(json_encode($ret));
                }

                $prefix = '';
                if(!empty($_POST['tipe_rka'])){
                    if($_POST['tipe_rka'] == 'rka_lokal'){
                        $prefix = '_lokal';
                    }
                }

                $sub_keg = $wpdb->get_row($wpdb->prepare("
                SELECT
                    p.*,
                    s.nama_sub_giat,
                    s.kode_urusan
                FROM data_pptk_sub_keg".$prefix." p
                RIGHT JOIN data_sub_keg_bl s on p.kode_sbl = s.kode_sbl
                    and p.tahun_anggaran = s.tahun_anggaran
                    and s.active=1
                WHERE s.tahun_anggaran=%d
                    and s.kode_sbl=%s
                ", $_POST['tahun_anggaran'], $_POST['kode_sbl']), ARRAY_A);
                if ($sub_keg && isset($sub_keg['kode_urusan'])) {
                    $sub_keg['nama_sub_giat'] = str_replace("X.XX", $sub_keg['kode_urusan'], $sub_keg['nama_sub_giat']);
                }
                $ret['sub_keg'] = $sub_keg;

                $data_skpd = array();
                $data_skpd[$_POST['tahun_anggaran']] = $_POST['id_skpd'];
                $args = array(
                    'role'    => 'pptk',
                    'orderby' => 'user_nicename',
                    'order'   => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'skpd',
                            'value' => 'i:' . $_POST['tahun_anggaran'] . ';s:4:"' . $_POST['id_skpd'] . '"',
                            'compare' => 'LIKE'
                        )
                    )
                );
                $users = get_users($args);
                $user_pptk_opt = '<option value="">Pilih User</option>';
                foreach ($users as $user) {
                    $selected = '';
                    if ($user->ID == $sub_keg['id_user']) {
                        $selected = 'selected';
                    }
                    $user_pptk_opt .= '<option value="' . esc_attr($user->ID) . '" ' . $selected . '>' . esc_html($user->display_name) . '</option>';
                }
                $ret['user_pptk_html'] = $user_pptk_opt;
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function simpan_sub_keg_pptk()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil simpan user PPTK!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['kode_sbl'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'kode_sbl tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['id_user'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id_user tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                }
                // cek role user existing harus administrator atau PA, PLT, KPA
                $current_user = wp_get_current_user();
                $allowed_roles = array('administrator', 'PA', 'KPA', 'PLT');
                // Periksa apakah ada perpotongan antara peran yang diizinkan dan peran pengguna saat ini.
                if (empty(array_intersect($allowed_roles, $current_user->roles))) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
                    die(json_encode($ret));
                }
                $kode_sbl = $_POST['kode_sbl'];
                $id_user = $_POST['id_user'];
                $tahun_anggaran = $_POST['tahun_anggaran'];

                get_userdata($id_user);

                $data = array(
                    'kode_sbl' => $kode_sbl,
                    'id_user' => $id_user,
                    'tahun_anggaran' => $tahun_anggaran,
                    'active' => 1,
                    'update_at' => current_time('mysql')
                );

                $prefix = '';
                if(!empty($_POST['tipe_rka'])){
                    if($_POST['tipe_rka'] == 'rka_lokal'){
                        $prefix = '_lokal';
                    }
                }

                $nama_tabel = "data_pptk_sub_keg".$prefix;
                $cek_pptk = $wpdb->get_var($wpdb->prepare("
                    SELECT
                        id
                    FROM ".$nama_tabel."
                    WHERE active=1
                        and tahun_anggaran=%d
                        and kode_sbl=%s
                ", $tahun_anggaran, $kode_sbl));

                if (empty($cek_pptk)) {
                    $result = $wpdb->insert($nama_tabel, $data);
                } else {
                    $wpdb->update($nama_tabel, $data, array('id' => $cek_pptk));
                }
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function get_data_nota_pencairan_dana()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get data nota pencairan dana!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['kode_sbl'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'kode sbl tidak boleh kosong!';
                    die(json_encode($ret));
                } else if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $kode_sbl = $_POST['kode_sbl'];
                $tahun_anggaran = $_POST['tahun_anggaran'];

                $data_npd = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                        *
                    FROM data_nota_pencairan_dana
                    WHERE kode_sbl = %s
                        AND tahun_anggaran = %d
                        AND active = 1
                    ORDER BY kode_akun ASC", $kode_sbl, $tahun_anggaran), ARRAY_A);

                $ret['html'] = '';
                $no = 0;
                foreach ($data_npd as $val) {
                    $ret['html'] .= '
                        <tr>
                            <td>' . $no++ . '</td>
                            <td>' . $val['kode_akun'] . '</td>
                            <td>' . $val['kode_akun'] . ' ' . $val['nama_akun'] . '</td>
                            <td>0</td>
                            <td>0</td>
                            <td>'. $val['pagu_pencairan'] .'</td>
                            <td class="aksi">';
                                // tampilkan tombol edit dan hapus
                                $ret['html'] .= '
                                    <a class="btn btn-sm btn-warning" onclick="edit_data(\'' . $val['id'] . '\'); return false;" href="#" title="Edit Data"><i class="dashicons dashicons-edit"></i></a>
                                    <a class="btn btn-sm btn-danger" onclick="delete_data(\'' . $val['id'] . '\'); return false;" href="#" title="delete data"><i class="dashicons dashicons-trash"></i></a>';
                    $ret['html'] .= '</td></tr>';
                }
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }
    
    function get_daftar_panjar()
    {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get daftar panjar!';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['kode_sbl'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'kode sbl tidak boleh kosong!';
                    die(json_encode($ret));
                } else if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $kode_sbl = $_POST['kode_sbl'];
                $tahun_anggaran = $_POST['tahun_anggaran'];

                $data_npd = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                        *,
                        SUM(pagu_pencairan) as total_pagu_pencairan
                    FROM data_nota_pencairan_dana
                    WHERE kode_sbl = %s
                        AND tahun_anggaran = %d
                        AND active = 1
                    GROUP BY nomor_npd
                    ORDER BY kode_akun ASC", $kode_sbl, $tahun_anggaran), ARRAY_A);

                $data_all = array(
                    'total_pencairan_all' => 0,
                    'data' => array()
                );
                
                foreach ($data_npd as $v_npd) {
                    if(empty($data_all['data'][$v_npd['nomor_npd']])){
                        $data_all['data'][$v_npd['nomor_npd']] = array(
                            'id' => $v_npd['id'],
                            'nomor_npd' => $v_npd['nomor_npd'],
                            'jenis_panjar' => $v_npd['jenis_panjar'],
                            'total_pagu_pencairan' => $v_npd['total_pagu_pencairan'],
                            'id_user_pptk' => $v_npd['id_user_pptk']
                        );
                    }

                    // array_push($data_all['data'][$v_npd['nomor_npd']]['data'], $v_npd); 
                    $data_all['total_pencairan_all'] += $v_npd['total_pagu_pencairan'];
                    // $data_all['data'][$v_npd['nomor_npd']]['total_pencairan'] += $v_npd['pagu_pencairan'];
                }

                $ret['html'] = '';
                foreach ($data_all['data'] as $v_all_npd) {
                    $current_user = get_userdata($v_all_npd['id_user_pptk']);
                    $jenis_panjar = $v_all_npd['jenis_panjar'] == 'set_panjar' ? 'Panjar' : 'Tanpa Panjar';
                    $ret['html'] .= '
                        <tr>
                            <td class="kanan bawah kiri id-npd-'.$v_all_npd['id'].'">' . $v_all_npd['nomor_npd'] . '</td>
                            <td class="kanan bawah text-right">'. $jenis_panjar .'</td>
                            <td class="kanan bawah text-right">'. $current_user->display_name .'</td>
                            <td class="kanan bawah text-right">'. $v_all_npd['total_pagu_pencairan'] .'</td>
                            <td class="kanan bawah text-right text-center">';
                                // \'' . $v_per_no_npd[0]->id . '\'
                                // tampilkan tombol edit dan hapus
                                $ret['html'] .= '
                                <a class="btn btn-sm btn-info" onclick="print('. $v_all_npd['id'] .'); return false;" href="#" title="Print"><i class="dashicons dashicons-printer"></i></a>
                                <a class="btn btn-sm btn-success" onclick="tambah_rekening('. $v_all_npd['id'] .'); return false;" href="#" title="Tambah Rekening"><i class="dashicons dashicons-plus"></i></a>
                                <a class="btn btn-sm btn-warning" onclick="edit_data('. $v_all_npd['id'] .'); return false;" href="#" title="Edit Data"><i class="dashicons dashicons-edit"></i></a>
                                <a class="btn btn-sm btn-danger" onclick="delete_data('. $v_all_npd['id'] .'); return false;" href="#" title="delete data"><i class="dashicons dashicons-trash"></i></a>';
                            $ret['html'] .='</td>
                        </tr>';
                }
                $ret['cek'] = $data_all;
                $ret['data_npd'] = $data_npd;
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function tambah_data_panjar($return_callback = false) {
        global $wpdb;
		$ret = array(
			'status'	=> 'success',
			'message' 	=> 'Tambah data panjar!'
		);
        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                }else if (empty($_POST['kode_sbl'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'kode sbl tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $tahun_anggaran = $_POST['tahun_anggaran'];
                $kode_sbl = $_POST['kode_sbl'];
                $data = json_decode(stripslashes($_POST['data']), true);
                if(empty($data['nomor_npd'])){
                    $ret['status'] = 'error';
                    $ret['message'] = 'Nomor NPD tidak boleh kosong!';
                }elseif(empty($data['set_panjar'])){
                    $ret['status'] = 'error';
                    $ret['message'] = 'Setting Panjar tidak boleh kosong!';
                }elseif(empty($data['set_pptk'])){
                    $ret['status'] = 'error';
                    $ret['message'] = 'Setting PPTK tidak boleh kosong!';
                }elseif(empty($data['nomor_dpa'])){
                    $ret['status'] = 'error';
                    $ret['message'] = 'Nomor DPA tidak boleh kosong!';
                }

                // cek role user existing harus administrator atau PA, PLT, KPA
                $current_user = wp_get_current_user();
                $allowed_roles = array('administrator', 'PA', 'KPA', 'PLT');

                // Periksa apakah ada perpotongan antara peran yang diizinkan dan peran pengguna saat ini.
                if (empty(array_intersect($allowed_roles, $current_user->roles))) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
                    die(json_encode($ret));
                }

                $jenis_panjar = ($data['set_panjar'] == 'dengan_panjar') ? 'set_panjar' : 'not_set_panjar';

                if($ret['status'] != 'error'){
                    // $data_akun = $wpdb->get_row($wpdb->prepare('
					// 	SELECT 
					// 		*
					// 	FROM data_akun
					// 	WHERE kode_akun=%s
					// 		AND tahun_anggaran=%d
					// 		AND active=1
					// ', $data['rekening_akun'], $tahun_anggaran));

                    //Cek nomor npd jika tambah baru
                    $cek_nomor = $wpdb->get_var($wpdb->prepare('
                        SELECT
                            nomor_npd
                        FROM data_nota_pencairan_dana
                        WHERE nomor_npd=%s
                            AND tahun_anggaran=%d
                            AND active=1
                    ', $data['nomor_npd'], $tahun_anggaran));

                    if(empty($_POST['id']) && !empty($cek_nomor)){
                        $ret['status'] = 'error';
                        $ret['message'] = 'Nomor NPD sudah terpakai!';
                        die(json_encode($ret));
                    }

                    $input_options = array(
                        'kode_sbl' => $kode_sbl,
                        'nomor_npd' => $data['nomor_npd'],
                        'kode_akun' => NULL,
                        'nama_akun' => NULL,
                        'id_user_pptk' => $data['set_pptk'],
                        'jenis_panjar' => $jenis_panjar,
                        'nomor_dpa' => $data['nomor_dpa'],
                        'pagu_pencairan' => 0,
                        'tahun_anggaran' => $tahun_anggaran,
                        'active' => 1,
                        'created_at' => current_time('mysql'),
                        'update_at' => current_time('mysql')
                    );

                    //insert data panjar
                    if(!empty($_POST['id'])){
                        $cek_id = $wpdb->get_var($wpdb->prepare('
                            SELECT
                                id
                            FROM data_nota_pencairan_dana
                            WHERE id=%d
                                AND tahun_anggaran=%d
                                AND active=1
                        ', $_POST['id'], $tahun_anggaran));
                        
                        if(!$cek_id){
                            //cek nomor npd
                            $cek_nomor = $wpdb->get_var($wpdb->prepare('
                                SELECT
                                    nomor_npd
                                FROM data_nota_pencairan_dana
                                WHERE nomor_npd=%s
                                    AND tahun_anggaran=%d
                                    AND active=1
                            ', $data['nomor_npd'], $tahun_anggaran));

                            if(!empty($cek_nomor)){
                                $ret['status'] = 'error';
                                $ret['message'] = 'Nomor NPD sudah terpakai!';
                                die(json_encode($ret));
                            }

                            $wpdb->insert('data_nota_pencairan_dana', $input_options);
                            $ret['message'] = 'Berhasil menambahkan data panjar!';
                        }else{
                            $wpdb->update('data_nota_pencairan_dana', $input_options, array('id' => $cek_id));
                            $ret['message'] = 'Berhasil update data panjar!';
                        }
                    }else{
                        $wpdb->insert('data_nota_pencairan_dana', $input_options);
                        $ret['message'] = 'Berhasil menambahkan data panjar!';
                    }
                }
            }else{
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        }else{
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }

        if($return_callback){
            return $ret;
        }else{
            die(json_encode($ret));
        }
    }

    function edit_data_panjar() {
        $this->tambah_data_panjar();
    }

    function delete_data_panjar() {
        global $wpdb;
		$ret = array(
			'status'	=> 'success',
			'message' 	=> 'Hapus data panjar!'
		);
        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['id'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id tidak boleh kosong!';
                    die(json_encode($ret));
                }

                // cek role user existing harus administrator atau PA, PLT, KPA
                $current_user = wp_get_current_user();
                $allowed_roles = array('administrator', 'PA', 'KPA', 'PLT');

                // Periksa apakah ada perpotongan antara peran yang diizinkan dan peran pengguna saat ini.
                if (empty(array_intersect($allowed_roles, $current_user->roles))) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
                    die(json_encode($ret));
                }

                if($ret['status'] != 'error'){
                    $status = $wpdb->update('data_nota_pencairan_dana', array('active' => 0), array('id' => $_POST['id']));

                    if($status === false){
						$ret['status'] = 'error';
						$ret['message'] = 'Delete gagal, harap hubungi admin!';
					}else{
                        $ret['message'] = 'Data berhasil dihapus';
                    }

                }
            }else{
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        }else{
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }

        die(json_encode($ret));
    }

    function get_nota_panjar_by_id() {
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get nota panjar by id!';
        $ret['data'] = array();

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['id'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'id user tidak boleh kosong!';
                    die(json_encode($ret));
                }else if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $data_nota_panjar = $wpdb->get_row($wpdb->prepare("
                    SELECT *
                    FROM data_nota_pencairan_dana
                    WHERE id=%d
                    AND tahun_anggaran=%d
                    AND active=1
                ", $_POST['id'], $_POST['tahun_anggaran']), ARRAY_A);

                if (!empty($data_nota_panjar)) {
                    $data_nota_panjar['jenis_panjar'] = $data_nota_panjar['jenis_panjar'] == 'set_panjar' ? 'dengan_panjar' : 'tanpa_panjar';
                    $ret['data'] = $data_nota_panjar;
                } else {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Nota Panjar tidak ditemukan!';
                }

                $data_rekening_nota_panjar = $wpdb->get_results($wpdb->prepare("
                    SELECT *
                    FROM data_rekening_nota_pencairan_dana
                    WHERE id_npd=%d
                    AND tahun_anggaran=%d
                    AND active=1
                ", $_POST['id'], $_POST['tahun_anggaran']));

                $ret['data']['rekening_npd'] = array();
                if (!empty($data_rekening_nota_panjar)) {
                    $ret['data']['rekening_npd'] = $data_rekening_nota_panjar;
                }
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function get_rka_sub_keg_akun(){
        global $wpdb;
        $ret = array();
        $ret['status'] = 'success';
        $ret['message'] = 'Berhasil get data akun rka per sub keg!';
        $ret['data_akun_html'] = '';

        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                }
                if (empty($_POST['kode_sbl'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Kode sbl tidak boleh kosong!';
                    die(json_encode($ret));
                }
                
                $tahun_anggaran = $_POST['tahun_anggaran'];
                $kode_sbl = $_POST['kode_sbl'];
                // cek role user existing harus administrator atau PA, PLT, KPA
                $current_user = wp_get_current_user();
                $allowed_roles = array('administrator', 'PA', 'KPA', 'PLT');

                // Periksa apakah ada perpotongan antara peran yang diizinkan dan peran pengguna saat ini.
                if (empty(array_intersect($allowed_roles, $current_user->roles))) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
                    die(json_encode($ret));
                }

                $prefix = '';
                if(!empty($_POST['tipe_rka'])){
                    if($_POST['tipe_rka'] == 'rka_lokal'){
                        $prefix = '_lokal';
                    }
                }

                $data_akun = $wpdb->get_results("
					SELECT 
                        dakun.kode_akun,
                        dakun.nama_akun 
					FROM data_rka".$prefix." as drka 
                    LEFT JOIN data_akun as dakun
                    ON drka.kode_akun=dakun.kode_akun
					WHERE drka.kode_sbl='".$kode_sbl."'
						AND drka.tahun_anggaran=".$tahun_anggaran."
						AND drka.active=1
                        AND dakun.tahun_anggaran=".$tahun_anggaran."
						AND dakun.active=1
					GROUP BY drka.kode_akun 
                    ORDER BY drka.kode_akun ASC"
				, ARRAY_A);

                $data_akun_options = '<option value="">Pilih Rekening</option>';
                foreach ($data_akun as $v_akun) {
                    $data_akun_options .= '<option value="' . $v_akun['kode_akun'] . '">'. $v_akun['kode_akun'] .' ' . $v_akun['nama_akun'] . '</option>';
                }
                $ret['data_akun_html'] = $data_akun_options;
            } else {
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        } else {
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }
        die(json_encode($ret));
    }

    function tambah_data_rekening_panjar($return_callback = false) {
        global $wpdb;
		$ret = array(
			'status'	=> 'success',
			'message' 	=> 'Tambah rekening data panjar!'
		);
        if (!empty($_POST)) {
            if (!empty($_POST['api_key']) && $_POST['api_key'] == get_option('_crb_api_key_extension')) {
                if (empty($_POST['tahun_anggaran'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'tahun anggaran tidak boleh kosong!';
                    die(json_encode($ret));
                }else if (empty($_POST['kode_sbl'])) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'kode sbl tidak boleh kosong!';
                    die(json_encode($ret));
                }

                $tahun_anggaran = $_POST['tahun_anggaran'];
                $kode_sbl = $_POST['kode_sbl'];
                $data = json_decode(stripslashes($_POST['data']), true);
                if(empty($data['id_npd_rek'])){
                    $ret['status'] = 'error';
                    $ret['message'] = 'Id User tidak boleh kosong!';
                }

                // cek role user existing harus administrator atau PA, PLT, KPA
                $current_user = wp_get_current_user();
                $allowed_roles = array('administrator', 'PA', 'KPA', 'PLT');

                // Periksa apakah ada perpotongan antara peran yang diizinkan dan peran pengguna saat ini.
                if (empty(array_intersect($allowed_roles, $current_user->roles))) {
                    $ret['status'] = 'error';
                    $ret['message'] = 'Akses ditolak - hanya pengguna dengan peran tertentu yang dapat mengakses fitur ini!';
                    die(json_encode($ret));
                }

                foreach ($data['rekening_akun'] as $k_rekening => $v_rekening) {
                    if($ret['status'] != 'error'){
                        if(
                            !isset($data['pagu_rekening'][$k_rekening])
                            || $data['pagu_rekening'][$k_rekening] == ''
                        ){
                            $ret['status'] = 'error';
                            $ret['message'] = 'Pagu Rekening tidak boleh kosong!';
                        }else if(empty($data['rekening_akun'][$k_rekening])){
                            $ret['status'] = 'error';
                            $ret['message'] = 'Rekening tidak boleh kosong!';
                        }
                    }
                }

                if($ret['status'] != 'error'){
                    //insert  rekening
                    $wpdb->update('data_rekening_nota_pencairan_dana', array('active' => 0), array(
                        'kode_sbl' => $kode_sbl,
                        'tahun_anggaran' => $tahun_anggaran,
                        'id_npd' => $data['id_npd_rek']
                    ));
                    foreach ($data['rekening_akun'] as $k_rek_akun => $v_rek_akun) {
                        $data_akun = $wpdb->get_row($wpdb->prepare('
                            SELECT 
                                *
                            FROM data_akun
                            WHERE kode_akun=%s
                                AND tahun_anggaran=%d
                                AND active=1
                        ', $data['rekening_akun'][$k_rek_akun], $tahun_anggaran));

                        if(!empty($data_akun)){
                            $cek_ids = $wpdb->get_results($wpdb->prepare('
                                SELECT 
                                    id
                                FROM data_rekening_nota_pencairan_dana
                                WHERE kode_sbl=%s
                                    AND id_npd=%d
                                    AND tahun_anggaran=%d
                                    AND active=1
                            ', $kode_sbl, $data['id_npd_rek'][$k_rek_akun],$tahun_anggaran), ARRAY_A);

                            $opsi_rekening = array(
                                'nama_rekening' => $data_akun->nama_akun,
                                'kode_rekening' => $data_akun->kode_akun,
                                'id_rekening' => $data_akun->id_akun,
                                'pagu_dana' => $data['pagu_rekening'][$k_rek_akun],
                                'kode_sbl' => $kode_sbl,
                                'id_npd' => $data['id_npd_rek'],
                                'update_at' => current_time('mysql'),
                                'active' => 1,
                                'tahun_anggaran' => $tahun_anggaran
                            );

                            if(
                                empty($cek_ids)
                                || empty($cek_ids[$k_rek_akun])
                            ){
                                $wpdb->insert('data_rekening_nota_pencairan_dana', $opsi_rekening);
                                $ret['message'] = 'Berhasil menambahkan rekening data panjar!';
                            }else{
                                $wpdb->update('data_rekening_nota_pencairan_dana', $opsi_rekening, array('id' => $cek_ids[$k_rek_akun]['id']));
                                $ret['message'] = 'Berhasil update rekening data panjar!';
                            }
                        }else{
                            $ret['status'] = 'error';
                            $ret['message'] = 'Data rekening tidak ditemukan!';
                        }
                    }
                }
            }else{
                $ret['status'] = 'error';
                $ret['message'] = 'APIKEY tidak sesuai!';
            }
        }else{
            $ret['status'] = 'error';
            $ret['message'] = 'Format Salah!';
        }

        if($return_callback){
            return $ret;
        }else{
            die(json_encode($ret));
        }
    }
}
