<?PHP

/**
 * sendMessage()
 * 
 * @param mixed $app_id
 * @param mixed $api_key
 * @param mixed $message
 * @return
 */
function sendMessage($app_id, $api_key, $message)
{
    $content = array("en" => $message);
    $fields = array(
        'app_id' => $app_id,
        'included_segments' => array('All'),
        'data' => array("foo" => "bar"),
        'contents' => $content);

    $fields = json_encode($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic ' . $api_key));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}


$app_id = 'cdc85c44-75d3-4c0b-8a4b-d3a2e0432ece';
$api_key = 'ZThlNjNvOTctY2RjYi00ZjUxLTgxMTItNDg2NTRkNmY3MGVk';
$message = 'Happy ramadhan';

$response = sendMessage($app_id,$api_key,$message);
echo "<pre>";
print_r($response);
echo "</pre>";

?>
