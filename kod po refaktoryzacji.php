<?php

class ContractManager{

    private $action;
    private $sort;
    private $dg_bgcolor;
    private $id;

    public function __construct($action, $sort, $dg_bgcolor, $id){
        $this->action = $action;
        $this->sort = $sort;
        $this->dg_bgcolor = $dg_bgcolor;
        $this->id = $id;
    }

    public function getOrderBy(){
        switch ($this->sort){
            case 1:
                $orderBy = " ORDER BY 2, 4 DESC";
                break;
            case 2:
                $orderBy = " ORDER BY 10";
                break;
            default:
                $orderBy = " ORDER BY id";
        }

        return $orderBy;
    }

    public function createSqlQuery($isAmountFilter){

        $where = "id = '" . mysql_real_escape_string($this->id) . "'";

        if($isAmountFilter){
            $where .= " AND kwota > 10 ";
        }

        $orderBy = $this->getOrderBy();

        return "SELECT * FROM contracts WHERE $where $orderBy";
    }

    public function generateTable($results){
        echo '<html><body bgcolor="' . $this->dg_bgcolor . '">';
        echo '<br>';
        echo '<table width="95%">';

        while($row = mysql_fetch_array($results)){
            echo '<tr>';
            echo '<td>' . $row[0] . '</td>';
            echo '<td>' . $row[2] . ($row[10] > 5 ? ' ' . $row[10] : '') . '</td>';
            echo '</tr>';
        }

        echo '</table></body></html>';
    }

    public function displayContracts(){
        if($this->action == 5){
            $query = $this->createSqlQuery(true);
        } else {
            $query = $this->createSqlQuery(false);
        }

        $results = mysql_query($query);
        $this->generateTable($results);
    }

}


// contracts
// 0 => id, 2 => nazwa przedsiebiorcy, 4 => NIP, 10 => kwota,

//define variables
$id = $_GET['i'] ?? null;
$action = $_GET['akcja'] ?? null;
$sort = $_GET['sort'] ?? null;
$dg_bgcolor = $dg_bgcolor ?? "#ffffff";

$contractManager = new Contractmanager($action, $sort, $dg_bgcolor, $id);
$contractManager->displayContracts();

