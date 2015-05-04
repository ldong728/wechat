<?php
/**
 * Created by PhpStorm.
 * User: godlee
 * Date: 2015/4/29
 * Time: 17:56
 */
$mypath = $_SERVER['DOCUMENT_ROOT'] . '/xdsm';
include_once $mypath . '/includes/xdsmdb.php';

class mobilePhoneQuery
{


    public function getPrice($key)
    {
        $backData='';
        $sql = 'SELECT * FROM (SELECT * FROM price_view ORDER BY last_modify DESC ) a
                WHERE name LIKE  \'%'.$key.'%\' GROUP BY product_id ORDER BY last_modify DESC limit 25';
        $query = $GLOBALS['xdsmdb']->query($sql);
        foreach ($query as $row) {
            $str = stristr($row['inf'],$row['product_id']);
            $str = stristr($str,'"');
            $str = substr($str,1,(strpos($str,';')-2));
            $backData = $backData.$row['name']."\n".$str.'： ￥'.intval($row['price'])."\n\n";
        }
        $backData=$backData.'以上价格仅供参考，以店面实际报价为准。';
        return $backData;

    }
    public function temp(){
        $sql = 'SELECT * FROM (SELECT * FROM price_view ORDER BY last_modify DESC ) a
                WHERE name LIKE  \'%plus%\' GROUP BY product_id ORDER BY last_modify DESC limit 1';
        $query = $GLOBALS['xdsmdb']->query($sql);
        $row = $query->fetch();
        $str = stristr($row['inf'],$row['product_id']);
        $str = stristr($str,'"');
        $str = substr($str,1,(strpos($str,';')-2));
        return $str;
    }

} 