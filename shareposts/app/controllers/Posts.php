<?php
  class Posts extends Controller {

    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }

      $this->postModel = $this->model('Post');
      $this->userModel = $this->model('User');
    }

    public function index() {
      // get posts

      $posts = $this->postModel->getPosts();

      $data = [
        'posts' => $posts
      ];


      $this->view('Posts/index', $data);
    }

    public function add() {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // sanitise post array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $data = [
          'title' => trim($_POST['title']),
          'body' => trim($_POST['body']),
          'user_id' => trim($_SESSION['user_id']),
          'title_err' => '',
          'body_err' => ''
        ];

        // validate title
        if(empty($data['title'])){
          $data['title_err'] = 'Please Enter Title';
        }

        // validate body
        if(empty($data['body'])){
          $data['body_err'] = 'Please Enter Body Text';
        }

        //make sure no errors
        if(empty( $data['title_err'] && $data['body_err'])){
          // validated
          if($this->postModel->addpost($data)){
            flash('post_message', 'Post Added');
            redirect('posts');
          } else {
            die('Something went wrong');
          }
        } else {
          //load view with error message
          $this->view('posts/add', $data);
        }


      } else {
      $data = [
        'title' => '',
        'body' => '' 
      ];}

      $this->view('Posts/add', $data);
    }

    public function show($id){
      $post = $this->postModel->getPostById($id);
      $user = $this->userModel->getUserById($post->user_id);

      $data = [
        'post' => $post,
        'user' => $user
      ];

      $this->view('posts/show', $data);
    }  

    public function edit($id) {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // sanitise post array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $data = [
          'id' => $id,
          'title' => trim($_POST['title']),
          'body' => trim($_POST['body']),
          'user_id' => trim($_SESSION['user_id']),
          'title_err' => '',
          'body_err' => ''
        ];

        // validate title
        if(empty($data['title'])){
          $data['title_err'] = 'Please Enter Title';
        }

        // validate body
        if(empty($data['body'])){
          $data['body_err'] = 'Please Enter Body Text';
        }

        //make sure no errors
        if(empty( $data['title_err'] && $data['body_err'])){
          // validated
          if($this->postModel->updatePost($data)){
            flash('post_message', 'Post Changed');
            redirect('posts');
          } else {
            die('Something went wrong');
          }
        } else {
          //load view with error message
          $this->view('posts/edit', $data);
        }


      } else {
        //get existing post from model
        $post = $this->postModel->getPostById($id);

        //check for owner
        if($post->user_id != $_SESSION['user_id']){
          redirect('posts');
        }


      $data = [
        'id' => $id,
        'title' => $post->title,
        'body' => $post->body 
      ];}

      $this->view('posts/edit', $data);
    }

    public function delete($id){
      //get existing post from model
      $post = $this->postModel->getPostById($id);

      //check for owner
      if($post->user_id != $_SESSION['user_id']){
        redirect('posts');
      }

      
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($this->postModel->deletePost($id)){
          flash('post_message', ('Post Deleted'));
          redirect('posts');
        } else {
          die('something went wrong');
        }
      } else {
        redirect('posts');
      }
    }
  }