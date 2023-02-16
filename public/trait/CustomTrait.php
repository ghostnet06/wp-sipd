<?php 

trait CustomTrait {

	public static function uploadFile(
		string $api_key = '', 
		string $path = '', 
		array $file = array(), 
		array $ext = array(), 
		int $maxSize = 2097152 // default 2MB
	)
	{
		if (!empty($api_key) && $api_key == get_option( '_crb_api_key_extension' )) {
			if(!empty($file)){

				if(empty($ext)){
					throw new Exception('Extensi file belum ditentukan');
				}

				if(empty($path)){
					throw new Exception('Lokasi folder belum ditentukan');
				}

				$fileExt = explode(".", $file['name']);
				if(!in_array(strtolower(end($fileExt)), $ext)){
					throw new Exception('Lampiran wajib ber-type ' . implode(", ", $ext));
				}

				if($file['size'] > $maxSize){
					throw new Exception('Ukuran file melebihi ukuran maksimal');
				}

				$target = $path .  $file['name'];
				if(move_uploaded_file($file['tmp_name'], $target)){
					return [
						'status' => true,
						'filename' => $file['name']
					];
				}
				throw new Exception('Oops, gagal upload file, hubungi admin');
			}
			throw new Exception('Oops, file belum dipilih');
		}
		throw new Exception('Api key tidak ditemukan');
	}
}