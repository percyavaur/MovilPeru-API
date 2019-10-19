<?php
require_once '../vendor/autoload.php';
  
$interestDetails = ['unique identifier', 'ExponentPushToken[FOVOctBiG91SGgcUv4bQVT]'];

// You can quickly bootup an expo instance
$expo = \ExponentPhpSDK\Expo::normalSetup();

// Subscribe the recipient to the server
$expo->subscribe($interestDetails[0], $interestDetails[1]);

// Build the notification data
$notification = [
    'sound' => 'default',
    'body' => 'Hello World!'
];

// Notify an interest with a notification
$expo->notify($interestDetails[0], $notification);

/* $key = "ExponentPushToken[0GAEokJazChx21MOxeC1l2]";
$userId = 'userId from your database';
$notification = ['title' => $title,'body' => $msg];
  try{

      $expo = \ExponentPhpSDK\Expo::normalSetup();
      $expo->notify($userId,$notification);//$userId from database
      $status = 'success';
}catch(Exception $e){
        $expo->subscribe($userId, $key); //$userId from database
        $expo->notify($userId,$notification);
        $status = 'new subscribtion';
}

  echo $status;
  ?> */