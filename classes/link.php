<?php 


  class link {
    
    /**
     * Link Class for givemea.link!
     * @author Armando León
     */
    
    private $id, $url, $upvotes, $downvotes, $nsfw, $info;
    private $connection;
    
    public function __construct($id=null) {
      
      //Get an ID, either random or provided (optional). Hydrate the object. 
      
      $this->connection = new mysqli("localhost", "armandoj_getlink", "password", "armandoj_getmealink");
      
      if ($id == null) {
        
        //Selects random ID. ID cannot have more 2 NSFW of Unfunctional points.
        
        $this->id = $this->connection->query("SELECT id FROM links WHERE nsfw <= 3 AND functional <= 3 ORDER BY RAND() LIMIT 1")->fetch_assoc()['id'];
                
      } else {
        
        $this->id = $id;
        
      }
      
      $this->hydrate();
      
    }
    
    public function hydrate() {
      
      //Set info array and various tags. 
      
      $this->info = $this->connection->query("SELECT * FROM links WHERE id = {$this->id}")->fetch_assoc();
      $this->url = $this->info['url'];
      $this->upvotes = $this->info['upvotes'];
      $this->downvotes = $this->info['downvotes'];
      $this->nswf = $this->info['nsfw'];
      $this->functional = $this->info['functional'];
      $this->processed = $this->processedURL();
      $this->info['processed'] = $this->processed;
      
      //For JSON, add a status
      
      $this->info['status'] = 'success';
      
    }
    
    private function failJSON() {
      
      //Currently not in use. Would return a failed JSON status. 
      
      $this->info['status'] = 'failure';
      header('Content-type: application/json');
      echo json_encode($this->info);
      die();
      
    }
    
    public function returnJSON() {
      
      //Returns the object's properties as a JSON file.
      
      header('Content-type: application/json');
      echo json_encode($this->info);
      
    }
    
    public function getValue($string=null) {
      
      //Returns a specific value if requested, otherwise returns the whole info array. 
      
      if ($string !== null) {  
      
        return $this->info[$string];
        
      } else {
      
        return $this->info;
        
      }
      
    }
    
    public function addFunctional() {
      
      //Adds one point for an unfunctional link.
      
      $this->functional = $this->functional + 1;
      $this->connection->query("UPDATE links SET functional = {$this->functional} WHERE id = {$this->id}");
      $this->returnJSON();
      
    }
    
    public function addNSFW() {
      
      //Adds one point for an adult link. 
      
      $this->nsfw = $this->nsfw + 1;
      $this->connection->query("UPDATE links SET nsfw = {$this->nsfw} WHERE id = {$this->id}");
      $this->returnJSON();
      
    }
    
    public function processedURL() {
      
      //Returns just the host of a URL, to be displayed as the link. Removes www.
      
      $processed = parse_url($this->url)['host'];
      $processed = str_replace("www.", "", $processed);
      return $processed;
      
      
    }
    
    
  }     


?>
