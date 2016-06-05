<?php

namespace OSC\News;

use
	Aedea\Core\Database\StdObject as DbObj
	, OSC\NewsDescription\Collection as NewDescriptionCol
;

class Object extends DbObj {

	protected
		$detail,
		$customerId,
		$image,
		$imageThumbnail
	;
	public function __construct( $params = array() ){
		parent::__construct($params);

		$this->detail = new NewDescriptionCol();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'customer_id',
				'image',
				'image_thumbnail',
				'status',
				'create_by',
				'create_date',
				'detail'
			)
		);
		return parent::toArray($args);
	}

	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				customer_id,
				image_thumbnail,
				image,
				status,
				create_by,
				create_date
			FROM
				news
			WHERE
				id = '" . (int)$this->getId() . "'
		");

		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: News not found",
				404
			);
		}
		
		$this->setProperties($this->dbFetchArray($q));

		$this->detail->setFilter('news_id', $this->getId());
		$this->detail->populate();
	}

	public function updateStatus() {
		if( !$this->getId() ) {
			throw new Exception("save method requires id");
		}
		$this->dbQuery("
			UPDATE
				news
			SET
				status = '" .  $this->getStatus() . "',
				update_by = '" .  $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$this->getId() . "'
		");
	}

	public function update() {
		if( !$this->getId() ) {
			throw new Exception("save method requires id");
		}
		$this->dbQuery("
			UPDATE
				news
			SET 
				image = '" .  $this->getImage() . "',
				image_thumbnail = '" .  $this->getImageThumbnail() . "',
				update_by = '" .  $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$this->getId() . "'
		");
	}

	public function delete(){
		if( !$this->getId() ) {
			throw new Exception("delete method requires id to be set");
		}
		$this->dbQuery("
			DELETE FROM
				news
			WHERE
				id = '" . (int)$this->getId() . "'
		");
		// remove from description
		$this->dbQuery("
			DELETE FROM
				news_description
			WHERE
				news_id = '" . (int)$this->getId() . "'
		");
	}
	
	public function insert(){	
		$this->dbQuery("
			INSERT INTO
				news
			(
				customer_id,
				image,
				image_thumbnail,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getCustomerId() . "',
				'" . $this->getImage() . "',
				'" . $this->getImageThumbnail() . "',
				'" . $this->getCreateBy() . "',
				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setDetail( $array ){
		$this->detail = $array;
	}

	public function getDetail(){
		return $this->detail;
	}

	public function setImageThumbnail( $string ){
		$this->imageThumbnail = $string;
	}

	public function getImageThumbnail(){
		return $this->imageThumbnail;
	}

	public function setImage( $string ){
		$this->image = $string;
	}

	public function getImage(){
		return $this->image;
	}

	public function setCustomerId( $int ){
		$this->customerId = (int)$int;
	}

	public function getCustomerId(){
		return $this->customerId;
	}

}
