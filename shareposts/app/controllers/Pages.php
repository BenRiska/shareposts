<?php

// default controller, assigns index and about pages depending on the url given
  class Pages extends Controller {
    public function __construct(){
     
    }
    
    public function index(){
      if(isloggedIn()) {
        redirect(posts);
      }

      $data = [
        'title' => 'SharePosts',
        'description' => 'Simple social network built on the BenMVC PHP framework'
      ];
     
      $this->view('pages/index', $data);
    }

    public function about(){
      $data = [
        'title' => 'About Us',
        'description' => 'App to share posts with other users'
      ];

      $this->view('pages/about', $data);
    }
  }