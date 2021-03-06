<?php

/**
 * Portfolio page. Show a table of all the author pictures. Clicking on one should show their quote.
 * Our quotes model has been autoloaded, because we use it everywhere.
 * 
 * controllers/Portfolio.php
 * By Evelyn Dai
 * ------------------------------------------------------------------------
 */
class Portfolio extends Application {

    function __construct() {
        parent::__construct();
    }

    //-------------------------------------------------------------
    //  The normal pages
    //-------------------------------------------------------------

    function index($user = null) {
        $this->data['pagebody'] = 'portfolio'; // this is the view we want shown

        if ($user == null) {
            $user = $this->session->userdata('username');
        }

        //Trading Activities
        $transaction = $this->Transactions->getTrans($user);
        $trans = array();

        foreach ($transaction as $record) {
            $trans[] = $record;
        }
        $this->data['transactions'] = $trans;
        //$this->data['debug'] = print_r($query->result_array(), true); 
        //Holdings
        $card_count = $this->collections->get_cards($user);
        $card_counts = $this->collections->sort_cards($card_count);
        $this->data['cards'] = $card_counts;
        
        
        $this->data['elevena0'] = $card_counts['elevena0'];
        $this->data['elevena1'] = $card_counts['elevena1'];
        $this->data['elevena2'] = $card_counts['elevena2'];

        $this->data['elevenb0'] = $card_counts['elevenb0'];
        $this->data['elevenb1'] = $card_counts['elevenb1'];
        $this->data['elevenb2'] = $card_counts['elevenb2'];

        $this->data['elevenc0'] = $card_counts['elevenc0'];
        $this->data['elevenc1'] = $card_counts['elevenc1'];
        $this->data['elevenc2'] = $card_counts['elevenc2'];

        $this->data['thirteenc0'] = $card_counts['thirteenc0'];
        $this->data['thirteenc1'] = $card_counts['thirteenc1'];
        $this->data['thirteenc2'] = $card_counts['thirteenc2'];

        $this->data['thirteend0'] = $card_counts['thirteend0'];
        $this->data['thirteend1'] = $card_counts['thirteend1'];
        $this->data['thirteend2'] = $card_counts['thirteend2'];

        $this->data['twentysixh0'] = $card_counts['twentysixh0'];
        $this->data['twentysixh1'] = $card_counts['twentysixh1'];
        $this->data['twentysixh2'] = $card_counts['twentysixh2'];
        
        //Dropdown select player
        $players = $this->player->getPlayer();
        $p = array();
        foreach ($players as $player) {
            $p[$player['Player']] = $player['Player'];
        }
        //Parse selected player to the url and redirect it
        $js = 'id="players" onChange="select_player(this);"';
        $this->data['players'] = form_dropdown('players', $p, $user, $js);


        //Pass these on to the view

        $this->render();
    }

}

/* End of file Portfolio.php */
/* Location: application/controllers/Portfolio.php */