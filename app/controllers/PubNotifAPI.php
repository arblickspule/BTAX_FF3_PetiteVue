<?php

class PubNotifAPI extends ApiController {
    
  function get() {
    // I think we're done here 
    try {
      
      $id = $this->f3->get("PARAMS.id");
      
      $this->f3->logger->write("PubNotifAPI: GET ".$id);    
      
      $data = $this->db->exec("SELECT * FROM 'pub_notifs' WHERE id=?", $id);    
      
      if (count($data)==0)
        throw new Exception("PubNotif (ID: ".$id.") not found", 1);
      
      $this->f3->logger->write("PubNotif (ID: ".$id.") found");     
      
      echo json_encode($data);
      
    } catch (Exception $e) {
      
      $this->f3->logger->write("PubNotifAPI: GET - error: ".$e->getMessage());
      $this->f3->error(404);
          
    }   
  }
  
  function post() {
    //Logging to be added
    $id = $this->f3->get("PARAMS.id");
    $query=$this->f3->get('QUERY');
    
    $this->f3->logger->write("PubNotifAPI: POST ".$id);
    try {
      //! Going to have to read in column data and map data to array from input
      
      // if (!is_numeric($id))
      //   throw new Exception("Invalid id, integer expected", 1);

      // stripslashes probably not needed
      $queryArray = array_map('stripslashes',$this->http_parse_query($query));
      
      // $queryArray['id'] = $id;

      ksort($queryArray);

      $columnList = array_keys($queryArray);

      var_dump(
        $this->db->exec(
          "INSERT INTO `pub_notifs` (".implode(', ',$columnList).") VALUES(".implode(',', array_fill(0, count($columnList), '?')).")",
          array_values($queryArray)
        )
      );
    } catch (Exception $e) {
      echo 'Err: '.$e->getCode().' : '.$e->getMessage().PHP_EOL;
      switch($e->getCode()) {
        case 1:
          $this->f3->error(500);
          break;
        default:
          $this->f3->error(501);
          break;
      }
    } finally {
      echo json_encode($this->db->exec("SELECT * FROM 'pub_notifs' WHERE id=?", $id));
    }    
  }
  function put() {
    //Logging to be added
    $id = $this->f3->get("PARAMS.id");

    try {
      //! Going to have to read in column data and map data to array from input
      
      if (!is_numeric($id))
        throw new Exception("Invalid id, integer expected", 1);

      // stripslashes probably not needed
      $queryArray = array_map('stripslashes',$this->http_parse_query($this->f3->get('QUERY')));
      
      // $queryArray['id'] = $id;

      ksort($queryArray);

      $columnList = array_keys($queryArray);

      $sql_values = '';
      $sep = '';
      foreach ($queryArray as $key => $val) {
          $sql_values .= $sep . '`' . $key . '`="' . $val . '"';
          $sep = ', ';
      }
      echo "UPDATE pub_notifs SET ".$sql_values." WHERE id=".$id.PHP_EOL;
      var_dump(
        $this->db->exec(
          // "INSERT INTO `pub_notifs` (".implode(', ',$columnList).") VALUES(".implode(',', array_fill(0, count($columnList), '?')).")",
          "UPDATE pub_notifs SET ".$sql_values." WHERE id=".$id
          // array_values($queryArray)
        )
      );
      // throw new Exception("Gaargh!!");
    } catch (Exception $e) {
      echo 'Err: '.$e->getCode().' : '.$e->getMessage().PHP_EOL;
      switch($e->getCode()) {
        case 1:
          $this->f3->error(500);
          break;
        default:
          $this->f3->error(501);
          break;
      }
    } finally {
      echo json_encode($this->db->exec("SELECT * FROM 'pub_notifs' WHERE id=?", $id));
    }
  }
  function delete() {
    //Copy to trash table
    //Logging to be added
    try {
      echo $this->db->exec("DELETE FROM 'pub_notifs' WHERE id=?", $this->f3->get("PARAMS.id")).PHP_EOL;
    } catch (Exception $e) {
      var_dump($e);
    }
  }

  function breaking() {
    try {
      
      $this->f3->logger->write("PubNotifAPI->breaking: GET ");    
      
      $data = $this->db->exec("SELECT * FROM 'pub_notifs' WHERE expires < ?", date("Y-m-d H:i:s"));    
      
      if (count($data)==0)
        throw new Exception("PubNotif->breaking:  no data found", 1);
      
      // $this->f3->logger->write("PubNotif (ID: ".$id.") found");    

      if (count($data) > 1) {
        //TODO Return latest & highest severity 
      } else {      
        echo json_encode($data);
      }
      
    } catch (Exception $e) {
      
      $this->f3->logger->write("PubNotifAPI: GET - error: ".$e->getMessage());
      $this->f3->error(404);
          
    }   
  }
  


  //========================
  // Tests
  //
  function testHttpParseQuery () {
    $query = $this->f3->get("QUERY");
    $test = new Test;
    //! Something wrong with call internal function like this
    // $test->expect(
    //   is_callable($this->http_parse_query($query)), 'Is http_parse_query a function'
    // );
    $test->expect(
      is_string($query),
      'Is query is a string'
    );
    $test->expect(
      is_array($this->http_parse_query($query)),
      'Is result an array'
    );
    $test->message($this->http_parse_query($query));
    $this->printTest($test);
  }
}