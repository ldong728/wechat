<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>兄弟数码新城旗舰店</title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        wx.config({
            debug: false;,
            appId: '<?php echo $signPackage["appId"];?>',
            timestamp: <?php echo $signPackage["timestamp"];?>,
            nonceStr: '<?php echo $signPackage["nonceStr"];?>',
            signature: '<?php echo $signPackage["signature"];?>',
            jsApiList: [
                'getLocation',
                'openLocation'
            ]
        });
        wx.ready(function () {
            wx.openLocation({
                latitude: 30.1723043204, // 纬度，浮点数，范围为90 ~ -90
                longitude: 121.2621096238, // 经度，浮点数，范围为180 ~ -180。
                name: '慈溪兄弟数码旗舰店', // 位置名
                address: '新城中心9-12', // 地址详情说明
                scale: 15, // 地图缩放级别,整形值,范围从1~28。默认为最大
                infoUrl: 'http://www.xdsm.net' // 在查看位置界面底部显示的超链接,可点击跳转
            });
        });
    </script>
</head>

<body>

</body>

</html>