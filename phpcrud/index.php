<?php
$host      = "localhost";
$user      = "root";
$pass      = "";
$db        = "akademik";

$koneksi   = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
  die("Tidak bisa terkoneksi ke database");
}
$idmahasiswa  = "";
$nim          = "";
$nama         = "";
$jeniskelamin = "";
$alamat       = "";
$kota         = "";
$email        = "";
$sukses       = "";
$error        = "";

if (isset($_GET['op'])) {
  $op = $_GET['op'];
} else {
  $op = "";
}
if ($op == 'delete') {
  $idmahasiswa    = $_GET['id'];
  $sql1       = "delete from mahasiswa where idmahasiswa = '$idmahasiswa'";
  $q1         = mysqli_query($koneksi, $sql1);
  if ($q1) {
    $sukses = "Berhasil hapus data";
  } else {
    $error  = "Gagal melakukan delete data";
  }
}
if ($op == 'edit') 
{
  $idmahasiswa  = $_GET['id'];
  $sql1         = "select * from mahasiswa where idmahasiswa = '$idmahasiswa'";
  $q1           = mysqli_query($koneksi, $sql1);
  $r1           = mysqli_fetch_array($q1);
  $nim          = $r1['nim'];
  $nama         = $r1['nama'];
  $jeniskelamin = $r1['jeniskelamin'];
  $alamat       = $r1['alamat'];
  $kota         = $r1['kota'];
  $email        = $r1['email'];
  if ($nim == '') 
  {
    $error = "Data tidak ditemukan";
  }
}
if (isset($_POST['simpan'])) {
  $idmahasiswa  = $_GET['id'];
  $nim          = $_POST['nim'];
  $nama         = $_POST['nama'];
  $jeniskelamin = $_POST['jeniskelamin'];
  $alamat       = $_POST['alamat'];
  $kota         = $_POST['kota'];
  $email        = $_POST['email'];
  $foto         = $_FILES['foto']['name'];
  $tmp_name     = $_FILES['foto']['tmp_name'];

  $getfoto    = "uploads/" . basename($_FILES['foto']['name']);
  if (move_uploaded_file($_FILES['foto']['tmp_name'], $getfoto)) {
    $message = "Upload Berhasil";
  }
  if ($nim && $nama && $jeniskelamin && $alamat && $kota && $email && $foto) {
    if ($op == 'edit') {
      $sql1   = "update mahasiswa set nim = '$nim', nama = '$nama', jeniskelamin = '$jeniskelamin', alamat = '$alamat', kota = '$kota', email = '$email', foto = '$foto' where idmahasiswa = '$idmahasiswa'";
      $q1     = mysqli_query($koneksi, $sql1);
      if ($q1) {
        $sukses     = "Data berhasil diupdate";
      } else {
        $error      = "Data Gagal diupdate";
      }
    } else {
      $sql1   = "insert into mahasiswa(nim,nama,jeniskelamin,alamat,kota,email,foto) values ('$nim','$nama','$jeniskelamin','$alamat','$kota','$email','$foto')";
      $q1     = mysqli_query($koneksi, $sql1);
      if ($q1) {
        $sukses     = "Berhasil memasukkan data baru";
      } else {
        $error      = "Gagal memasukkan data";
      }
    }
  } else {
    $error = "Silakan masukkan semua data";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <style>
    .mx-auto {
      font-family: "Times New Romans";
      font-size: 20;
      padding: 15px;
      width: 800px;
    }
    label{
      color:black;
    }
    table{
      text-align: center;
      vertical-align: middle;
    }

    .card {
      padding: 10px;
      margin-top: 10px
    }
  </style>
</head>

<body>
  <div class="mx-auto">
    <div class="card">
      <div class="card-header text-white bg-primary">
        <center>
        <h2>Create / Edit Data</h2>
      </div>
      <div class="card-body">
        <?php
        if ($error) {
        ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $error ?>
          </div>
        <?php
          header("refresh:5;url=index.php"); //5 : detik
        }
        ?>
        <?php
        if ($sukses) {
        ?>
          <div class="alert alert-success" role="alert">
            <?php echo $sukses ?>
          </div>
        <?php
          header("refresh:5;url=index.php");
        }
        ?>
        <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3 row">
            <label for="idmahasiswa" class="col-sm-2 col-form-label">ID Mahasiswa</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="idmahasiswa" name="idmahasiswa" value="<?php echo $idmahasiswa ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="nim" class="col-sm-2 col-form-label">NIM</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="jeniskelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
            <div class="col-sm-10">
              <select class="form-control" name="jeniskelamin" id="jeniskelamin">
                <option value="">- Pilih Jenis Kelamin -</option>
                <option value="L" <?php if ($jeniskelamin == "L") echo "selected" ?>>L</option>
                <option value="P" <?php if ($jeniskelamin == "P") echo "selected" ?>>P</option>
              </select>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="kota" class="col-sm-2 col-form-label">Kota</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="kota" name="kota" value="<?php echo $kota ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="email" name="email" value="<?php echo $email ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="foto" class="col-sm-2 col-form-label">Foto</label>
            <div class="col-sm-10">
              <input type="file" class="form-control" id="foto" name="foto" value="<?php echo $foto ?>">
            </div>
          </div>
          <a class="col-12">
            <br>
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header text-white bg-primary">
        <center>
          <h2> Data Mahasiswa </h2>
        </center>
      </div>
      <div class="card-body">
        <table class="table table-bordered border-primary">
          <thead>
            <tr>
              <th scope="col">No</th>
              <th scope="col">ID Mahasiswa</th>
              <th scope="col">NIM</th>
              <th scope="col">Nama</th>
              <th scope="col">Jenis Kelamin</th>
              <th scope="col">Alamat</th>
              <th scope="col">Kota</th>
              <th scope="col">Email</th>
              <th scope="col">Foto</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql2   = "select * from mahasiswa order by idmahasiswa desc";
            $q2     = mysqli_query($koneksi, $sql2);
            $no   = 1;
            while ($r2 = mysqli_fetch_array($q2)) {
              $idmahasiswa  = $r2['idmahasiswa'];
              $nim          = $r2['nim'];
              $nama         = $r2['nama'];
              $jeniskelamin = $r2['jeniskelamin'];
              $alamat       = $r2['alamat'];
              $kota         = $r2['kota'];
              $email        = $r2['email'];
              $foto         = $r2['foto'];
            ?>
              <tr>
                <th scope="row"><?php echo $no++ ?></th>
                <td scope="row"><?php echo $idmahasiswa ?></td>
                <td scope="row"><?php echo $nim ?></td>
                <td scope="row"><?php echo $nama ?></td>
                <td scope="row"><?php echo $jeniskelamin ?></td>
                <td scope="row"><?php echo $alamat ?></td>
                <td scope="row"><?php echo $kota ?></td>
                <td scope="row"><?php echo $email ?></td>
                <td scope="row"><img src="uploads/<?php echo $foto ?>" width='100px' height='100px'></td>
                <td scope="row">
                  <a href="index.php?op=edit&id=<?php echo $idmahasiswa ?>"><button type="button" class="btn text-white btn-warning">Edit</button></a>
                  <a href="index.php?op=delete&id=<?php echo $idmahasiswa ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>