<?php
namespace App\Models;

class Masters extends Home
{
	public function add($path)
	{			
		return $this->data;
	}

	public function delete($path)
	{			
		return $this->data;
	}

	public function change($path)
	{			
		$this->data['masters'] = $this->db->db->select("masters", "*");
		return $this->data;
	}
}