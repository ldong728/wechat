<?php
include_once $mypath.'/includes/helpers.inc.php';
try
{
  $pdo = new PDO('mysql:host=localhost;dbname=test_db', 'godlee', 'godlee1394');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec('SET NAMES "utf8"');
}
catch (PDOException $e)
{
  $error = 'Unable to init the database server.'.$e->getMessage();
  include 'error.html.php';
  exit();
}



function exeNew($s){
    try{
        $GLOBALS['pdo']->exec($s);
        return $GLOBALS['pdo']->lastInsertId();
    }catch(PDOException $e){
        $error = 'exeError' . $e->getMessage();
        include 'error.html.php';
        exit();
    }
}
function pdoQuery($tableName, $fields, $where, $append)
{
    $sql = 'SELECT ';
    $fieldsCount = count($fields);
    if ($fieldsCount > 0) {
        for ($i = 0; $i < $fieldsCount; $i++) {
            $sql = $sql . $fields[$i];
            if ($i < $fieldsCount - 1) $sql = $sql . ',';
        }
    }else{
        $sql=$sql.'* ';
    }
    $sql = $sql . ' FROM ' . $tableName;
    $whereCount = count($where);
    if ($whereCount > 0) {
        $sql = $sql . ' WHERE ';
        $j = 0;
        foreach ($where as $k => $v) {
            if($v==null){
                $j++;
                continue;
            }
            $sql = $sql . $k . '=' . '"' . $v . '"';
            if ($j < $whereCount - 1) $sql = $sql . ' AND ';
            $j++;
        }
    }
    if($append!=null){
        $sql=$sql.' '.$append;
    }
//    wxlog('sql:'.$sql);
//    echo $sql;
//    exit;
    try {
        $query = $GLOBALS['pdo']->query($sql);

        return $query;
    }catch (PDOException $e) {
        $error = 'Unable to PDOquery to the database server.' . $e->getMessage();
        include 'error.html.php';
        exit();
    }
}
function joinQuery($joinType,$fields,$tables,$joinField,$where,$group){
    $sql=outerJoinStr($joinType,$fields,$tables,$joinField,$where,$group);
//    echo $sql;
    try {
        $query = $GLOBALS['pdo']->query($sql);

        return $query;
    }catch (PDOException $e) {
        $error = 'Unable to joinquery to the database server.' . $e->getMessage();
        include 'error.html.php';
        exit();
    }

}
function pdoInsert($tableName,$value,$str=''){
//    echo 'data';
//    exit;
    $sql='INSERT INTO '.$tableName.' SET ';
    $j = 0;
    $valueCount=count($value);
    foreach ($value as $k => $v) {
        $sql = $sql . $k . '=' . '"' . $v . '"';
        if ($j < $valueCount - 1) $sql = $sql . ',';
        $j++;
    }
    if($str=='ignore'){
        $sql=preg_replace('/INTO/',$str,$sql);
    }else{
        $sql=$sql.$str;
    }

//    echo $sql;
//    exit;
    try {
        $GLOBALS['pdo']->exec($sql);
        return $GLOBALS['pdo']->lastInsertId();

    }catch (PDOException $e) {
        $error = 'Unable to insert to the database server.' . $e->getMessage();
        include 'error.html.php';
        exit();
    }

}
function pdoUpdate($tableName,array $value,array $where,$str=''){
//    echo 'data';
//    exit;
    $sql='UPDATE '.$tableName.' SET ';
    $j = 0;
    $valueCount=count($value);
    foreach ($value as $k => $v) {
        $sql = $sql . $k . '=' . '"' . $v . '"';
        if ($j < $valueCount - 1) $sql = $sql . ',';
        $j++;
    }
    $whereCount = count($where);
    if ($whereCount > 0) {
        $sql = $sql . ' WHERE ';
        $j = 0;
        foreach ($where as $k => $v) {
            if($v==null){
                $j++;
                continue;
            }
            $sql = $sql . $k . '=' . '"' . $v . '"';
            if ($j < $whereCount - 1) $sql = $sql . ' AND ';
            $j++;
        }
    }
    $sql=$sql.$str;
//    wxlog('sql:'.$sql);
//    echo $sql;
//    exit;
    try {
        $GLOBALS['pdo']->exec($sql);
        return $GLOBALS['pdo']->lastInsertId();

    }catch (PDOException $e) {
        $error = 'Unable to insert to the database server.' . $e->getMessage();
        include 'error.html.php';
        exit();
    }

}
function outerJoinStr($joinType,$fields,$tables,$joinField,$where,$group)
{
    $sql = 'SELECT ';
    $fieldsCount = count($fields);
    if ($fieldsCount > 0) {
        for ($i = 0; $i < $fieldsCount; $i++) {
            $sql = $sql . $fields[$i];
            if ($i < $fieldsCount - 1) $sql = $sql . ',';
        }
    } else {
        $sql = $sql . '* ';
    }
    $sql = $sql . ' FROM ' . $tables[0];
    for ($i = 1; $i < count($tables); $i++) {
        $sql = $sql . ' ' . $joinType[$i - 1] . ' ' . $tables[$i] . ' ON ' . $joinField[$i - 1];
    }
    $whereCount = count($where);
    if ($whereCount > 0) {
        $sql = $sql . ' WHERE ';
        $j = 0;
        foreach ($where as $k => $v) {
            if($v==null){
                $j++;
                continue;
            }
            $sql = $sql . $k . '=' . '"' . $v . '"';
            if ($j < $whereCount - 1) $sql = $sql . ' AND ';
            $j++;
        }
    }
    if ($group != null) {
        $sql = $sql . ' ' . $group;
    }

    return $sql;

}
function outerJoinQuery($joinType,$fields,$tables,$joinField,$where,$group){
    $str=outerJoinStr($joinType,$fields,$tables,$joinField,$where,null);
    for($i=0; $i<count($joinType);$i++){
        $joinType[$i]=preg_replace('/left/','right',$joinType[$i]);
    }
    $str=$str.' union all '.outerJoinStr($joinType,$fields,$tables,$joinField,$where,$group);
    echo $str;
    exit;

    try {
        $query = $GLOBALS['pdo']->query($str);

        return $query;
    }catch (PDOException $e) {
        $error = 'Unable to outerJoinQuery.' . $e->getMessage();
        include 'error.html.php';
        exit();
    }


}

?>