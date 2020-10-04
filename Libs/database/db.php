<?php namespace database;

  class selectQuery
  {
    public $query="";
    public $block0 = "";
    public $block1 = "";
    public $block2 = "";
    public $block3 = "";
    public $block4 = "";

    public $conn;
    public $justCreated = false;
    public $joined = false;

    function __construct($conn, $query){
      $this->conn = $conn;
      $this->query = $query;
      $this->justCreated = true;
    }

    function get(){
      if(is_null($this->conn)){
        throw new \Exception('Connection is not assigned.');
        return false;
      }else{
        $this->query=trim(
        $this->query.
        $this->block0.
        $this->block1.
        $this->block2.
        $this->block3.
        $this->block4
        ).
        ";";
        $result = $this->conn->query($this->query);
        if($this->conn->error){
          throw new \Exception($this->conn->error);
          return false;
        }else{
          return $result;
        }
      }
    }

    function where($key, $value){
      if(is_null($this->conn)){
        throw new \Exception('Connection is not assigned.');
        return false;
      }else{
        if($this->justCreated != true){
          $this->block1=$this->block1."AND ";
        }else{
          if(!$this->joined){
            $this->block1=$this->block1."WHERE ";
          }else{
            $this->block1=$this->block1."ON ";
          }
          $this->justCreated=false;
        }

        $this->block1=$this->block1.$key."=".(!is_string($value)?$value:"'".$this->conn->real_escape_string($value)."'")." ";

        return $this;
      }
    }

    function orWhere($key, $value){
      if(is_null($this->conn)){
        throw new \Exception('Connection is not assigned.');
        return false;
      }else{
        if($this->justCreated != true){
          $this->block1=$this->block1."OR ";
        }else{
          if(!$this->joined){
            $this->block1=$this->block1."WHERE ";
          }else{
            $this->block1=$this->block1."ON ";
          }
          $this->justCreated=false;
        }
        $this->block1=$this->block1.$key."=".(!is_string($value)?$value:"'".$this->conn->real_escape_string($value)."'")." ";

        return $this;
      }
    }

    function joinWhere($key, $value){
      if(is_null($this->conn)){
        throw new \Exception('Connection is not assigned.');
        return false;
      }else{
        if($this->justCreated != true){
          $this->block1=$this->block1."AND ";
        }else{
          if(!$this->joined){
            $this->block1=$this->block1."WHERE ";
          }else{
            $this->block1=$this->block1."ON ";
          }
          $this->justCreated=false;
        }

        $this->block1=$this->block1.$key."=".$value." ";

        return $this;
      }
    }

    function orJoinWhere($key, $value){
      if(is_null($this->conn)){
        throw new \Exception('Connection is not assigned.');
        return false;
      }else{
        if($this->justCreated != true){
          $this->block1=$this->block1."OR ";
        }else{
          if(!$this->joined){
            $this->block1=$this->block1."WHERE ";
          }else{
            $this->block1=$this->block1."ON ";
          }
          $this->justCreated=false;
        }
        $this->block1=$this->block1.$key."=".$value." ";

        return $this;
      }
    }


    function join($tableToJoin){
      $this->joined = false;
      $this->block0 = "INNER JOIN $tableToJoin ";

      $this->block1 = str_replace("WHERE","ON",$this->block1);

      return $this;
    }

    function sort($by, $asc=1){
      $this->block2 = "ORDER BY $by";

      if($asc==1){
        $this->block2 = $this->block2." asc ";
      }else{
        $this->block2 = $this->block2." desc";
      }

      return $this;
    }

    function olderThan($dateHeader, $value, $operator="AND"){
      if(is_null($this->conn)){
        throw new \Exception('Connection is not assigned.');
        return false;
      }else{
        if($this->justCreated != true){
          $this->block1=$this->block1.$operator." ";
        }else{
          if(!$this->joined){
            $this->block1=$this->block1."WHERE ";
          }else{
            $this->block1=$this->block1."ON ";
          }
          $this->justCreated=false;
        }

        $this->block1=$this->block1.$dateHeader."< CURRENT_DATE - INTERVAL ".$value." DAY ";

        return $this;
      }
    }

    function youngerThan($dateHeader, $value, $operator="AND"){
      if(is_null($this->conn)){
        throw new \Exception('Connection is not assigned.');
        return false;
      }else{
        if($this->justCreated != true){
          $this->block1=$this->block1.$operator." ";
        }else{
          if(!$this->joined){
            $this->block1=$this->block1."WHERE ";
          }else{
            $this->block1=$this->block1."ON ";
          }
          $this->justCreated=false;
        }

        $this->block1=$this->block1.$dateHeader."> CURRENT_DATE - INTERVAL ".$value." DAY ";

        return $this;
      }
    }

    function limit($lim){
      $this->block3 = " limit $lim ";
      return $this;
    }

    function offset($off){
      $this->block4 = " offset $off ";
      return $this;
    }

  }

  class db
  {

    private $servername = "";
    private $username = "";
    private $password = "";
    private $dbname = "";
    private $dbport = "";

    private $conn;

    function __construct(
      $server,
      $un,
      $pass,
      $dbname,
      $dbport = "3306"
    )
    {
      $this->servername=$server;
      $this->username=$un;
      $this->password=$pass;
      $this->dbname=$dbname;
      $this->dbport=$dbport;

      $this->conn = new \mysqli(
        $this->servername,
        $this->username,
        $this->password,
        $this->dbname,
        $this->dbport
      );
    }

    function query($query){
      $result = $this->conn->query($query);

      if($result->num_rows==0){
        return false;
      }else{
        return $result;
      }
    }

    function insert($table, array $args){
      $params = "(";
      $values = "(";
      foreach ($args as $key => $value) {
          if(trim($params)!="("){
            $params=$params.",";
          }

          $params=$params." ".$key;

          if(trim($values)!="("){
            $values=$values.",";
          }

          $values=$values." ".(!is_string($value)?$value:"'".$this->conn->real_escape_string($value)."'");
      }

      $params=$params.")";
      $values=$values.")";

      $query = "INSERT INTO $table $params VALUES $values;";

      if($this->conn->query($query)){
        return true;
      }else{
        throw new \Exception($this->conn->error);
        return false;
      }
    }

    function select($table){
      $query = "SELECT * FROM ".$table." ";
      $selectQObj = new selectQuery($this->conn, $query);

      return $selectQObj;

    }

    public function __get($property) {
      if (property_exists($this, $property)) {
        return $this->$property;
      }
    }

    public function __set($property, $value) {
      if (property_exists($this, $property)) {
        $this->$property = $value;
      }

      return $this;
    }

  }


?>
