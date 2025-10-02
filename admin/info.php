<?php include("inc_header.php") ?>
<!-- ketika info membuka get kata kunci dan ada isian dikata kunci tersebut maka nilainya akan dimasukan ke dalam variabel kata kunci jika tidak ada isinya maka diberi nilai 0 -->
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
            $sql1 = "delete from info where id = '$id'";
            $q1 = mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses = "Berhasil hapus data";
            }
         }

        ?> 
        <h1>info Admin</h1>
        <p>
            <a href="info_input.php">
                <input type="button" class="btn btn-primary" value="Buat info Baru"/>
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
    value="<?php echo $katakunci ?>" oninput="if(this.value===''){ window.location='info.php'; }">

            </div>
            <div class="col-auto">
                <input type="submit" name="cari" value="Cari Tulisan" class="btn btn-secondary"/>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-1">No</th>
                    <th>Judul</th>
                    <th class="col-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sqltambahan = "";
                $per_info = 2;
                if($katakunci !=''){
                    $array_katakunci = explode(" ",$katakunci);
                    for($x=0;$x < count($array_katakunci);$x++){
                        $sqlcari[] = "(judul like '%".$array_katakunci[$x]."%' or isi like '%".$array_katakunci[$x]."%' )";
                    }
                    $sqltambahan = " where ".implode(" or ",$sqlcari);
                }
                $sql1   = "select * from info $sqltambahan";
                $page   =  isset($_GET['page'])?(int)$_GET['page']:1;
                $mulai  =  ($page > 1) ? ($page * $per_info) - $per_info : 0;
                $q1     = mysqli_query($koneksi,$sql1);
                $total  = mysqli_num_rows($q1); 
                $pages  = ceil($total / $per_info);
                $nomor  = $mulai + 1;
                $sql1   = $sql1." order by id desc limit $mulai,$per_info";

                $q1     = mysqli_query($koneksi,$sql1);

                while ($r1 = mysqli_fetch_array($q1)) {
                    ?>
                        <tr>
                            <td><?php echo $nomor++ ?></td>
                             <td><?php echo $r1['judul'] ?></td>
                             <td>
                                <a href="info_input.php?id=<?php echo $r1['id']?>">
                                <span class="badge bg-warning text-dark">Edit</span>
                                </a>
                                <a href="info.php?op=delete&id=<?php echo $r1['id'] ?>" onclick="return confirm('Apakah yakin ingin menghapus data?')">
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
                        <a class="page-link" href="info.php?katakunci=<?php echo $katakunci ?>&cari= <?php echo $cari?>&page=<?php echo $i ?>"><?php echo $i ?></a>
                </li>
                <?php
                }
                ?>
            </ul>

        </nav>
        <?php include("inc_footer.php") ?>
