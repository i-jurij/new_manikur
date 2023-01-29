<?php
namespace App\Lib;

class Sql_col_names
{
    protected $pdo;
    protected string $table;
    protected string $driver;
    public array $res;

    public function __construct($pdo, $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $driver = $pdo->db->info()['driver'];
        
        //if (strpos(DBINITNAME, 'sqlite')) {
        if ($driver == 'sqlite') {
            $this->res = $this->sqlite();
        } 
        elseif ($driver == 'mysql') {
            $this->res = $this->mysql();
        }
        else {
            $this->res = $this->other();
        }
        
    }

    protected function sqlite() {
        $q = $this->pdo->db->query("PRAGMA table_info($this->table);");
        while ($tab = $q->fetch(\PDO::FETCH_ASSOC)) {
            //print $tab['name'] . ' - ' . $tab['type'] . '<br />';
            $res[$tab['name']] = $tab['type'];
        }
        return $res;
    }

    protected function mysql() {
        $q = $this->pdo->db->pdo->query('DESCRIBE ' . $this->table);
        $result = $q->fetchAll(\PDO::FETCH_ASSOC);
        foreach($result as $column){
            $res[$column['Field']] = $column['Type'];
        }
        /*
        $q = $this->pdo->db->pdo->prepare($this->table);
        $q->execute();
        $res = $q->fetchAll(\PDO::FETCH_COLUMN);
        */ 
        return $res;       
    }

    protected function other() {
        $q = $this->pdo->db->pdo->query('SELECT * FROM `'.$this->table.'` LIMIT 0');
        for ($i = 0; $i < $q->columnCount(); $i++) {
            $col = $q->getColumnMeta($i);
            $res[$col['name']] = $col['native_type'];
        }
        return $res;
    }
}
?>