<?php 

/**
 * 十进制数转换成62进制
 *
 * @param integer $num
 * @return string
 */
function from10_to62($num) {
    $to = 62;
    $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $ret = '';
    do {
        $ret = $dict[bcmod($num, $to)] . $ret;
        $num = bcdiv($num, $to);
    } while ($num > 0);
    return $ret;
}


/**
 * 62进制数转换成十进制数
 *
 * @param string $num
 * @return string
 */
function from62_to10($num) {
    $from = 62;
    $num = strval($num);
    $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = strlen($num);
    $dec = 0;
    for($i = 0; $i < $len; $i++) {
        $pos = strpos($dict, $num[$i]);
        $dec = bcadd(bcmul(bcpow($from, $len - $i - 1), $pos), $dec);
    }
    return $dec;
}

////////////////////////////////////////////////////example  @@################
if(isset($_GET['a']) && $_GET['a']>=0 ){
	echo "10进制到62进制:".from10_to62($_GET['a'])."<br>";
}
if(isset($_GET['b']) && $_GET['b']!=""){
	echo "62进制到10进制:".from62_to10($_GET['b']);
}
