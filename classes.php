<?php

class Bank {
	
	private $connection = null;
	private $items = array();
	private $donators = array();
	
	public function __construct($host, $user, $pass){
		$this->connection = mysqli_connect($host, $user, $pass) or die(mysqli_error($this->connection));
		mysqli_select_db($this->connection, 'clanbank') or die(mysqli_error($this->connection));
		
		$this->loadBank();
		$this->loadDonator();
	}
	
	public function close() {
		mysqli_close($this->connection);
	}
	
	private function loadBank(){
		$data = mysqli_query($this->connection, "SELECT * FROM `items`");
		if(mysqli_num_rows($data) < 1){
			$this->items = false;
		}else {
			while($item = mysqli_fetch_assoc($data)){
				$this->items[] = new Item($item);
			}
		}
	}
	
	private function loadDonator(){
		$data = mysqli_query($this->connection, "SELECT * FROM `donator` ORDER BY `id` DESC");
		if(mysqli_num_rows($data) < 1){
			$this->donators = false;
		}else {
			while($donator = mysqli_fetch_assoc($data)){
				$this->donators[] = new Donator($donator);
			}
		}
	}
	
	public function displayAll(){
		if(!is_array($this->items)){
			echo "There are currently no items in the clan bank!";
			return;
		}
		$i = 0;
		echo "<table cellpadding='0' cellspacing='0' border='0' width='400px'>";
		echo "<tr><th>zBank Items In Stock</th></tr>";
		echo "<tr><td>";
		foreach($this->items as $item){
			if($item->quantity >0) {
				if($i > 9) {
					echo "</td></tr><tr><td><img src=\"bankImage.php?imageURL=".$item->image."&quantity=".$item->quantity."\" title=\"".$item->name."\" alt=\"".$item->name."\" />";
					$i = 0;
				} else {
					echo "<img src=\"bankImage.php?imageURL=".$item->image."&quantity=".$item->quantity."\" title=\"".$item->name."\" alt=\"".$item->name."\" />";
				}
				$i++;
			}
		}
		echo "</td></tr>";
		echo "</table>";
	}
	
	public function addItem($itemName, $quantity, $donator) {
		$quantity = mysqli_real_escape_string($this->connection, $quantity);
		$donator = mysqli_real_escape_string($this->connection, $donator);
	
			//Prepare item name for addition to url
		$item = strtolower($itemName);
		$item = str_replace(" ", "%20", $item);

		//Retrieve and decode data from zyzbez
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, "http://forums.zybez.net/runescape-2007-prices/api/".$item);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		$phpJSON = json_decode($data, true);

		if(isset($phpJSON['error']) AND $phpJSON['error'] == "No results found." AND $item != "money") {
		
			return false;
		
		}
		
		else {
			
			if($item == "money") {
			$itemData['id'] = '0';
			$itemData['name'] = 'money';
			$itemData['image'] = "http://zskillers.com/bank/gp.png";
			}
			else {
			$itemData = $phpJSON[0];
			}
			
			if($result = mysqli_query($this->connection, "SELECT * FROM items WHERE itemID = ". $itemData['id'])) {

				/* determine number of rows result set */
				$row_cnt = mysqli_num_rows($result);

				if($row_cnt == 1) $itemExists = true;

				/* close result set */
				mysqli_free_result($result);
			}
			
			//update Item if exists
			if(isset($itemExists)) {
				
				if(!mysqli_query($this->connection, "UPDATE items SET quantity = quantity + $quantity WHERE itemID = ". $itemData['id'])) {
					die('Error: Couldnt Update item.');
				}
				
			}
			//Add item if doesnt exists
			else {
			
				if(!mysqli_query($this->connection, "INSERT INTO items (itemID, name, imageURL, quantity) 
												VALUES ('$itemData[id]', '$itemData[name]', '$itemData[image]', '$quantity')")) {
					
					die('Error: Couldnt Insert Item');
					
				}
			
			}
			
			//Add To donator
			if(!mysqli_query($this->connection, "INSERT INTO donator (name, itemID, quantity) 
			VALUES ('$donator', '$itemData[id]', '$quantity')")) {
			
				die('Error: Couldnt Add donator');
			}
			
			return true;
		}
	}
	
	public function displayDonator() {
		if(!is_array($this->donators)){
			echo "There are currently no donations!";
			return;
		}

		echo "<table cellpadding='0' cellspacing='0' border='0' width='400px'>";
		echo "<tr><th colspan='1'>zBank - Transactions</th></tr>";
		echo "<tr><th>Item</th><th>User</th></tr>";
		foreach($this->donators as $donator){
			if($donator->itemID == 0) {
			$itemData['id'] = '0';
			$itemData['name'] = 'money';
			$itemData['image'] = "http://zskillers.com/bank/gp.png";
			}
			else {
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, "http://forums.zybez.net/runescape-2007-prices/api/item/".$donator->itemID);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$data = curl_exec($ch);
			curl_close($ch);
			$phpJSON = json_decode($data, true);
			$itemData = $phpJSON;
			}
			echo "<tr><td><img src=\"bankImage.php?displayNum=yes&imageURL=".$itemData['image']."&quantity=".$donator->quantity."\" title=\"".$itemData['name']."\" alt=\"".$itemData['name']."\" /></td>
					<td>".$donator->name."</td></tr>";
		}
		echo "</table>";
	
	}
}

class Item {
	
	public $itemID, $name, $image, $quantity;
	
	public function __construct($item){
		$this->itemID = $item['itemID'];
		$this->name = $item['name'];
		$this->image = $item['imageURL'];
		$this->quantity = $item['quantity'];
	}
}

class Donator {
	
	public $id, $itemID, $name, $quantity;
	
	public function __construct($item){
		$this->id = $item['id'];
		$this->name = $item['name'];
		$this->itemID = $item['itemID'];
		$this->quantity = $item['quantity'];
	}
}

?>