<?php
global $wpdb;

$roles = array('verifikator_bappeda', 'verifikator_bppkad', 'verifikator_pbj', 'verifikator_adbang', 'verifikator_inspektorat', 'verifikator_pupr', 'pptk');
$options_role = array();
foreach ($roles as $val) {
    $role = get_role($val);
    if (empty($role)) {
        add_role($val, $val, array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false
        ));
    }
    if ($val != 'pptk') {
        $options_role[] = "<option value='$val'>$val</option>";
    }
}
$api_key = get_option('_crb_api_key_extension');
?>
<style>
    .modal-content label:after {
        content: ' *';
        color: red;
        margin-right: 5px;
    }
</style>
<h1 class="text-center" style="margin-top: 50px;">Manajemen User Verifikasi RKA (Rencana Kerja dan Anggaran)</h1>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_tambah_data" style="margin-left: 15px;">
    + Tambah Data
</button>

<!-- Modal -->
<div class="modal fade" id="modal_tambah_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog .modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Tambah Data User Verifikasi RKA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" id="username" required>
                    <small class="form-text text-muted">
                        Username minimal 5 karakter
                    </small>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="password" required>
                    <input type="checkbox" class="form-check-input" onclick="pass_visibility()">Lihat Password
                    <small class="form-text text-muted">
                        Password minimal 8 karakter, mengandung huruf, angka, dan karakter unik
                    </small>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" id="nama" required>
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="form-group">
                    <label>Nomor WA</label>
                    <input type="number" class="form-control" id="nomorwa" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select class="form-control" id="role" required>
                        <?php echo implode('', $options_role); ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_data(this)">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- table -->
<div style="padding: 15px;margin:0 0 3rem 0;">
    <table class="table table-bordered" id="daftar-user">
        <thead>
            <tr>
                <th class="text-center">Id</th>
                <th class="text-center">Username</th>
                <th class="text-center">Nama</th>
                <th class="text-center">E-mail</th>
                <th class="text-center">Nomor WA</th>
                <th class="text-center">Role</th>
                <th class="text-center" style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    function pass_visibility() {
        var pass = jQuery("#password");
        if (pass.attr('type') === "password") {
            pass.attr('type', 'text');
        } else {
            pass.attr('type', 'password');
        }
    }

    function load_data() {
        if (typeof load_data_user == 'undefined') {
            window.load_data_user = jQuery('#daftar-user').on('preXhr.dt', function(e, settings, data) {
                jQuery("#wrap-loading").show();
            }).DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        api_key: '<?php echo $api_key; ?>',
                        action: 'get_user_verifikator'
                    }
                },
                lengthMenu: [
                    [20, 50, 100, -1]
                    [20, 50, 100, "All"]
                ],
                order: [
                    [0, 'asc']
                ],
                "drawCallback": function(settings) {
                    jQuery("#wrap-loading").hide();
                },
                "columns": [{
                        "data": 'id',
                        className: "text-center"
                    },
                    {
                        "data": 'user',
                        className: "text-center"
                    },
                    {
                        "data": 'nama',
                        className: "text-center"
                    },
                    {
                        "data": 'email',
                        className: "text-center"
                    },
                    {
                        "data": 'nomorwa',
                        className: "text-center"
                    },
                    {
                        "data": 'role',
                        className: "text-center"
                    },
                    {
                        "data": 'aksi',
                        className: "text-center"
                    }
                ]
            });
        } else {
            load_data_user.draw();
        }
    }
    jQuery(document).ready(function() {
        load_data();
    });

    function submit_data(that) {
        jQuery('#wrap-loading').show();
        const username = jQuery('#username').val();
        const password = jQuery('#password').val();
        const nama = jQuery('#nama').val();
        const email = jQuery('#email').val();
        const nomorwa = jQuery('#nomorwa').val();
        const role = jQuery('#role').val();

        jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                api_key: '<?php echo $api_key; ?>',
                username: username,
                password: password,
                nama: nama,
                email: email,
                role: role,
                nomorwa: nomorwa,
                action: 'tambah_user_verifikator'
            },
            success: function(data) {
                jQuery('#wrap-loading').hide();
                const response = JSON.parse(data);
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#modal_tambah_data').modal('hide');
                    load_data();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }

    function delete_data(id) {
        let confirmDelete = confirm("Apakah anda yakin akan menghapus user ini?");
        if (confirmDelete) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'post',
                data: {
                    'action': 'delete_user_verifikator',
                    'api_key': '<?php echo get_option('_crb_api_key_extension'); ?>',
                    'id': id
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if (response.status == 'success') {
                        load_data();
                    } else {
                        alert(`GAGAL! \n${response.message}`);
                    }
                }
            });
        }
    }

    function edit_data(_id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            method: 'post',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            dataType: 'json',
            data: {
                'action': 'get_user_verifikator',
                'api_key': '<?php echo get_option('_crb_api_key_extension'); ?>',
                'id': _id,
            },
            success: function(res) {
                if (res.status == 'success') {
                    jQuery('#username').val(res.data.username);
                    jQuery('#password').val(res.data.password);
                    jQuery('#nama').val(res.data.nama);
                    jQuery('#email').val(res.data.email);
                    jQuery('#nomorwa').val(res.data.nomorwa);
                    jQuery('#role').val(res.data.role);
                    jQuery('#modal_tambah_data').modal('show');
                } else {
                    alert(res.message);
                }
                jQuery('#wrap-loading').hide();
            }
        });
    }
</script>