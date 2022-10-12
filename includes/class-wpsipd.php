<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/agusnurwanto
 * @since      1.0.0
 *
 * @package    Wpsipd
 * @subpackage Wpsipd/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpsipd
 * @subpackage Wpsipd/includes
 * @author     Agus Nurwanto <agusnurwantomuslim@gmail.com>
 */
class Wpsipd
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wpsipd_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $simda;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('WPSIPD_VERSION')) {
			$this->version = WPSIPD_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wpsipd';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpsipd_Loader. Orchestrates the hooks of the plugin.
	 * - Wpsipd_i18n. Defines internationalization functionality.
	 * - Wpsipd_Admin. Defines all hooks for the admin area.
	 * - Wpsipd_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wpsipd-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wpsipd-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wpsipd-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wpsipd-public.php';

		// Untuk SCRIPT SIMDA
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wpsipd-simda.php';

		$this->simda = new Wpsipd_Simda( $this->plugin_name, $this->version );
		$this->loader = new Wpsipd_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpsipd_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Wpsipd_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Wpsipd_Admin($this->get_plugin_name(), $this->get_version(), $this->simda);

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('carbon_fields_register_fields', $plugin_admin, 'crb_attach_sipd_options');
		$this->loader->add_action('template_redirect', $plugin_admin, 'allow_access_private_post', 0);
		$this->loader->add_filter('carbon_fields_should_save_field_value', $plugin_admin, 'crb_edit_save', 10, 3);

		$this->loader->add_action('wp_ajax_get_label_komponen',  $plugin_admin, 'get_label_komponen');
		$this->loader->add_action('wp_ajax_simpan_data_label_komponen',  $plugin_admin, 'simpan_data_label_komponen');
		$this->loader->add_action('wp_ajax_hapus_data_label_komponen',  $plugin_admin, 'hapus_data_label_komponen');
		$this->loader->add_action('wp_ajax_simpan_mapping',  $plugin_admin, 'simpan_mapping');
		$this->loader->add_action('wp_ajax_load_ajax_carbon',  $plugin_admin, 'load_ajax_carbon');
		$this->loader->add_action('wp_ajax_generate_sumber_dana_format',  $plugin_admin, 'generate_sumber_dana_format');
		$this->loader->add_action('wp_ajax_get_list_skpd',  $plugin_admin, 'get_list_skpd');
		$this->loader->add_action('wp_ajax_get_analis_rincian_label',  $plugin_admin, 'get_analis_rincian_label');
		$this->loader->add_action('wp_ajax_sumberdana_sipd_lokal_ke_wp_sipd',  $plugin_admin, 'sumberdana_sipd_lokal_ke_wp_sipd');
		$this->loader->add_action('wp_ajax_sumberdana_wp_sipd_ke_rka_simda',  $plugin_admin, 'sumberdana_wp_sipd_ke_rka_simda');
		$this->loader->add_action('wp_ajax_generate_lisensi',  $plugin_admin, 'generate_lisensi');
		$this->loader->add_action('admin_notices',  $plugin_admin, 'wp_sipd_admin_notice');
		$this->loader->add_action('wp_ajax_get_api_modul_migrasi_data',  $plugin_admin, 'get_api_modul_migrasi_data');
		$this->loader->add_action('wp_ajax_nopriv_get_api_modul_migrasi_data',  $plugin_admin, 'get_api_modul_migrasi_data');
		$this->loader->add_action('wp_ajax_get_sinkron_modul_migrasi_data',  $plugin_admin, 'get_sinkron_modul_migrasi_data');
		$this->loader->add_action('wp_ajax_get_sinkron_data_sirup',  $plugin_admin, 'get_sinkron_data_sirup');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Wpsipd_Public($this->get_plugin_name(), $this->get_version(), $this->simda);

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$this->loader->add_action('wp_ajax_singkron_akun_belanja',  $plugin_public, 'singkron_akun_belanja');
		$this->loader->add_action('wp_ajax_nopriv_singkron_akun_belanja',  $plugin_public, 'singkron_akun_belanja');

		$this->loader->add_action('wp_ajax_singkron_ssh',  $plugin_public, 'singkron_ssh');
		$this->loader->add_action('wp_ajax_nopriv_singkron_ssh',  $plugin_public, 'singkron_ssh');

		$this->loader->add_action('wp_ajax_singkron_rka',  $plugin_public, 'singkron_rka');
		$this->loader->add_action('wp_ajax_nopriv_singkron_rka',  $plugin_public, 'singkron_rka');

		$this->loader->add_action('wp_ajax_singkron_unit',  $plugin_public, 'singkron_unit');
		$this->loader->add_action('wp_ajax_nopriv_singkron_unit',  $plugin_public, 'singkron_unit');

		$this->loader->add_action('wp_ajax_get_cat_url',  $plugin_public, 'get_cat_url');
		$this->loader->add_action('wp_ajax_nopriv_get_cat_url',  $plugin_public, 'get_cat_url');

		$this->loader->add_action('wp_ajax_set_unit_pagu',  $plugin_public, 'set_unit_pagu');
		$this->loader->add_action('wp_ajax_nopriv_set_unit_pagu',  $plugin_public, 'set_unit_pagu');

		$this->loader->add_action('wp_ajax_singkron_data_giat',  $plugin_public, 'singkron_data_giat');
		$this->loader->add_action('wp_ajax_nopriv_singkron_data_giat',  $plugin_public, 'singkron_data_giat');

		$this->loader->add_action('wp_ajax_singkron_sumber_dana',  $plugin_public, 'singkron_sumber_dana');
		$this->loader->add_action('wp_ajax_nopriv_singkron_sumber_dana',  $plugin_public, 'singkron_sumber_dana');

		$this->loader->add_action('wp_ajax_singkron_data_rpjmd',  $plugin_public, 'singkron_data_rpjmd');
		$this->loader->add_action('wp_ajax_nopriv_singkron_data_rpjmd',  $plugin_public, 'singkron_data_rpjmd');

		$this->loader->add_action('wp_ajax_singkron_penerima_bantuan',  $plugin_public, 'singkron_penerima_bantuan');
		$this->loader->add_action('wp_ajax_nopriv_singkron_penerima_bantuan',  $plugin_public, 'singkron_penerima_bantuan');

		$this->loader->add_action('wp_ajax_singkron_alamat',  $plugin_public, 'singkron_alamat');
		$this->loader->add_action('wp_ajax_nopriv_singkron_alamat',  $plugin_public, 'singkron_alamat');

		$this->loader->add_action('wp_ajax_singkron_user_deskel',  $plugin_public, 'singkron_user_deskel');
		$this->loader->add_action('wp_ajax_nopriv_singkron_user_deskel',  $plugin_public, 'singkron_user_deskel');

		$this->loader->add_action('wp_ajax_singkron_user_dewan',  $plugin_public, 'singkron_user_dewan');
		$this->loader->add_action('wp_ajax_nopriv_singkron_user_dewan',  $plugin_public, 'singkron_user_dewan');

		$this->loader->add_action('wp_ajax_singkron_pengaturan_sipd',  $plugin_public, 'singkron_pengaturan_sipd');
		$this->loader->add_action('wp_ajax_nopriv_singkron_pengaturan_sipd',  $plugin_public, 'singkron_pengaturan_sipd');

		$this->loader->add_action('wp_ajax_singkron_user_penatausahaan',  $plugin_public, 'singkron_user_penatausahaan');
		$this->loader->add_action('wp_ajax_nopriv_singkron_user_penatausahaan',  $plugin_public, 'singkron_user_penatausahaan');

		$this->loader->add_action('wp_ajax_singkron_renstra',  $plugin_public, 'singkron_renstra');
		$this->loader->add_action('wp_ajax_nopriv_singkron_renstra',  $plugin_public, 'singkron_renstra');

		$this->loader->add_action('wp_ajax_get_unit',  $plugin_public, 'get_unit');
		$this->loader->add_action('wp_ajax_nopriv_get_unit',  $plugin_public, 'get_unit');

		$this->loader->add_action('wp_ajax_get_indikator',  $plugin_public, 'get_indikator');
		$this->loader->add_action('wp_ajax_nopriv_get_indikator',  $plugin_public, 'get_indikator');

		$this->loader->add_action('wp_ajax_get_kas',  $plugin_public, 'get_kas');
		$this->loader->add_action('wp_ajax_nopriv_get_kas',  $plugin_public, 'get_kas');

		$this->loader->add_action('wp_ajax_get_kas_fmis',  $plugin_public, 'get_kas_fmis');
		$this->loader->add_action('wp_ajax_nopriv_get_kas_fmis',  $plugin_public, 'get_kas_fmis');

		$this->loader->add_action('wp_ajax_get_all_sub_unit',  $plugin_public, 'get_all_sub_unit');
		$this->loader->add_action('wp_ajax_nopriv_get_all_sub_unit',  $plugin_public, 'get_all_sub_unit');

		$this->loader->add_action('wp_ajax_singkron_anggaran_kas',  $plugin_public, 'singkron_anggaran_kas');
		$this->loader->add_action('wp_ajax_nopriv_singkron_anggaran_kas',  $plugin_public, 'singkron_anggaran_kas');

		$this->loader->add_action('wp_ajax_singkron_pendapatan',  $plugin_public, 'singkron_pendapatan');
		$this->loader->add_action('wp_ajax_nopriv_singkron_pendapatan',  $plugin_public, 'singkron_pendapatan');

		$this->loader->add_action('wp_ajax_singkron_pembiayaan',  $plugin_public, 'singkron_pembiayaan');
		$this->loader->add_action('wp_ajax_nopriv_singkron_pembiayaan',  $plugin_public, 'singkron_pembiayaan');

		$this->loader->add_action('wp_ajax_singkron_asmas',  $plugin_public, 'singkron_asmas');
		$this->loader->add_action('wp_ajax_nopriv_singkron_asmas',  $plugin_public, 'singkron_asmas');

		$this->loader->add_action('wp_ajax_singkron_pokir',  $plugin_public, 'singkron_pokir');
		$this->loader->add_action('wp_ajax_nopriv_singkron_pokir',  $plugin_public, 'singkron_pokir');

		$this->loader->add_action('wp_ajax_get_up',  $plugin_public, 'get_up');
		$this->loader->add_action('wp_ajax_nopriv_get_up',  $plugin_public, 'get_up');

		$this->loader->add_action('wp_ajax_get_link_laporan',  $plugin_public, 'get_link_laporan');
		$this->loader->add_action('wp_ajax_nopriv_get_link_laporan',  $plugin_public, 'get_link_laporan');

		$this->loader->add_action('wp_ajax_get_data_rka',  $plugin_public, 'get_data_rka');
		$this->loader->add_action('wp_ajax_nopriv_get_data_rka',  $plugin_public, 'get_data_rka');

		$this->loader->add_action('wp_ajax_mapping_rek_fmis',  $plugin_public, 'mapping_rek_fmis');
		$this->loader->add_action('wp_ajax_nopriv_mapping_rek_fmis',  $plugin_public, 'mapping_rek_fmis');

		$this->loader->add_action('wp_ajax_mapping_sub_kegiatan_fmis',  $plugin_public, 'mapping_sub_kegiatan_fmis');
		$this->loader->add_action('wp_ajax_nopriv_mapping_sub_kegiatan_fmis',  $plugin_public, 'mapping_sub_kegiatan_fmis');

		$this->loader->add_action('wp_ajax_get_data_pendapatan',  $plugin_public, 'get_data_pendapatan');
		$this->loader->add_action('wp_ajax_nopriv_get_data_pendapatan',  $plugin_public, 'get_data_pendapatan');

		$this->loader->add_action('wp_ajax_get_data_pembiayaan',  $plugin_public, 'get_data_pembiayaan');
		$this->loader->add_action('wp_ajax_nopriv_get_data_pembiayaan',  $plugin_public, 'get_data_pembiayaan');

		$this->loader->add_action('wp_ajax_singkroniasi_total_sub_keg_fmis',  $plugin_public, 'singkroniasi_total_sub_keg_fmis');
		$this->loader->add_action('wp_ajax_nopriv_singkroniasi_total_sub_keg_fmis',  $plugin_public, 'singkroniasi_total_sub_keg_fmis');

		$this->loader->add_action('wp_ajax_get_mandatory_spending_link',  $plugin_public, 'get_mandatory_spending_link');
		$this->loader->add_action('wp_ajax_nopriv_get_mandatory_spending_link',  $plugin_public, 'get_mandatory_spending_link');

		$this->loader->add_action('wp_ajax_update_nonactive_sub_bl',  $plugin_public, 'update_nonactive_sub_bl');
		$this->loader->add_action('wp_ajax_nopriv_update_nonactive_sub_bl',  $plugin_public, 'update_nonactive_sub_bl');

		$this->loader->add_action('wp_ajax_update_nonactive_sub_bl_kas',  $plugin_public, 'update_nonactive_sub_bl_kas');
		$this->loader->add_action('wp_ajax_nopriv_update_nonactive_sub_bl_kas',  $plugin_public, 'update_nonactive_sub_bl_kas');

		$this->loader->add_action('wp_ajax_singkron_pendahuluan',  $plugin_public, 'singkron_pendahuluan');
		$this->loader->add_action('wp_ajax_nopriv_singkron_pendahuluan',  $plugin_public, 'singkron_pendahuluan');

		$this->loader->add_action('wp_ajax_non_active_user',  $plugin_public, 'non_active_user');
		$this->loader->add_action('wp_ajax_nopriv_non_active_user',  $plugin_public, 'non_active_user');

		$this->loader->add_action('wp_ajax_get_mapping',  $plugin_public, 'get_mapping');
		$this->loader->add_action('wp_ajax_nopriv_get_mapping',  $plugin_public, 'get_mapping');

		$this->loader->add_action('wp_ajax_generate_user_sipd_merah',  $plugin_public, 'generate_user_sipd_merah');
		$this->loader->add_action('wp_ajax_nopriv_generate_user_sipd_merah',  $plugin_public, 'generate_user_sipd_merah');

		$this->loader->add_action('wp_ajax_singkron_renstra_tujuan',  $plugin_public, 'singkron_renstra_tujuan');
		$this->loader->add_action('wp_ajax_nopriv_singkron_renstra_tujuan',  $plugin_public, 'singkron_renstra_tujuan');

		$this->loader->add_action('wp_ajax_singkron_renstra_sasaran',  $plugin_public, 'singkron_renstra_sasaran');
		$this->loader->add_action('wp_ajax_nopriv_singkron_renstra_sasaran',  $plugin_public, 'singkron_renstra_sasaran');

		$this->loader->add_action('wp_ajax_singkron_renstra_program',  $plugin_public, 'singkron_renstra_program');
		$this->loader->add_action('wp_ajax_nopriv_singkron_renstra_program',  $plugin_public, 'singkron_renstra_program');

		$this->loader->add_action('wp_ajax_singkron_renstra_kegiatan',  $plugin_public, 'singkron_renstra_kegiatan');
		$this->loader->add_action('wp_ajax_nopriv_singkron_renstra_kegiatan',  $plugin_public, 'singkron_renstra_kegiatan');

		$this->loader->add_action('wp_ajax_get_realisasi_akun',  $plugin_public, 'get_realisasi_akun');
		$this->loader->add_action('wp_ajax_nopriv_get_realisasi_akun',  $plugin_public, 'get_realisasi_akun');

		$this->loader->add_action('wp_ajax_get_url_page',  $plugin_public, 'get_url_page');
		$this->loader->add_action('wp_ajax_nopriv_get_url_page',  $plugin_public, 'get_url_page');

		$this->loader->add_action('wp_ajax_save_monev_renja',  $plugin_public, 'save_monev_renja');
		$this->loader->add_action('wp_ajax_nopriv_save_monev_renja',  $plugin_public, 'save_monev_renja');

		$this->loader->add_action('wp_ajax_singkron_skpd_mitra_bappeda',  $plugin_public, 'singkron_skpd_mitra_bappeda');
		$this->loader->add_action('wp_ajax_nopriv_singkron_skpd_mitra_bappeda',  $plugin_public, 'singkron_skpd_mitra_bappeda');

		$this->loader->add_action('wp_ajax_get_ssh',  $plugin_public, 'get_ssh');
		$this->loader->add_action('wp_ajax_nopriv_get_ssh',  $plugin_public, 'get_ssh');

		$this->loader->add_action('wp_ajax_get_ssh_fmis',  $plugin_public, 'get_ssh_fmis');
		$this->loader->add_action('wp_ajax_nopriv_get_ssh_fmis',  $plugin_public, 'get_ssh_fmis');

		$this->loader->add_action('wp_ajax_get_skpd',  $plugin_public, 'get_skpd');
		$this->loader->add_action('wp_ajax_nopriv_get_skpd',  $plugin_public, 'get_skpd');

		$this->loader->add_action('wp_ajax_get_skpd_fmis',  $plugin_public, 'get_skpd_fmis');
		$this->loader->add_action('wp_ajax_nopriv_get_skpd_fmis',  $plugin_public, 'get_skpd_fmis');

		$this->loader->add_action('wp_ajax_get_meta_subunit_simda',  $plugin_public, 'get_meta_subunit_simda');
		$this->loader->add_action('wp_ajax_nopriv_get_meta_subunit_simda',  $plugin_public, 'get_meta_subunit_simda');

		$this->loader->add_action('wp_ajax_get_sub_keg',  $plugin_public, 'get_sub_keg');
		$this->loader->add_action('wp_ajax_nopriv_get_sub_keg',  $plugin_public, 'get_sub_keg');

		$this->loader->add_action('wp_ajax_get_sub_keg_rka',  $plugin_public, 'get_sub_keg_rka');
		$this->loader->add_action('wp_ajax_nopriv_get_sub_keg_rka',  $plugin_public, 'get_sub_keg_rka');

		$this->loader->add_action('wp_ajax_get_sumber_dana',  $plugin_public, 'get_sumber_dana');
		$this->loader->add_action('wp_ajax_nopriv_get_sumber_dana',  $plugin_public, 'get_sumber_dana');

		$this->loader->add_action('wp_ajax_mapping_skpd_fmis',  $plugin_public, 'mapping_skpd_fmis');
		$this->loader->add_action('wp_ajax_nopriv_mapping_skpd_fmis',  $plugin_public, 'mapping_skpd_fmis');

		$this->loader->add_action('wp_ajax_get_spp',  $plugin_public, 'get_spp');
		$this->loader->add_action('wp_ajax_nopriv_get_spp',  $plugin_public, 'get_spp');

		$this->loader->add_action('wp_ajax_get_spm',  $plugin_public, 'get_spm');
		$this->loader->add_action('wp_ajax_nopriv_get_spm',  $plugin_public, 'get_spm');

		$this->loader->add_action('wp_ajax_get_sp2d',  $plugin_public, 'get_sp2d');
		$this->loader->add_action('wp_ajax_nopriv_get_sp2d',  $plugin_public, 'get_sp2d');

		$this->loader->add_action('wp_ajax_get_spd',  $plugin_public, 'get_spd');
		$this->loader->add_action('wp_ajax_nopriv_get_spd',  $plugin_public, 'get_spd');

		$this->loader->add_action('wp_ajax_get_spd_rinci',  $plugin_public, 'get_spd_rinci');
		$this->loader->add_action('wp_ajax_nopriv_get_spd_rinci',  $plugin_public, 'get_spd_rinci');

		$this->loader->add_action('wp_ajax_get_spp_rinci',  $plugin_public, 'get_spp_rinci');
		$this->loader->add_action('wp_ajax_nopriv_get_spp_rinci',  $plugin_public, 'get_spp_rinci');

		$this->loader->add_action('wp_ajax_get_tagihan',  $plugin_public, 'get_tagihan');
		$this->loader->add_action('wp_ajax_nopriv_get_tagihan',  $plugin_public, 'get_tagihan');

		$this->loader->add_action('wp_ajax_get_sp2b',  $plugin_public, 'get_sp2b');
		$this->loader->add_action('wp_ajax_nopriv_get_sp2b',  $plugin_public, 'get_sp2b');

		$this->loader->add_action('wp_ajax_get_sp2b_rinci',  $plugin_public, 'get_sp2b_rinci');
		$this->loader->add_action('wp_ajax_nopriv_get_sp2b_rinci',  $plugin_public, 'get_sp2b_rinci');

		$this->loader->add_action('wp_ajax_get_pegawai_simda',  $plugin_public, 'get_pegawai_simda');
		$this->loader->add_action('wp_ajax_nopriv_get_pegawai_simda',  $plugin_public, 'get_pegawai_simda');

		$this->loader->add_action('wp_ajax_singkroniasi_spd_fmis',  $plugin_public, 'singkroniasi_spd_fmis');
		$this->loader->add_action('wp_ajax_nopriv_singkroniasi_spd_fmis',  $plugin_public, 'singkroniasi_spd_fmis');

		$this->loader->add_action('wp_ajax_singkron_kategori_ssh',  $plugin_public, 'singkron_kategori_ssh');
		$this->loader->add_action('wp_ajax_nopriv_singkron_kategori_ssh',  $plugin_public, 'singkron_kategori_ssh');

		$this->loader->add_action('wp_ajax_singkron_satuan',  $plugin_public, 'singkron_satuan');
		$this->loader->add_action('wp_ajax_nopriv_singkron_satuan',  $plugin_public, 'singkron_satuan');

		$this->loader->add_action('wp_ajax_cek_api_key',  $plugin_public, 'cek_api_key');
		$this->loader->add_action('wp_ajax_nopriv_cek_api_key',  $plugin_public, 'cek_api_key');

		$this->loader->add_action('wp_ajax_get_usulan_ssh_sipd',  $plugin_public, 'get_usulan_ssh_sipd');
		$this->loader->add_action('wp_ajax_nopriv_get_usulan_ssh_sipd',  $plugin_public, 'get_usulan_ssh_sipd');

		$this->loader->add_action('wp_ajax_update_usulan_ssh_sipd',  $plugin_public, 'update_usulan_ssh_sipd');
		$this->loader->add_action('wp_ajax_nopriv_update_usulan_ssh_sipd',  $plugin_public, 'update_usulan_ssh_sipd');

		$this->loader->add_action('wp_ajax_get_data_ssh_sipd',  $plugin_public, 'get_data_ssh_sipd');
		$this->loader->add_action('wp_ajax_nopriv_get_data_ssh_sipd',  $plugin_public, 'get_data_ssh_sipd');

		$this->loader->add_action('wp_ajax_get_kontrak',  $plugin_public, 'get_kontrak');
		$this->loader->add_action('wp_ajax_nopriv_get_kontrak',  $plugin_public, 'get_kontrak');

		$this->loader->add_action('wp_ajax_get_kontrak_addendum',  $plugin_public, 'get_kontrak_addendum');
		$this->loader->add_action('wp_ajax_nopriv_get_kontrak_addendum',  $plugin_public, 'get_kontrak_addendum');

		$this->loader->add_action('wp_ajax_get_data_ssh_sipd_by_id',  $plugin_public, 'get_data_ssh_sipd_by_id');
		$this->loader->add_action('wp_ajax_nopriv_get_data_ssh_sipd_by_id',  $plugin_public, 'get_data_ssh_sipd_by_id');

		$this->loader->add_action('wp_ajax_get_visi_rpjm',  $plugin_public, 'get_visi_rpjm');
		$this->loader->add_action('wp_ajax_nopriv_get_visi_rpjm',  $plugin_public, 'get_visi_rpjm');

		$this->loader->add_action('wp_ajax_singkron_data_rpd',  $plugin_public, 'singkron_data_rpd');
		$this->loader->add_action('wp_ajax_nopriv_singkron_data_rpd',  $plugin_public, 'singkron_data_rpd');

		$this->loader->add_action('wp_ajax_get_rpd',  $plugin_public, 'get_rpd');
		$this->loader->add_action('wp_ajax_nopriv_get_rpd',  $plugin_public, 'get_rpd');

		$this->loader->add_action('wp_ajax_get_rpjpd',  $plugin_public, 'get_rpjpd');
		$this->loader->add_action('wp_ajax_nopriv_get_rpjpd',  $plugin_public, 'get_rpjpd');

		$this->loader->add_action('wp_ajax_mapping_satuan_kerja_sirup',  $plugin_public, 'mapping_satuan_kerja_sirup');
		$this->loader->add_action('wp_ajax_nopriv_mapping_satuan_kerja_sirup',  $plugin_public, 'mapping_satuan_kerja_sirup');

		$this->loader->add_action('wp_ajax_simpan_rfk',  $plugin_public, 'simpan_rfk');
		$this->loader->add_action('wp_ajax_reset_rfk',  $plugin_public, 'reset_rfk');
		$this->loader->add_action('wp_ajax_reset_catatan_verifkator_rfk',  $plugin_public, 'reset_catatan_verifkator_rfk');
		$this->loader->add_action('wp_ajax_get_monev',  $plugin_public, 'get_monev');
		$this->loader->add_action('wp_ajax_save_monev_renja_triwulan',  $plugin_public, 'save_monev_renja_triwulan');
		$this->loader->add_action('wp_ajax_simpan_catatan_rfk_unit',  $plugin_public, 'simpan_catatan_rfk_unit');
		$this->loader->add_action('wp_ajax_get_data_rpjm',  $plugin_public, 'get_data_rpjm');
		$this->loader->add_action('wp_ajax_get_rka_simda',  $plugin_public, 'get_rka_simda');
		$this->loader->add_action('wp_ajax_get_dpa_simda',  $plugin_public, 'get_dpa_simda');

		$this->loader->add_action('wp_ajax_reset_rfk_pemda',  $plugin_public, 'reset_rfk_pemda');
		$this->loader->add_action('wp_ajax_get_monev_renstra',  $plugin_public, 'get_monev_renstra');
		$this->loader->add_action('wp_ajax_save_monev_renstra',  $plugin_public, 'save_monev_renstra');
		
		$this->loader->add_action('wp_ajax_get_sumber_dana_mapping',  $plugin_public, 'get_sumber_dana_mapping');
		$this->loader->add_action('wp_ajax_get_rincian_sumber_dana_mapping',  $plugin_public, 'get_rincian_sumber_dana_mapping');

		$this->loader->add_action('wp_ajax_get_data_usulan_ssh',  $plugin_public, 'get_data_usulan_ssh');
		$this->loader->add_action('wp_ajax_get_data_satuan_ssh',  $plugin_public, 'get_data_satuan_ssh');
		$this->loader->add_action('wp_ajax_get_data_kategori_ssh',  $plugin_public, 'get_data_kategori_ssh');
		$this->loader->add_action('wp_ajax_get_data_akun_ssh',  $plugin_public, 'get_data_akun_ssh');
		$this->loader->add_action('wp_ajax_get_data_nama_ssh',  $plugin_public, 'get_data_nama_ssh');
		$this->loader->add_action('wp_ajax_submit_usulan_ssh',  $plugin_public, 'submit_usulan_ssh');
		$this->loader->add_action('wp_ajax_get_data_usulan_ssh_by_komponen',  $plugin_public, 'get_data_usulan_ssh_by_komponen');
		$this->loader->add_action('wp_ajax_get_komponen_and_id_kel_ssh',  $plugin_public, 'get_komponen_and_id_kel_ssh');
		$this->loader->add_action('wp_ajax_submit_tambah_harga_ssh',  $plugin_public, 'submit_tambah_harga_ssh');
		$this->loader->add_action('wp_ajax_submit_verify_ssh',  $plugin_public, 'submit_verify_ssh');
		$this->loader->add_action('wp_ajax_get_data_ssh_usulan_by_id',  $plugin_public, 'get_data_ssh_usulan_by_id');
		$this->loader->add_action('wp_ajax_submit_add_new_akun_ssh_usulan',  $plugin_public, 'submit_add_new_akun_ssh_usulan');
		$this->loader->add_action('wp_ajax_get_data_ssh_analisis',  $plugin_public, 'get_data_ssh_analisis');
		$this->loader->add_action('wp_ajax_get_data_chart_ssh',  $plugin_public, 'get_data_chart_ssh');
		$this->loader->add_action('wp_ajax_get_data_ssh_analisis_skpd',  $plugin_public, 'get_data_ssh_analisis_skpd');
		$this->loader->add_action('wp_ajax_get_data_chart_ssh_skpd',  $plugin_public, 'get_data_chart_ssh_skpd');
		$this->loader->add_action('wp_ajax_submit_edit_usulan_ssh',  $plugin_public, 'submit_edit_usulan_ssh');
		$this->loader->add_action('wp_ajax_submit_delete_usulan_ssh',  $plugin_public, 'submit_delete_usulan_ssh');
		$this->loader->add_action('wp_ajax_submit_delete_akun_usulan_ssh',  $plugin_public, 'submit_delete_akun_usulan_ssh');
		$this->loader->add_action('wp_ajax_submit_delete_check_usulan_ssh',  $plugin_public, 'submit_delete_check_usulan_ssh');
		$this->loader->add_action('wp_ajax_submit_approve_check_usulan_ssh',  $plugin_public, 'submit_approve_check_usulan_ssh');
		$this->loader->add_action('wp_ajax_submit_tambah_akun_ssh',  $plugin_public, 'submit_tambah_akun_ssh');
		$this->loader->add_action('wp_ajax_submit_edit_tambah_harga_ssh', $plugin_public, 'submit_edit_tambah_harga_ssh');
		$this->loader->add_action('wp_ajax_submit_edit_tambah_akun_ssh', $plugin_public, 'submit_edit_tambah_akun_ssh');
		$this->loader->add_action('wp_ajax_get_data_penjadwalan', $plugin_public, 'get_data_penjadwalan');
		$this->loader->add_action('wp_ajax_submit_add_schedule',  $plugin_public, 'submit_add_schedule');
		$this->loader->add_action('wp_ajax_get_data_jadwal_by_id',  $plugin_public, 'get_data_jadwal_by_id');
		$this->loader->add_action('wp_ajax_submit_edit_schedule',  $plugin_public, 'submit_edit_schedule');
		$this->loader->add_action('wp_ajax_submit_delete_schedule',  $plugin_public, 'submit_delete_schedule');
		$this->loader->add_action('wp_ajax_submit_lock_schedule',  $plugin_public, 'submit_lock_schedule');
		$this->loader->add_action('wp_ajax_run_sql_migrate',  $plugin_public, 'run_sql_migrate');
		$this->loader->add_action('wp_ajax_singkron_rpjmd_sipd_lokal',  $plugin_public, 'singkron_rpjmd_sipd_lokal');
		$this->loader->add_action('wp_ajax_singkron_rpd_sipd_lokal',  $plugin_public, 'singkron_rpd_sipd_lokal');
		$this->loader->add_action('wp_ajax_singkron_rpjpd_sipd_lokal',  $plugin_public, 'singkron_rpjpd_sipd_lokal');
		$this->loader->add_action('wp_ajax_submit_lock_schedule_rpjm',  $plugin_public, 'submit_lock_schedule_rpjm');
		$this->loader->add_action('wp_ajax_submit_lock_schedule_renstra',  $plugin_public, 'submit_lock_schedule_renstra');
		$this->loader->add_action('wp_ajax_submit_lock_schedule_rpd',  $plugin_public, 'submit_lock_schedule_rpd');

		$this->loader->add_action('wp_ajax_simpan_rpjpd',  $plugin_public, 'simpan_rpjpd');
		$this->loader->add_action('wp_ajax_simpan_rpd',  $plugin_public, 'simpan_rpd');
		$this->loader->add_action('wp_ajax_hapus_rpjpd',  $plugin_public, 'hapus_rpjpd');
		$this->loader->add_action('wp_ajax_hapus_rpd',  $plugin_public, 'hapus_rpd');
		$this->loader->add_action('wp_ajax_get_data_monitoring_rup',  $plugin_public, 'get_data_monitoring_rup');
		
		$this->loader->add_action('wp_ajax_submit_visi_rpjm',  $plugin_public, 'submit_visi_rpjm');
		$this->loader->add_action('wp_ajax_edit_visi_rpjm',  $plugin_public, 'edit_visi_rpjm');
		$this->loader->add_action('wp_ajax_update_visi_rpjm',  $plugin_public, 'update_visi_rpjm');
		$this->loader->add_action('wp_ajax_delete_visi_rpjm',  $plugin_public, 'delete_visi_rpjm');
		
		$this->loader->add_action('wp_ajax_get_misi_rpjm',  $plugin_public, 'get_misi_rpjm');
		$this->loader->add_action('wp_ajax_submit_misi_rpjm',  $plugin_public, 'submit_misi_rpjm');
		$this->loader->add_action('wp_ajax_edit_misi_rpjm',  $plugin_public, 'edit_misi_rpjm');
		$this->loader->add_action('wp_ajax_update_misi_rpjm',  $plugin_public, 'update_misi_rpjm');
		$this->loader->add_action('wp_ajax_delete_misi_rpjm',  $plugin_public, 'delete_misi_rpjm');
		
		$this->loader->add_action('wp_ajax_get_tujuan_rpjm',  $plugin_public, 'get_tujuan_rpjm');
		$this->loader->add_action('wp_ajax_submit_tujuan_rpjm',  $plugin_public, 'submit_tujuan_rpjm');
		$this->loader->add_action('wp_ajax_edit_tujuan_rpjm',  $plugin_public, 'edit_tujuan_rpjm');
		$this->loader->add_action('wp_ajax_update_tujuan_rpjm',  $plugin_public, 'update_tujuan_rpjm');
		$this->loader->add_action('wp_ajax_delete_tujuan_rpjm',  $plugin_public, 'delete_tujuan_rpjm');
		
		$this->loader->add_action('wp_ajax_get_indikator_tujuan_rpjm',  $plugin_public, 'get_indikator_tujuan_rpjm');
		$this->loader->add_action('wp_ajax_submit_indikator_tujuan_rpjm',  $plugin_public, 'submit_indikator_tujuan_rpjm');
		$this->loader->add_action('wp_ajax_edit_indikator_tujuan_rpjm',  $plugin_public, 'edit_indikator_tujuan_rpjm');
		$this->loader->add_action('wp_ajax_update_indikator_tujuan_rpjm',  $plugin_public, 'update_indikator_tujuan_rpjm');
		$this->loader->add_action('wp_ajax_delete_indikator_tujuan_rpjm',  $plugin_public, 'delete_indikator_tujuan_rpjm');

		$this->loader->add_action('wp_ajax_get_sasaran_rpjm',  $plugin_public, 'get_sasaran_rpjm');
		$this->loader->add_action('wp_ajax_submit_sasaran_rpjm',  $plugin_public, 'submit_sasaran_rpjm');
		$this->loader->add_action('wp_ajax_edit_sasaran_rpjm',  $plugin_public, 'edit_sasaran_rpjm');
		$this->loader->add_action('wp_ajax_update_sasaran_rpjm',  $plugin_public, 'update_sasaran_rpjm');
		$this->loader->add_action('wp_ajax_delete_sasaran_rpjm',  $plugin_public, 'delete_sasaran_rpjm');

		$this->loader->add_action('wp_ajax_get_indikator_sasaran_rpjm',  $plugin_public, 'get_indikator_sasaran_rpjm');
		$this->loader->add_action('wp_ajax_submit_indikator_sasaran_rpjm',  $plugin_public, 'submit_indikator_sasaran_rpjm');
		$this->loader->add_action('wp_ajax_edit_indikator_sasaran_rpjm',  $plugin_public, 'edit_indikator_sasaran_rpjm');
		$this->loader->add_action('wp_ajax_update_indikator_sasaran_rpjm',  $plugin_public, 'update_indikator_sasaran_rpjm');
		$this->loader->add_action('wp_ajax_delete_indikator_sasaran_rpjm',  $plugin_public, 'delete_indikator_sasaran_rpjm');

		$this->loader->add_action('wp_ajax_get_program_rpjm',  $plugin_public, 'get_program_rpjm');
		$this->loader->add_action('wp_ajax_add_program_rpjm',  $plugin_public, 'add_program_rpjm');
		$this->loader->add_action('wp_ajax_submit_program_rpjm',  $plugin_public, 'submit_program_rpjm');
		$this->loader->add_action('wp_ajax_edit_program_rpjm',  $plugin_public, 'edit_program_rpjm');
		$this->loader->add_action('wp_ajax_update_program_rpjm',  $plugin_public, 'update_program_rpjm');
		$this->loader->add_action('wp_ajax_delete_program_rpjm',  $plugin_public, 'delete_program_rpjm');
		
		$this->loader->add_action('wp_ajax_get_indikator_program_rpjm',  $plugin_public, 'get_indikator_program_rpjm');
		$this->loader->add_action('wp_ajax_submit_indikator_program_rpjm',  $plugin_public, 'submit_indikator_program_rpjm');
		$this->loader->add_action('wp_ajax_edit_indikator_program_rpjm',  $plugin_public, 'edit_indikator_program_rpjm');
		$this->loader->add_action('wp_ajax_update_indikator_program_rpjm',  $plugin_public, 'update_indikator_program_rpjm');
		$this->loader->add_action('wp_ajax_delete_indikator_program_rpjm',  $plugin_public, 'delete_indikator_program_rpjm');

		add_shortcode('menu_monev',  array($plugin_public, 'menu_monev'));
		add_shortcode('datassh', array($plugin_public, 'datassh'));
		add_shortcode('rekbelanja', array($plugin_public, 'rekbelanja'));
		add_shortcode('tampilrka', array($plugin_public, 'tampilrka'));
		add_shortcode('tampilrkpd', array($plugin_public, 'tampilrkpd'));
		add_shortcode('apbdpenjabaran', array($plugin_public, 'apbdpenjabaran'));
		add_shortcode('monitor_sipd', array($plugin_public, 'monitor_sipd'));
		add_shortcode('monitor_rfk', array($plugin_public, 'monitor_rfk'));
		add_shortcode('monitor_monev_renja', array($plugin_public, 'monitor_monev_renja'));
		add_shortcode('monitor_sumber_dana', array($plugin_public, 'monitor_sumber_dana'));
		add_shortcode('monitor_label_komponen', array($plugin_public, 'monitor_label_komponen'));
		add_shortcode('monitor_daftar_sumber_dana', array($plugin_public, 'monitor_daftar_sumber_dana'));
		add_shortcode('monitor_daftar_label_komponen', array($plugin_public, 'monitor_daftar_label_komponen'));
		add_shortcode('monitor_monev_renstra', array($plugin_public, 'monitor_monev_renstra'));
		add_shortcode('monitor_monev_rpjm', array($plugin_public, 'monitor_monev_rpjm'));
		add_shortcode('data_ssh_usulan', array($plugin_public, 'data_ssh_usulan'));
		add_shortcode('data_ssh_sipd', array($plugin_public, 'data_ssh_sipd'));
		add_shortcode('data_halaman_menu_ssh', array($plugin_public, 'data_halaman_menu_ssh'));
		add_shortcode('monitoring_data_spd', array($plugin_public, 'monitoring_data_spd'));
		add_shortcode('monitor_satuan_harga', array($plugin_public, 'monitor_satuan_harga'));
		add_shortcode('laporan_per_item_ssh', array($plugin_public, 'laporan_per_item_ssh'));
		add_shortcode('monitoring_spd_rinci', array($plugin_public, 'monitoring_spd_rinci'));
		add_shortcode('setting_penjadwalan', array($plugin_public, 'setting_penjadwalan'));
		add_shortcode('input_rpjm', array($plugin_public, 'input_rpjm'));
		add_shortcode('jadwal_renja', array($plugin_public, 'jadwal_renja'));
		add_shortcode('monitoring_sql_migrate', array($plugin_public, 'monitoring_sql_migrate'));
		add_shortcode('jadwal_rpjm', array($plugin_public, 'jadwal_rpjm'));
		add_shortcode('jadwal_renstra', array($plugin_public, 'jadwal_renstra'));
		add_shortcode('input_rpd', array($plugin_public, 'input_rpd'));
		add_shortcode('input_rpjpd', array($plugin_public, 'input_rpjpd'));
		add_shortcode('jadwal_rpjpd', array($plugin_public, 'jadwal_rpjpd'));
		add_shortcode('jadwal_rpd', array($plugin_public, 'jadwal_rpd'));
		add_shortcode('monitoring_rup', array($plugin_public, 'monitoring_rup'));
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wpsipd_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
