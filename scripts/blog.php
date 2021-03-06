<?php

$_dbh = null;


/**
 * Retrieve an instance of a PDO
 * @return Ambigous <PDO, NULL>|boolean
 */

function getPDO() {
	global $_dbh;
	$dsn = 'mysql:dbname=zsouserc_db;host=127.0.0.1';
	$user = 'zsouserc_live';
	$password = 'CENSORED';
	try {
		if ($_dbh == null)
			$_dbh = new PDO($dsn, $user, $password);
		return $_dbh;
	} catch (PDOException $e) {
 		echo 'Connection failed: ' . $e->getMessage();
 		return false;
 	}
}

/**
 * Blog data model
 * @author zsouser
 *
 */

class Blog {
	/** Data instance of the blog **/
	public $blog;
	/** Number of posts per page **/
	public static $PERPAGE = 3;
	/** Specific page labels **/
	public static $LABELS = array("blah" => 1);
	
	/**
	 * Constructor
	 * @param data object $blog
	 */
	public function __construct($blog = null) {
		$this->blog = $blog;
	}
	
	/**
	 * Number of pages
	 * @return number
	 */
	
	public function numPages() {
		$stmt = getPDO()->prepare("SELECT COUNT(id) as num FROM posts WHERE blog_id = :blog");
		$stmt->bindValue(":blog",$this->blog->id,PDO::PARAM_INT);
		$stmt->execute();
		return (integer)ceil($stmt->fetch(PDO::FETCH_OBJ)->num / self::$PERPAGE);
	}
	
	/**
	 * Create a new blog
	 * 
	 * @param string $title
	 * @param string $desc
	 * @return boolean
	 */
	
	public static function create($title,$desc) {
		$stmt = getPDO()->prepare("INSERT INTO blogs (title,description) VALUES(:title,:desc)");
		$stmt->bindValue(':title',$title,PDO::PARAM_STR);
		$stmt->bindValue(':desc',$desc,PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	/**
	 * Read a blog by id
	 * @param int $blog_id
	 * @return boolean|Blog
	 */
	
	public static function read($blog_id) {
		if ((integer)$blog_id < 0) return false;
		$stmt = getPDO()->prepare("SELECT * FROM blogs WHERE id = :id");
		$stmt->bindValue(':id',$blog_id,PDO::PARAM_INT);
		$stmt->execute();
		return new Blog($stmt->fetch(PDO::FETCH_OBJ));
	}
	
	/**
	 * Read by name - duplicate of read but with name key
	 * 
	 * @param string $blog_name
	 * @return Ambigous <boolean, Blog>|boolean
	 */
	
	public static function readName($blog_name) {
		if (isset(self::$LABELS[$blog_name])) {
			return self::read(self::$LABELS[$blog_name]);
		}
		return false;
	}
	
	/**
	 * Save an update to the blog
	 * @param unknown_type $blog_id
	 * @param unknown_type $title
	 * @param unknown_type $desc
	 */
	
	public static function update($blog_id,$title,$desc) {
		$stmt = getPDO()->prepare("UPDATE blogs SET title = :title, description = :desc WHERE id = :blog");
		$stmt->bindValue(':title',$title,PDO::PARAM_STR);
		$stmt->bindValue(':desc',$desc,PDO::PARAM_STR);
		$stmt->bindValue(':blog',$blog_id,PDO::PARAM_INT);
		$stmt->execute();
	}
	
	/**
	 * Destroy the blog
	 * 
	 * @param unknown_type $blog_id
	 * @return boolean
	 */
	
	public static function destroy($blog_id) {
		$stmt = getPDO()->prepare("DELETE FROM blogs WHERE id = :id");
		$stmt->bindValue(':id',$blog_id,PDO::PARAM_INT);
		if ($stmt->execute()) {
			$stmt = getPDO()->prepare("DELETE FROM posts WHERE blog_id = :id");
			$stmt->bindValue(':id',$blog_id,PDO::PARAM_INT);
			return $stmt->execute();
		}
		return false;
	}
	
	/**
	 * Load the posts by page
	 * @param unknown_type $page
	 * @return boolean|multitype:
	 */
	public function posts($page) {
		if (--$page < 0) return false;
		$stmt = getPDO()->prepare("SELECT * FROM posts WHERE blog_id = :id ORDER BY id DESC LIMIT :page, :per");
		$stmt->bindValue(':id',$this->blog->id,PDO::PARAM_INT);
		$stmt->bindValue(':page',$page*self::$PERPAGE,PDO::PARAM_INT);
		$stmt->bindValue(':per',self::$PERPAGE,PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	/**
	 * List all blogs
	 * @return multitype:
	 */
	
	public static function all() {
		$stmt = getPDO()->prepare("SELECT DISTINCT(blogs.id) as id, blogs.title, blogs.description FROM blogs LEFT JOIN posts ON posts.blog_id = blogs.id ORDER BY posts.date DESC");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	/**
	 * View - toString
	 * @return string
	 */
	
	public function __toString() {
		return "<div id='blog' data-id='".$this->blog->id."'><h2 id='blog-title'>" . $this->blog->title."</h2>
			<div id='blog-desc'>".$this->blog->description."</div>
			<div id='new-edit'></div>
			<div id='controls'>
			<div id='edit'>-</div>
			<div id='new'>
			
			<div class='control'>+</div>
			</div>
			</div>
			</div>
			<hr>";
	}

}


/**
 * Post data model
 * @author zsouser
 *
 */
class Post {
	public $post;
	/** Constructor **/
	public function __construct($post = null) {
		$this->post = $post;
	}
	
	/** 
	 * Statically create a post
	 * @param unknown_type $title
	 * @param unknown_type $body
	 * @param unknown_type $blog
	 * @return boolean
	 */
	public static function create($title,$body,$blog) {
		if (strlen($title) > 140) return false;
		$stmt = getPDO()->prepare("INSERT INTO posts (title, body, blog_id) values (:title, :body, :blog)");
		$stmt->bindValue(':title',$title,PDO::PARAM_STR);
		$stmt->bindValue(':body',$body,PDO::PARAM_STR);
		$stmt->bindValue(':blog',$blog,PDO::PARAM_INT);
		return $stmt->execute();
	}
	
	/**
	 * Statically read a post from the database
	 * @param unknown_type $post_id
	 * @return boolean|Post
	 */
	public static function read($post_id) {
		if ((integer)$post_id < 0) return false;
		$stmt = getPDO()->prepare("SELECT * FROM posts WHERE id = :id");
		$stmt->bindValue(':id',$post_id,PDO::PARAM_INT);
		$stmt->execute();
		return new Post($stmt->fetch(PDO::FETCH_OBJ));
	}
	
	/**
	 * Statically update a post
	 * @param unknown_type $post_id
	 * @param unknown_type $title
	 * @param unknown_type $body
	 * @return boolean
	 */
	public static function update($post_id,$title,$body) {
		if (strlen($title) > 140) return false;
		$stmt = getPDO()->prepare("UPDATE posts SET title = :title, body = :body WHERE id = :id");
		$stmt->bindValue(':title',$title,PDO::PARAM_STR);
		$stmt->bindValue(':body',$body,PDO::PARAM_STR);
		$stmt->bindValue(':id',$post_id,PDO::PARAM_INT);
		return $stmt->execute();
	}
	
	/**
	 * Destroy a post
	 * @param unknown_type $post_id
	 * @return boolean
	 */
	public static function destroy($post_id) {
		$stmt = getPDO()->prepare("DELETE FROM posts WHERE id = :id");
		$stmt->bindValue(':id',$post_id,PDO::PARAM_INT);
		return $stmt->execute();
	}
	
	/**
	 * View a post
	 * @return string
	 */
	public function __toString() {
		return "<div id='post' class='post-{$this->post->id}'>
		 <div class='top'>
		  <div class='post-title' id='title-{$this->post->id}'><b>{$this->post->title}</b></div>
		  <i>Posted {$this->post->date}</i>
		  <div class='control' data-id='{$this->post->id}'>&raquo;</div>
		 </div>
		 <div class='body' id='body-{$this->post->id}'>".stripslashes($this->post->body)."</div></div>";
	}
}

/**
 * Posts Controller
 * @author zsouser
 *
 */

class PostsController {
	private $post;
	private $blog_id;
	
	/**
	 * Constructor interprets the route and calls appropriate methods
	 * @param unknown_type $blog
	 * @param unknown_type $params
	 */
	public function __construct($blog,$params) {
		$this->blog_id = $blog;
		if (isset($params[0])) {
			if ((integer)$params[0] > 0) $this->post = Post::read(array_shift($params));
			else if (method_exists($this,$params[0])) call_user_method(array_shift($params),$this,$params);
			if ($this->post != null) {
				if (isset($params[0]) && method_exists($this,$params[0])) call_user_method(array_shift($params),$this,$params);
				else echo $this->post;
			}
		}
	}
	/**
	 * Create a new post
	 * @param unknown_type $params
	 */
	public function create($params) {
		if (isset($_POST['title']) && isset($_POST['body'])) {
			ob_clean();
			if (Post::create($_POST['title'],$_POST['body'],$this->blog_id)) {
				$id = getPDO()->lastInsertId();
				echo Post::read($id);
			}
			die;
		}
	}
	/**
	 * Edit a post
	 * @param unknown_type $params
	 */
	public function edit($params) {
		$params[0] = "blog";
		include "auth.php";
		if (isset($_POST['title']) && isset($_POST['body'])) {
			ob_clean();
			Post::update($this->post->post->id,stripslashes($_POST['title']),stripslashes($_POST['body']));
			$body = $_POST['body'];
			echo json_encode(array("title"=>stripslashes($_POST['title']),"body"=>stripslashes($body)));
			die;
		}
	}
	
	/**
	 * Destroy a post
	 * @param unknown_type $params
	 */
	public function destroy($params) {
		$params[0] = "blog";
		include "auth.php";
		Post::destroy($this->post->post->id);
	}
}
class BlogController {
	private $blog;
	/**
	 * Constructor interprets the route and calls appropriate methods
	 * @param unknown_type $blog
	 * @param unknown_type $params
	 */
	public function __construct($params) {
		if (isset($params[0])) {
			if ((integer)$params[0] > 0) $this->blog = Blog::read(array_shift($params));
			else if (method_exists($this,$params[0])) call_user_method(array_shift($params),$this,$params);
			else $this->blog = Blog::readName(array_shift($params));
			if ($this->blog != null && isset($params[0])) {
				//var_dump($params);
				if ($params[0] == "posts") {
					array_shift($params);
					$posts = new PostsController($this->blog->blog->id,$params);	
				}
				else if ((integer)$params[0] > 0) call_user_method('show',$this,$params);
				else if (method_exists($this,$params[0])) call_user_method(array_shift($params),$this,$params);
				else echo "poop";
			} 
			else if ($this->blog != null) call_user_method('show',$this,array(1));
			else echo "poo";
		}
		else call_user_method('index',$this,null);
	}
	
	/**
	 * Load the index
	 * @param unknown_type $params
	 */
	public function index($params) {
		echo "<div id='blogs'>";
			foreach (Blog::all() as $blog) {
				echo "<div id='blog-thumb'><h4>".$blog->title."</h4><p hidden>".$blog->description."<br><br><a href='/blog/".$blog->id."'>Click Here to View</a></p></div>";
			}	
			echo "</div>";
			echo "<div id='add-blog'><div class='control'>+</div></div>";
	}
	
	/**
	 * Create a blog
	 * @param unknown_type $params
	 */
	
	public function create($params) {
		$params[0] = "blog";
		include "auth.php";
		if (isset($_POST['title']) && isset($_POST['desc'])) {
			ob_clean();
			if (Blog::create($_POST['title'],$_POST['desc'])) {
				$this->blog = Blog::read(getPDO()->lastInsertId());
				echo "<div id='blog-thumb'><h4>".$blog->title."</h4><p hidden>".$blog->description."<br><br><a href='/blog/".$blog->id."'>Click Here to View</a></p></div>";
			}
			else var_dump( getPDO()->errorInfo());
			die;
		}
		echo "poo";
	}
	
	/**
	 * Show a blog
	 * @param unknown_type $params
	 */
	
	public function show($params) {
		echo $this->blog;
		echo "<div id='posts' data-page='".$params[0]."'>";
		$posts = $this->blog->posts($params[0]);
		if (empty($posts)) {
			echo "No posts yet!";
		}
		else foreach ($posts as $post) {
			
			echo new Post($post);
		}
		echo "</div>";
		$numPages = $this->blog->numPages();
		$page = $params[0];
		$max = $page+1;
		echo "<div id='pages'>";
		if ($numPages > 3) 
		for ($i = $page - 1; $i <= $max; $i++) {
			if ($i <= 0) {
				$i++;
				$max++;
			}
			if ($max > $numPages) {
				$i -= $max - $numPages;
				$max = $numPages;
			}
			
			if ($i == $page) echo "<b>";
			echo "<a href='/blog/".$this->blog->blog->id."/$i'>$i</a>";
			if ($i == $page) echo "</b>";
			
		}
		else
		for ($i = 1; $i <= $numPages; $i++) {
			if ($i == $page) echo "<b>";
			echo "<a href='/blog/".$this->blog->blog->id."/$i'>$i</a>";
			if ($i == $page) echo "</b>";

		}
		echo "</div>";
	}
	
	/**
	 * Edit a blog
	 * @param unknown_type $params
	 */
	public function edit($params) {
		$params[0] = "blog";
		include "auth.php";
		if (isset($_POST['title']) && isset($_POST['desc'])) {
			Blog::update($this->blog->blog->id,$_POST['title'],$_POST['desc']);
			ob_clean();
			header("Content-Type:text/javascript");
			echo json_encode(array("title"=>$_POST['title'],"desc"=>$_POST['desc']));
			die;
		}
	}
	
	/**
	 * Destroy
	 * @param unknown_type $params
	 */
	
	public function destroy($params) {
		$params[0] = "blog";
		include "auth.php";
		Blog::destroy($this->blog->blog->id);
	}
}

$blog = new BlogController($params);
	
?>