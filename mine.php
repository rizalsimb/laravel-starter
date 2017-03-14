
<?php
require('sys.php');

$link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1);

?>
<html>
<head>
<title>Mining Page</title>
</head>
<body>
    <h3>Instagram Followers - Get Coin</h3>
    <p>Anda akan mendapatkan random coin 1 / 4 / 0, semoga beruntung. Tombol tukar coin akan muncul ketika coin anda lebih dari/sama dengan <b>80</b>!</p>
    <p>
    <?php
    $f = false;
    $n = 0;
    
    $id = $_GET['id'];
    $a = $_GET['a'];
    $sec = $_GET['__fk'];
    if(!empty($id) AND !empty($a) AND !empty($sec)){
        if(md5($id . "696969" . $a) == $sec){
            $app = new payPro();
            $app->session = $a;
            $app->id = $id;
            
            if(isset($_POST['tukar'])){
                $tc = $_POST['__tc'];
                if($tc >= 20000){
                    $g = 'com.ty.vl.follower6';
                } else if($tc >= 6000){
                    $g = 'com.ty.vl.follower5';
                } else if($tc >= 2500){
                    $g = 'com.ty.vl.follower4';
                } else if($tc >= 500){
                    $g = 'com.ty.vl.follower3';
                } else if($tc >= 250){
                    $g = 'com.ty.vl.follower2';
                } else if($tc >= 80){
                    $g = 'com.ty.vl.follower1';
                } else {
                    $g = false;
                }
                if($g){
                    $v = $app->getFollowers($g);
                    if($v){
                        echo '<span style="color: green;">Success!</span> Followers berhasil dikirim, cek saja langsung!<br />';
                    } else {
                        echo '<span style="color: red;">Waho!</span> Ane bilang jangan maksain deh!<br />';
                    }
                } else {
                    echo '<span style="color: red;">Waho!</span> Coin lu kurang broh<br />';
                }
                
            }
            
            $gc = $app->getCoin(1, 100);
            echo "Jumlah Coin " . $gc; 
            if($gc >= 80) {
            	$f = true;
            	$n = $gc;
            	}
            if(is_array($gc) AND is_numeric($gc[1])){
                @file_put_contents("success.txt", $gc[0]);
                echo '<span style="color: green;">Success!</span> ['.$gc[0].'] Kamu mendapatkan ? coin. Coin kamu : '.$gc[5].'!';
                if($gc[1] >= 80){
                    $n = true;
                    $n = $gc[5];
                }
            } 
        } else {
            echo '<span style="color: red;">Waaaah!</span> Ambil auth dulu <a href="'.$link.'">disini</a> baru bisa gunain!';
        }
    } else {
        echo '<span style="color: red;">Addududuh!</span> Tolong jangan curang ya, semuanya sama!';
    }
    ?>
    </p>
    <?php
    if($f == true){
    ?>
    <form action="" method="POST">
        <input type="hidden" value="<?=$n;?>" name="__tc" />
        <input type="submit" name="tukar" value="Tukar Followers" />
    </form>  
    <?php
    }
    ?>
<head>
         <meta http-equiv="refresh" content="0.1">
</head>  
</body>
</html>