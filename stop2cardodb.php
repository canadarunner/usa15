<?php
require_once 'config.php';

date_default_timezone_set($timezone);

// $feedUrl = "https://api.findmespot.com/spot-main-web/consumer/rest-api/2.0/public/feed/$feedId/message.json";
$feedUrl = "https://api.findmespot.com/spot-main-web/consumer/rest-api/2.0/public/feed/$feedId/message.json";


$jsonString = file_get_contents($feedUrl);
$jsonArr    = json_decode($jsonString, true);
$response   = $jsonArr['response'];

if (isset($response['errors'])) {
  $error = $response['errors']['error'];
  error_log($error['code'] . $error['text']);
  print $error['description'];
  exit();
}

$response = $jsonArr['response']['feedMessageResponse'];

if ($response['count'] == 1) {
  $messages = array($response['messages']['message']);
} else {
  $messages = $response['messages']['message'];
}

// Find max timestamp
// $response = $cartodb->runSql("SELECT MAX(timestamp) FROM spot");
// https://{account}.cartodb.com/api/v2/sql?api_key={Your API key}&q=INSERT INTO test_table (column_name, column_name_2, the_geom) VALUES ('this is a string', 11, ST_SetSRID(ST_Point(-110, 43),4326))
$key = "api_key".$api_key
$url = "https://".$cartodb_key.".cartodb.com/api/v2/sql?"
$insert = "q=INSERT INTO test_table (".implode (", ", array_keys($data)).")";
$values = " VALUES (".implode (", ", $data).")";


$response = http_get ( string $url [, array $options [, array &$info ]] )
$max = array_pop($response['return']['rows'])->max;

foreach ($messages as $message) {

  if ($message['unixTime'] > $max) { // Add if newer
    $data = array(
      'feed_id'         => "'" . $feedId . "'",
      'spot_id'         => $message['id'],
      'the_geom'        => "'SRID=4326;POINT(" . $message['longitude'] . " " . $message['latitude'] . ")'",
      'messenger_id'    => "'" . $message['messengerId'] . "'",
      'messenger_name'  => "'" . $message['messengerName'] . "'",
      'timestamp'       => $message['unixTime'],    
      'message_type'    => "'" . $message['messageType'] . "'",  
      'latitude'        => $message['latitude'],  
      'longitude'       => $message['longitude'],
      'model_id'        => "'" . $message['modelId'] . "'",  
      'show_custom_msg' => "'" . $message['showCustomMsg'] . "'",
      'datetime'        => "'" . date("Y-m-d H:i:s", $message['unixTime']) . "'",
      'battery_state'   => "'" . $message['batteryState'] . "'",
      'hidden'          => "'" . $message['hidden'] . "'",
      'message_content' => "'" . (isset($message['messageContent']) ? $message['messageContent'] : '') . "'"      
    );
    $key = "api_key".$api_key
    $url = "https://".$cartodb_key.".cartodb.com/api/v2/sql?"
    $insert = "q=INSERT INTO test_table (".implode (", ", array_keys($data)).")";
    $values = " VALUES (".implode (", ", $data).")";
    // $response = $cartodb->insertRow('spot', $data);
  }

}
?>