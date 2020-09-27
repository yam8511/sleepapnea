<?php
require('./check_login.php');
require('./auth_user.php');
require('show_auth.php');

$patientID = $_GET['id'] ?? '';

if ($patientID == '') {
    die('請提供 patient ID');
}

require('db.php');

$sql = "SELECT * FROM `patient` WHERE `patient_ID` = '$patientID'";
$result = $db->query($sql);
if ($result) {
    $patientInfo = $result->fetch();
} else {
    die('無病人資料');
}

if (isset($_GET['start_time']) && isset($_GET['end_time'])) {
    $startTime = date('Y-m-d H:i:s', strtotime($_GET['start_time']));
    $endTime = date('Y-m-d H:i:s', strtotime($_GET['end_time']));

    $data_sql = "SELECT dc.*,name 
    FROM `data_collection` as dc, `patient` as pt, `test_time` as tt 
    WHERE tt.patient_ID = pt.patient_ID AND tt.machine_ID = dc.machine_ID
    AND pt.patient_ID = {$patientID}
    AND dc.time BETWEEN '{$startTime}' AND '{$endTime}'
    ORDER BY dc.time;
    ";

    $patientData = [];
    $result = $db->query($data_sql);
    if ($result) {
        $patientData = $result->fetchAll();
    }

    if (count($patientData) > 0) {
        $jsData = [];
        foreach ($patientData as $data) {
            $jsData[] = [
                'time' =>  intval(strtotime($data['time'].'+0800')."000"), // 為了給JS使用，要多「000」代表毫秒
                'heartrate' =>  floatval($data['heartrate']),
                'breathe' =>  floatval($data['breathe']),
                'bloodoxygen' =>  floatval($data['bloodoxygen']),
                // 'temperature' =>  floatval($data['temperature']),
                // 'humidity' =>  floatval($data['humidity']),
            ];
        }

        $jsData = json_encode($jsData, JSON_UNESCAPED_UNICODE);
?>


<!-- 顯示病人資料 -->
<h1 style=" color: red;">生理資料:</h1>
<h2 align="center">病人名稱 [<?= $patientInfo['name'] ?>]</h2>
<p align="center">ID: <?= $patientID ?></p>
<p align="center">測試時間: [<?= $startTime ?>] - [<?= $endTime ?>]</p>

<div id="heartrate_chart" style="height: 360px; width: 100%;"></div>
<hr>
<div id="breathe_chart" style="height: 360px; width: 100%;"></div>
<hr>
<div id="bloodoxygen_chart" style="height: 360px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script>
window.onload = function() {
    let rawData = <?= $jsData ?>; // 直接用PHP將資料印到JS裡
    function get_setting(text, field) {
        return {
            animationEnabled: true,
            theme: "light2",
            zoomEnabled: true,
            axisY: {
                titleFontSize: 24
            },
            title: {text},
            data: [{
                type: "spline",
                xValueFormatString: "YYYY-MM-DD HH:mm:ss",
                xValueType: "dateTime",
                dataPoints: rawData.map((item) => ({
                    x: new Date(item['time']),
                    y: item[field],
                }))
            }]
        }
    }

    (new CanvasJS.Chart("heartrate_chart", get_setting('心跳', 'heartrate'))).render();
    (new CanvasJS.Chart("breathe_chart", get_setting('呼吸', 'breathe'))).render();
    (new CanvasJS.Chart("bloodoxygen_chart", get_setting('血氧', 'bloodoxygen'))).render();
}
</script>

<?php
    } else {
?>

<!-- 顯示病人目前無監控資料 -->
<h1 style=" color: red;">生理資料:</h1>
<h2 align="center">病人名稱 [<?= $patientInfo['name'] ?>]</h2>
<p align="center">ID: <?= $patientID ?></p>
<p align="center">測試時間: [<?= $startTime ?>] - [<?= $endTime ?>]</p>
<h3 align="center" style="color: teal">目前無資料</h3>

<?php
    }
} else {
?>

<!-- 顯示時間選擇 -->
<h1 style=" color: red;">選擇查看病人生理狀態(時間) </h1>
<h2 align="center">病人名稱 [<?= $patientInfo['name'] ?>]</h2>
<h3 align="center">選擇時間區間</h2>
<form align="center" action="<?= $_SERVER['PHP_SELF'] ?>">
    <p>起始時間</p>
    <input type="datetime-local" name="start_time" value="<?= date('Y-m-d\T00:00:00', time() - 86400) ?>">
    <p>結束時間</p>
    <input type="datetime-local" name="end_time" value="<?= date('Y-m-d\T23:59:59') ?>">
    <input type="hidden" name="id" value="<?= $patientID ?>">

    <p>
        <a href="/">上一頁</a>
        <input type="submit" value="查詢">
    </p>
</form>

<?php
}
?>
