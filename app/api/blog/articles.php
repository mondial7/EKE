<?php

/**
 * Class: Articles
 * Example of API controller that handles the action Article of the feature Blog,
 * This class can be called through the url: http://example.com/api/blog/articles/?parameters
 *
 * WARNING!!!
 * Classes and Scripts called here are not included in the project. That is, this api is not doing anything at all.
 */
class Articles extends EKEApiController {

  /**
   * @var int
   */
  private $init = 0, $limit = 7;

  /**
   * @var object
   */
  private $Blog = null;

  /**
   * Main method, automatically run
   */
  public function run(){

  	// Instantiate the blog
  	require_once MODELS_DIR . '/Blog.php';
  	$this->Blog = new Blog();

  	// Get articles
  	if (isset($_GET['init']) && is_numeric($_GET['init'])) {

  	  	// set initial counter
  		$this->init = ($_GET['init'] > 0) ? $_GET['init'] : 0;

  		if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {

  			$this->limit = ($_GET['limit'] > 0) ? $_GET['limit'] : 7;

  			$articles = $this->Blog->getArticles($this->init, $this->limit);

  		} else {

  			$articles = $this->Blog->getArticles($this->init);

  		}


  	} else {

  		$articles = $this->Blog->getArticles();

  	}

  	// switch between json/html response
  	if (isset($_GET['html'])) {

  	  $template_variables['articles'] = $articles;

  	  // Render the template
  	  EKETwig::setDir(VIEWS_DIR . "/blog/");
  	  $this->response = EKETwig::getTemplate('blog__articles_async.twig', $template_variables);

  	} else {

  	  // default is a json response
  	  $this->response = json_encode($articles);

  	}

  	return $this;

  }


}
