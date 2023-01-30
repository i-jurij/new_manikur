<?php
namespace App\Models;
class Master_app extends Home
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
