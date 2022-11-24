<?php
require_once "config.php";

	//include 'db/db_config.php';
	//$id = $_GET['id'];
	//if($db->delete('karyawan')->where('id_calon_kr='.$id)->count() == 1){
		//header('location:tampil_karyawan.php');
	//} else {
	//	header('location:tampil_karyawan.php?error_msg=error_delete');

//update karyawan set status=1 where id_calon_kr='.$id


	// connection
	$host = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'db_spk';

	//$koneksi = mysqli_connect($host,$username,$password);
$id = $_GET['id'];
//if( $sql = "UPDATE karyawan SET status='1' WHERE id_calon_kr=5"){
	//header('location:tampil_karyawan.php');
	//} else {
	//header('location:tampil_karyawan.php?error_msg=error_delete');}
$koneksi = mysqli_connect($host, $username, $password, $database);
// Check connection
if (!$koneksi) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "delete from karyawan WHERE id_calon_kr=$id";

if (mysqli_query($koneksi, $sql)) {
 header('location:tampil_karyawanrestore.php');
  echo "Record updated successfully";
} else {
header('location:tampil_karyawanrestore.php?error_msg=error_delete');
  echo "Error updating record: " . mysqli_error($koneksi);
}

?>