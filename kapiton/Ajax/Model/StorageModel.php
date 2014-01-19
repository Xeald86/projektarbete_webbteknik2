<?php

namespace Ajax\Model;

use PDO;
use Exception;

class StorageModel {

    private $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
	
	public function insertOrder($type, $api, $sek, $bc, $cell) {
		$con = $this->pdo->prepare('INSERT INTO kapiton_order (type, api_key, cell, sek, bc) VALUES (?, ?, ?, ?, ?)');
		$con->execute(array($type, $api, $cell, $sek, $bc));
	}
}