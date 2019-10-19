<?php
require_once __DIR__.'/vendor/autoload.php';
  
$interestDetails = ['unique identifier', 'ExponentPushToken[FOVOctBiG91SGgcUv4bQVT]'];

// You can quickly bootup an expo instance
$expo = \ExponentPhpSDK\Expo::normalSetup();

// Subscribe the recipient to the server
$expo->subscribe($interestDetails[0], $interestDetails[1]);

// Build the notification data
$notification = ['body' => 'Hello World!'];

// Notify an interest with a notification
$expo->notify($interestDetails[0], $notification);