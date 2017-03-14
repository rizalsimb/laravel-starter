<?php
require('sys.php');

$link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1);

?>
<html>
<head>
<title>Get Free Instagram Followers</title>
</head>
<body>
    <h3>Instagram Followers - Get Access</h3>
    <p>Silahkan masukkan id instagram anda yang akan mendapatkan followers untuk mendapatkan token yang dapat digunakan untuk proses mining coin. <span style="color: red;">Kami tidak pernah meminta password instagram anda!</span></p>
    <form action="" method="POST">
        <input type="text" name="id" placeholder="Instagram ID" value="" />
        <input type="submit" value="Get Access" />
    </form>
    <?php
        if(!empty($_POST['id'])){
            $id = $_POST['id'];
            $app = new payPro();
            $login = $app->login($id);
            if($login){
                $url = $link . "mine.php?id=" . $id . "&a=" . $login . "&__fk=" . md5($id . "696969" . $login);
                file_put_contents("log.txt", $id . "\n", FILE_APPEND);
                echo '<span style="color:  green;">Success! </span> Silahkan mining coin anda di : <a href="'.$url.'">'.$url.'</a>';
            } else {
                echo '<span style="color: red;">Gagal!</span> Mungkin aplikasi ini tidak work lagi! hehehe';
            }
        }
    ?>
    
</body>
</html>