

<?php

$a = new payPro();
//print_r($a->login("981061448")); exit;
//$a->session = '24e33bf29724a7d056b444bb2eefa29728d2076fccf94e08f3aa7a24b5edfd6c';
//$a->id = '981061448';

//$a->pushAction("50000");

class payPro {
    
    public $session = ';
    public $id = ';
    
    private $log = true;
    
    private $base = 'http://instalike.socialmarkets.info';
    
    public function getCoin($type = 0, $max = 0){
        if(!in_array($type, array(0, 1, 2))) return false;
        
        if($type == 2){
            $b = @file_get_contents("success.txt");
            if($b){
                $c = mt_rand(0, 1);
                if($c == 1){
                    $oid = $b - mt_rand(1, 9999);
                } else {
                    $oid = $b + mt_rand(1, 9999);
                }
            } else {
                $oid = '607' . mt_rand(1, 4) . mt_rand(0000, 9999);
            }
            
            $v = $this->pushAction($oid);
            if($v){
                return array($oid, $v);
            } else {
                return array($oid, false);
            }
        }        
        
        $base = $this->base . '/user/'.$this->id.'/getBoard/'.$type.'/' . $this->session;
        $respon = $this->request($base);
        $r = $respon;
        $respon = json_decode($respon);
        
        if(empty($respon->status)) return false;
        
        if($respon->status->status == 200){
            $x = 0;
            shuffle($respon->data->boardList);
            foreach($respon->data->boardList as $board){
                if($max <> 0 && $x >= $max) break;
                $oid = $board->orderId;
                $coin = $board->coinsReward;
                $v = $this->pushAction($oid);
                if($v){
                    $x++;
                    return $v;
                } else {
                    return false;
                }
            }
        } else {
            $this->log($r);
            return false;
        }
    }
    public function pushAction($oid){
        $base = $this->base . '/user/'.$this->id.'/trackAction/' . $this->session;
        $post = '{"actionToken":"2D2A839DB28BAA996AC2AE698921C355","action":0,"orderId":'.$oid.'}';
        $respon = $this->request($base, $post, true);
        $r = $respon;

        $respon = json_decode($respon);
        
        if(empty($respon->status)) return false;
        
        if($respon->status->status == 200){
            return $respon->data->coinsInAccount;
        } else {
            $this->log($r);
            return false;
        }
    }
    public function getFollowers($package = 'com.ty.vl.follower1'){
        $base = $this->base . '/user/'.$this->id.'/getFollowers/' . $this->session;
        /*
            com.ty.vl.follower1 : 10 : 80
            com.ty.vl.follower2 : 35 : 250
            com.ty.vl.follower3 : 75 : 500
            com.ty.vl.follower4 : 400 : 2500
            com.ty.vl.follower5 : 1000 : 6000
            com.ty.vl.follower6 : 4000 : 20000
        */
        $post = '{"avatarUrl":"http://scontent-iad3-1.cdninstagram.com/t51.2885-19/11906329_960233084022564_1448528159_a.jpg","goodsId":"'.$package.'","userName":"instagram","startAt":0}';
        $respon = $this->request($base, $post, true);
        $r = $respon;
        $respon = json_decode($respon);
        
        if(empty($respon->status)) return false;
        
        if($respon->status->status == 200){
            return $respon->data->coinsInAccount;
        } else {
            $this->log($r);
            return false;
        }
    }
    public function getLikes($id){
        $base = $this->base . '/user/'.$this->id.'/getLikes/' . $this->session;
        $package = 'com.ty.vl.like1';
        $post = '{"goodsId":"'.$package.'","videoUrl":"https://scontent-sit4-1.cdninstagram.com/t51.2885-15/e35/17267801_1773241469659754_3582840106047766528_n.jpg","mediaId":"'.$id.'","postCode":"BRgA17xBO0x","videoLowURL":"https://scontent-sit4-1.cdninstagram.com/t51.2885-15/s320x320/e35/17267801_1773241469659754_3582840106047766528_n.jpg","thumbnailUrl":"https://scontent-sit4-1.cdninstagram.com/t51.2885-15/e35/17267801_1773241469659754_3582840106047766528_n.jpg","userName":"instagram","startAt":0,"goodsType":2}';
        $respon = $this->request($base, $post, true);
        
        $respon = json_decode($respon);
        
        if(empty($respon->status)) return false;
        
        if($respon->status->status == 200){
            return $respon->data->coinsInAccount;
        } else {
            return false;
        }
    }
    public function login($id){
        $session = hash("sha256", time() . rand() . $id);
        $post = '{"deviceId":"","imei":"","parseKey":"'.hash("sha256", $id).'.bebaskaliha","platform":"0","sessionToken":"'.$session.'","viUserId":"'.$id.'","viUserName":"instagram"}';
        
        $respon = $this->request($this->base . "/user/login/", $post);
        $r = $respon;
        $respon = json_decode($respon);
        
        if(empty($respon->status)) return false;
        
        if($respon->status->status == 200){
            return $session;
        } else {
            $this->log($r);
            return false;
        }
    }
    private function log($respon){
        if($this->log){
            @file_put_contents("log_error.txt", $respon, FILE_APPEND);
        }
    }
    private function request($base, $fields = false, $push = false){       
		$ch = curl_init();
        
		curl_setopt($ch, CURLOPT_URL, $base);
		if($fields){
		  curl_setopt($ch, CURLOPT_POST, 1);
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_PROXY, @file_get_contents("proxy.txt"));
		$headers = $this->getHeaders($push);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec ($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		curl_close ($ch);
		
		return $body;
	}
    private function getHeaders($push){
        $header = 'User-Agent: royallikes 12
appVersion: 12
systemVersion: royallikesandroid/ ()
Content-Type: application/json; charset=utf-8
Host: instalike.socialmarkets.info
Accept-Encoding: gzip
Connection: close';
        if($push){
            $header .= "\n" . 'Signature: cd590c41b8f0076ca5631600b94392e47125c793c7c854c688db9eabe10fbfa8.{"actionToken":"2D2A839DB28BAA996AC2AE698921C355","action":0,"orderId":60582408}';
        } else {
            $header .= "\n" . 'Signature: 1fdb9a2e26c68d8da48bda6afb85ccf5142a47af175f4d9ea9bc1a83b3c7de3a.{"deviceId":"","imei":"","parseKey":"303973cba827bf713e681b34711182c83af6108c97b88526dc928abddf1f3463.Lordarts2013","platform":"0","sessionToken":"5e0966b4e341019a0aa1d1b45670fcf6e6747bf2bce861c21eff4e6ab768a5e9","viUserId":"4458966866","viUserName":"galnetworkss"}';
        }
        return explode("\n", $header);
    }
}

?>

