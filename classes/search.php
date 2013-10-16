<? namespace FIFA14;
 
use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
 
class Search {
  
  private $nuc;
	private $sess;
	private $phish;
	
	
		//initialise the class
		public function __construct($nuc, $sess, $phish) {
			$this->nuc 	= $nuc;
			$this->sess 	= $sess;
			$this->phish 	= $phish;
						
		}
		
		
		
		
		
		public function Consumables($macr,$micr,$num,$cat,$start,$lev,$minb,$maxb) {
		
		$client = new Client(null);
      
        $client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
				
			
			$url = "https://utas.s2.fut.ea.com/ut/game/fifa14/transfermarket?";
			$searchstring = "";
			
			if ($lev != "" && $lev != "any"){
				$searchstring .= "&lev=$lev";
			}
			
			if ($cat != "" && $cat != "any"){
				$searchstring .= "&cat=$cat";
			}
			
			if ($micr > 0){
				$searchstring .= "&micr=$micr";//min bid
			}
			
			if ($macr > 0){
				$searchstring .= "&macr=$macr";//max bid
			}
			
			if ($minb > 0){
				$searchstring .= "&minb=$minb";
			}
			
			if ($maxb > 0){
				$searchstring .= "&maxb=$maxb";
			}
		
			$search = $url . "type=development&blank=10&start=$start&num=$num" . $searchstring;
			
			//echo $search;
	
 
				$request = $client->post($search);
 
				$request->addHeader('Origin', 'http://www.easports.com');
				$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
				$request->addHeader('X-HTTP-Method-Override', 'GET');
				$request->addHeader('X-UT-Embed-Error', 'true');
				$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
				$request->addHeader('X-UT-SID', $this->sess);	
		
 
				$response = $request->send();
				
				$json = $response->json();
					
				//print_r($json);
				
				
				return $json;
 
			}
			
			
			public function Chemistry($macr,$micr,$num,$cat,$start,$lev,$minb,$maxb,$style) {
		
		$client = new Client(null);
      
        $client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
		
					
			$url = "https://utas.s2.fut.ea.com/ut/game/fifa14/transfermarket?";
			$searchstring = "";
			
			if ($lev != "" && $lev != "any"){
				$searchstring .= "&lev=$lev";
			}
			
			if ($cat != "" && $cat != "any"){
				$searchstring .= "&cat=$cat";
			}
			
			if ($micr > 0){
				$searchstring .= "&micr=$micr";//min bid
			}
			
			if ($macr > 0){
				$searchstring .= "&macr=$macr";//max bid
			}
			
			if ($minb > 0){
				$searchstring .= "&minb=$minb";
			}
			
			if ($maxb > 0){
				$searchstring .= "&maxb=$maxb";
			}
		
			$search = $url . "type=training&playStyle=$style&start=$start&num=$num" . $searchstring;
			
			//echo $search;
	
 
				$request = $client->post($search);
 
				$request->addHeader('Origin', 'http://www.easports.com');
				$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
				$request->addHeader('X-HTTP-Method-Override', 'GET');
				$request->addHeader('X-UT-Embed-Error', 'true');
				$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
				$request->addHeader('X-UT-SID', $this->sess);	
		
 
				$response = $request->send();
				
				$json = $response->json();
					
				//print_r($json);
				
				
				return $json;
 
			}
			
			public function Bid($tradeid,$bid) {
		
		    $client = new Client(null);
		      
        $client->setUserAgent("Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36");
			
			  $url = "https://utas.s2.fut.ea.com/ut/game/fifa14/trade/".$tradeid."/bid";			
				
				$data_array = array('bid' => $bid);
        $data_string = json_encode($data_array);
		
		
        $request = $client->post($url, array(), $data_string);
 
			
 
				$request->addHeader('Origin', 'http://www.easports.com');
				$request->setHeader('Referer', 'http://www.easports.com/iframe/fut/bundles/futweb/web/flash/FifaUltimateTeam.swf');
				$request->addHeader('X-HTTP-Method-Override', 'PUT');
				$request->addHeader('X-UT-Embed-Error', 'true');
				$request->addHeader('X-UT-PHISHING-TOKEN', $this->phish);				
				$request->addHeader('X-UT-SID', $this->sess);
				$request->setHeader('Content-Type', 'application/json');
				$request->addHeader('Content-Length', strlen($data_string));				
				//$request->addHeader('bid', $bid);	//Not necessary				
		 
 
				$response = $request->send();
				
				$json = $response->json();
					
				//print_r($json);
								
				return $json;
 
			}
			
			
			public function baseID($resourceid){
				$rid = $resourceid;
				$version = 0;
				
				WHILE ($rid > 16777216){
					$version++;
					if ($version == 1){
						//the constant applied to all items
						$rid -= 1342177280;
					}elseif ($version == 2){
						//the value added to the first updated version
						$rid -= 50331648;
					}else{
						//the value added on all subsequent versions
						$rid -= 16777216;
					}
				}
								
				return $rid;
			}
							
	}
	
?>
