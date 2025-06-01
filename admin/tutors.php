<?php include("inc_header.php") ?>
<!-- ketika tutors membuka get kata kunci dan ada isian dikata kunci tersebut maka nilainya akan dimasukan ke dalam variabel kata kunci jika tidak ada isinya maka diberi nilai 0 -->
        <?php
        $sukses = "";
        $katakunci = (isset ($_GET['katakunci']))? trim($_GET['katakunci']):""; 
         if(isset($_GET['op'])){
            $op = $_GET['op'];
         }else{
            $op = "";
         }
         if($op == 'delete'){
            $id = $_GET['id'];
            $sql1 ="select foto from tutors where id = '$id'";
            $q1   = mysqli_query($koneksi,$sql1);
            $r1   = mysqli_fetch_array($q1);
            @unlink("../gambar/".$r1['foto']);

            $sql1 = "delete from tutors where id = '$id'";
            $q1 = mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses = "Berhasil hapus data";
            }
         }

        ?> 
        <h1>Halaman Admin Tutors</h1>
        <p>
            <a href="tutors_input.php">
                <input type="button" class="btn btn-primary" value="Buat Tutors Baru"/>
            </a>
        </p>
        <?php 
        if($sukses){
            ?>
                <div class="alert alert-primary" role="alert">
                    <?php echo $sukses ?>
                </div>
            <?php
        }
        ?>
        <form class="row g-3" method="get" action="">
            <div class="col-auto">
                <input type="text" name="katakunci" class="form-control" placeholder="Masukkan Kata Kunci"
                value="<?php echo $katakunci ?>" oninput="if(this.value===''){ window.location='tutors.php'; }">

            </div>
            <div class="col-auto">
                <input type="submit" name="cari" value="Cari Tulisan" class="btn btn-secondary"/>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-1">#</th>
                    <th class="col-2">Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th class="col-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sqltambahan = "";
                $per_halaman = 2;
                if($katakunci !=''){
                    $array_katakunci = explode(" ",$katakunci);
                    for($x=0;$x < count($array_katakunci);$x++){
                        $sqlcari[] = "(nama like '%".$array_katakunci[$x]."%' or isi like '%".$array_katakunci[$x]."%' )";
                    }
                    $sqltambahan = " where ".implode(" or ",$sqlcari);
                }
                $sql1   = "select * from tutors $sqltambahan";
                $page   =  isset($_GET['page'])?(int)$_GET['page']:1;
                $mulai  =  ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
                $q1     = mysqli_query($koneksi,$sql1);
                $total  = mysqli_num_rows($q1); 
                $pages  = ceil($total / $per_halaman);
                $nomor  = $mulai + 1;
                $sql1   = $sql1." order by id desc limit $mulai,$per_halaman";

                $q1     = mysqli_query($koneksi,$sql1);

                while ($r1 = mysqli_fetch_array($q1)) {
                    ?>
                        <tr>
                            <td><?php echo $nomor++ ?></td>
                             <td><img src="../gambar/<?php echo tutors_foto($r1['id']) ?>" style="max-height:100px;max-width:100px"/></td>
                             <td><?php echo $r1['nama'] ?></td>
                             <td><?php echo $r1['email'] ?></td>
                            <td><?php echo $r1['password'] ?></td>
                             <td>
                                <a href="tutors_input.php?id=<?php echo $r1['id']?>">
                                <span class="badge bg-warning text-dark">Edit</span>
                                </a>
                                <a href="tutors.php?op=delete&id=<?php echo $r1['id'] ?>" onclick="return confirm('Apakah yakin ingin menghapus data?')">
                                <span class="badge text-bg-danger">Delete</span>
                            </a>
                            </td>
                        </tr>
                    <?php
                }
                ?>
               
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php 
                $cari = isset($_GET['cari'])? $_GET['cari'] : "";

                    for($i=1; $i <= $pages; $i++){
                ?>
                <li class="page-item">
                        <a class="page-link" href="tutors.php?katakunci=<?php echo $katakunci ?>&cari= <?php echo $cari?>&page=<?php echo $i ?>"><?php echo $i ?></a>
                </li>
                <?php
                }
                ?>
            </ul>

        </nav>
        <?php include("inc_footer.php") ?>
