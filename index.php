<?php
Namespace FIFA14;
require_once 'vendor/autoload.php';
//require 'search.php';
require 'core.php';
  
  echo "Credits: ";
  
  print_r($con["getCredits"]);
  
  echo "<br><br>";
 
// Consumables($macr,$micr,$num,$cat,$start,$lev,$minb,$maxb)
$fitness = $s->Consumables(0,0,12,"fitness",0,"gold",0,900);  	 
//print_r($fitness);
 
//You can get the item data's like this 
 
FOREACH ($fitness['auctionInfo'] as $auction){
  
  $resid = $auction['itemData']['resourceId'];
  $b = $s->baseID($resid);
	print_r($b); // This is the base ID calculated from resource ID
  
  
  // An example 
  
  if($b == 5002006){ // Checks if the type of card is Team Fitness - Gold
  
    $tradeid = $auction['tradeId']; // Trade ID
    $price = $auction['buyNowPrice']; // BuyNowPrice
    $bid = $s->Bid($tradeid,$bid); // Sends TradeID and Amount of coins to Bid
    print_r($bid); 
  
  }
  
  // You can make a for loop to seach through pages and buy the cards at 59th min
  
}
