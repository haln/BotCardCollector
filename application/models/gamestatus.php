<?php

class Gamestatus extends main_Model{

  //Setting variables
  protected $series = array();
  protected $collection = array();
  protected $players = array();
  protected $total_cards;
  protected $cards_left;
  protected $collection_info = array();

  //GameStatus Constructor
  function _construct(){
    parent::_construct();
    $this->update_gamestatus();
  }

  function update_gamestatus(){
    //Reset variables to defaults
    $this->total_cards = 0;
    $this->cards_left = 0;

    //Setup necessities
    $current_player = $this->session->userdata('username');

    //Update players
    $this->players = $this->db->get('players')->result_array();
    //Setup players array to hold equity information
    for($x = 0; $x < count($this->players); $x++){
			$this->players[$x]['Equity'] = 0;
		}

    //Update series
    $this->series = $this->db->get('series')->result_array();
    //Manipulate data on series information to generate card information
    for($x = 0; $x < count($this->series); $x++){
      $this->series[$x]['Used'] = 0;
      $this->total_cards = $this->total_cards + $this->series[$x]['Frequency'];
    }
    $this->cards_left = $this->total_cards;

    //Update collection
    $this->collection = $this->db->get('collections')->result_array();
    //Manipulate collection data to create player and series information
    foreach($this->collection as $record){
      $this->cards_left = $this->cards_left - 1;
      $coll_series = intval(substr($record['Piece'],0,2));
      $serieskey = array_search($coll_series,array_column($this->series,'Series'));
      $this->series[$serieskey]['Used'] = $this->series[$serieskey]['Used'] + 1;
      $playerkey = array_search($record['Player'],array_column($this->players,'Player'));
      $this->players[$playerkey]['Equity'] = $this->players[$playerkey]['Equity'] + $this->series[$serieskey]['Value'];
      if($record['Player'] == $current_player){
        if(empty($this->collection_info[$coll_series])){
          $this->collection_info[$coll_series] = 1;
        }
        else{
          $this->collection_info[$coll_series] = $this->collection_info[$coll_series] + 1;
        }
      }
    }
  }

  //Returns an array with information about players
  function get_players(){
    $this->update_gamestatus();
    return $this->players;
  }

  //Returns an array with information about card series
  function get_series(){
    $this->update_gamestatus();
    return $this->series;
  }

  //Returns an array with information about card collections
  function get_collection(){
    $this->update_gamestatus();
    return $this->collection;
  }

  function get_total_cards(){
    $this->update_gamestatus();
    return $this->total_cards;
  }

  function get_cards_left(){
    $this->update_gamestatus();
    return $this->cards_left;
  }

  function get_collection_info(){
    $this->update_gamestatus();
    return $this->collection_info;
  }
}
?>
