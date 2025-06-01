<?php include("inc_header.php") ?>
<!-- ketika members membuka get kata kunci dan ada isian dikata kunci tersebut maka nilainya akan dimasukan ke dalam variabel kata kunci jika tidak ada isinya maka diberi nilai 0 -->
        <?php
        $sukses = "";
        $katakunci = (isset ($_GET['katakunci']))? trim($_GET['katakunci']):""; 
         if(isset($_GET['op'])){
            $op = $_GET['op'];
         }else{
            $op = "";
         }
         if($op == 'delete'){
            $sql1 = "delete from members where id = '$id'";
            $q1 = mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses = "Berhasil hapus data";
            }
         }

        ?> 
        <h1>Halaman Admin Members</h1>

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
    value="<?php echo $katakunci ?>" oninput="if(this.value===''){ window.location='members.php'; }">

            </div>
            <div class="col-auto">
                <input type="submit" name="cari" value="Cari Members" class="btn btn-secondary"/>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-1">#</th>
                    <th class="col-2">Email</th>
                    <th>Nama</th>
                    <th class="col-2">Status</th>
                    <th class="col-2">Langganan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sqltambahan = "";
                $per_halaman = 3;
                if($katakunci !=''){
                    $array_katakunci = explode(" ",$katakunci);
                    for($x=0;$x < count($array_katakunci);$x++){
                        $sqlcari[] = "(nama_lengkap like '%".$array_katakunci[$x]."%' or email like '%".$array_katakunci[$x]."%' )";
                    }
                    $sqltambahan = " where ".implode(" or ",$sqlcari);
                }
                $sql1   = "select * from members $sqltambahan";
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
                            <td><?php echo $r1['email'] ?></td>
                            <td><?php echo $r1['nama_lengkap'] ?></td>
                             <td>
                                <?php 
                                if ($r1['status'] == '1'){
                                    ?>
                                    <span class="badge bg-success">Aktif</span>
                                    <?php
                                }else{
                                    ?>
                                    <span class="badge bg-light">Belum Aktif</span>
                                    <?php
                                }
                                ?>
                            </td>
                             <td>
                                <?php 
                                if ($r1['is_langganan'] == '1'){
                                    ?>
                                    <span class="badge bg-success">Berlangganan</span>
                                    <?php
                                }else{
                                    ?>
                                    <span class="badge bg-light">Belum Berlangganan</span>
                                    <?php
                                }
                                ?>
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
                        <a class="page-link" href="members.php?katakunci=<?php echo $katakunci ?>&cari= <?php echo $cari?>&page=<?php echo $i ?>"><?php echo $i ?></a>
                </li>
                <?php
                }
                ?>
            </ul>

        </nav>
        <?php include("inc_footer.php") ?>
