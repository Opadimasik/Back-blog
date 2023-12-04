<?php
include_once("helpers/headers.php");
include_once("helpers/validation.php");
include_once("helpers/bearer.php");
global $Link;
header("Content-type: application/json");
function getData($method)
{
    // GET или POST: данные возвращаем как есть
    if ($method === 'GET') return $_GET;
    if($method === 'POST' && !empty($_POST)) return $_POST;
    // PUT, PATCH или DELETE
    $data = array(); //создание нового массива
    $exploded = explode('&', file_get_contents('php://input'));
    //Здесь мы разбили массив, полученный из потока ввода php://input no разделителю &, тем самым получив список ключ - значение
    foreach($exploded as $pair) 
    {
        $item = explode('=', $pair);
        if (count($item) == 2) 
        {
        $data[urldecode($item[0])] = urldecode($item[1]);
        }
    }
    return json_decode(file_get_contents('php://input'));
}

// получаем метод запросы POST, GET, PATCH и тд.
function getMethod() {
    return $_SERVER["REQUEST_METHOD"];
}
//connet to database, in my case connect to PHPmyAdmin
$Link = mysqli_connect("localhostt","dbLab2","0000","dbLab2");
if (!$Link) 
{
    setHTTPStatus('500','DB connetion error'.mysqli_connect_errno());
    exit;
}

//url for url
// $urlAll = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
// $urlAll = rtrim($urlAll, '/');
// $urlListAll = explode('/', $urlAll);
// $routersAll = $urlListAll[1]; // Изменил с 0 на 1, так как 0-й элемент - пустая строка (начальный слеш)
// $urlListAll = array_slice($urlListAll, 2); // Пропускаю первые два элемента (пустая строка и имя маршрута)
//url for routers
$formData = getData(getMethod());
$method = getMethod();
$url = isset($_GET['q']) ? $_GET['q'] :'';
$url = rtrim($url,'/');
$urlList = explode('/', $url);
$routers = $urlList[0];

if (file_exists(realpath(dirname(__FILE__)) . '/routers/' . $routers . '.php')) 
{
    include_once 'routers/' . $routers . '.php';
    route($method, $urlList, $formData);
} 
else 
{
    setHTTPStatus("404");
}
