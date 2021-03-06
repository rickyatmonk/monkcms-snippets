<?php
/* ===================================================================== //

	META INFORMATION OUTPUT

	1. Initialize class in your template to set the module type.

		$meta = new pageMeta("module name", "default title", "facebook og image url", "facebook og type");

		"module name" - the module in use:
		page  |  blog  |  article  |  event  |  sermon  |  facebook

		"default title" - the title in case other methods fail

	2. Call variables.

		Default variables:
		$meta->page_title
		$meta->page_description
		$meta->page_keywords
		$meta->page_group
		$meta->page_image
    $meta->facebook_og

// ===================================================================== */

class pageMeta {
  public $sitename = "Site Name";
  public $page_title = "";
  public $page_description = "";
  public $page_keywords = "";
  public $page_group = "";
  public $page_image = "";
  public $debug = "";
  public $facebook_image = "";
  public $facebook_og = "";
  public $facebook_type = "";
  public $facebook = false;

  function pageMeta($modid, $default, $fbimg, $fbtype){

    //set default page title
    if($default){
      $this->page_title = $default;
    }

    if($fbimg){
      $this->facebook_image = $fbimg;
      $this->facebook = true;
    }

    if($fbtype){
      $this->facebook_type = $fbtype;
    }

    //get wildcard
    $ifdetail = explode("://",$_GET['wildcard']);

    //debug text output of wildcard
    //$this->debug = $ifdetail;

    // PAGES
    // if page/module uses display:auto will return $ifdetail[1] else just display page.
    if($ifdetail[1]!==""){
      if(strtolower($modid) == "page" || strtolower($modid) == "pages"){
        $pmeta = getContent(
          "page",
          "display:detail",
          "find:".$_GET['nav'],
          "show:__title__",
          "show:~|~",
          "show:__description__",
          "show:~|~",
          "show:__tags__",
          "show:~|~",
          "show:__group__",
          "show:~|~",
          "noecho"
        );
        $pmeta .= getContent("media","display:detail","find:".$_GET['nav'],"label:header","show:__imageurl maxWidth='300' maxHeight='300'__",'noecho');
        $this->assignMeta($pmeta, $this->facebook);

      // BLOGS
      }else if(strtolower($modid) == "blog" || strtolower($modid) == "blogs"){
        $pmeta = getContent(
          "blog",
          "display:auto",
          "howmany:1",
          "before_show_postlist:__blogtitle__",
          "before_show_postlist:~|~",
          "before_show_postlist:__blogdescription__",
          "before_show_postlist:~|~",
          "before_show_postlist:~|~",
          "before_show_postlist:__group__",
          "before_show_postlist:~|~",
          "before_show_postlist:---headerimage---",
          "show_detail:__blogtitle__ - __blogposttitle__",
          "show_detail:~|~",
          "show_detail:__blogsummary__",
          "show_detail:~|~",
          "show_detail:__tags__",
          "show_detail:~|~",
          "show_detail:__group__",
          "show_detail:~|~",
          "show_detail:__imageurl maxWidth='300' maxHeight='300'__",
          "noecho"
          );
          $blog_header_image = getContent("media","display:detail","find:".$_GET['nav'],"label:header","show:__imageurl maxWidth='300' maxHeight='300'__",'noecho');
          $pmeta = str_replace('---headerimage---',$blog_header_image,$pmeta);
          $this->assignMeta($pmeta);

		// ARTICLES
      }else if(strtolower($modid) == "article" || strtolower($modid) == "articles"){
        $pmeta = getContent(
          "article",
          "display:detail",
          "find:".$_GET['slug'],
          "show:__title__",
          "show:~|~",
          "show:__summary__",
          "show:~|~",
          "show:__tags__",
          "show:~|~",
          "show:__group__",
          "show:~|~",
          "show:__imageurl maxWidth='300' maxHeight='300'__",
          "noecho"
          );
          $this->assignMeta($pmeta);

      // EVENTS
      }else if(strtolower($modid) == "event" || strtolower($modid) == "events"){
        $pmeta = getContent(
          "event",
          "display:detail",
          "find:".$_GET['slug'],
          "show:__title__",
          "show:~|~",
          "show:__summary__",
          "show:~|~",
          "show:__category__",
          "show:~|~",
          "show:__group__",
          "show:~|~",
          "show:__imageurl maxWidth='300' maxHeight='300'__",
          "noecho"
          );
          $this->assignMeta($pmeta);

      // PRODUCTS
      }else if(strtolower($modid) == "product" || strtolower($modid) == "products"){
        $pmeta = getContent(
          "products",
          "display:auto",
          "howmany:1",
          "before_show_productlist:__familytitle__",
          "show_detail:__familytitle__ - __producttitle__",
          "show_detail:~|~",
          "show_detail:__productdescription__",
          "show_detail:~|~",
          "show_detail:__producttags__",
          "show_detail:~|~",
          "show_detail:__group__",
          "show_detail:~|~",
          "show_detail:__productimageURL maxWidth='300' maxHeight='300'__",
          "noecho"
          );
          $this->assignMeta($pmeta);

      // SERMONS
      }else if(strtolower($modid) == "sermon" || strtolower($modid) == "sermons"){
        $pmeta = getContent(
          "sermon",
          "display:auto",
          "howmany:1",
          "show_detail:__title__",
          "show_detail:~|~",
          "show_detail:__summary__",
          "show_detail:~|~",
          "show_detail:__tags__",
          "show_detail:~|~",
          "show_detail:__group__",
          "show_detail:~|~",
          "show_detail:__imageurl maxWidth='300' maxHeight='300'__",
          "noecho"
          );
        if(trim($pmeta)==''){
	        $pmeta = getContent(
	          "sermon",
	          "display:detail",
	          "find:".$_GET['sermonslug'],
	          "show:__title__",
	          "show:~|~",
	          "show:__summary__",
	          "show:~|~",
	          "show:__tags__",
	          "show:~|~",
	          "show:__group__",
	          "show:~|~",
	          "show:__imageurl maxWidth='300' maxHeight='300'__",
	          "noecho"
	          );
	        }
          $this->assignMeta($pmeta);
      }
    }else{
        $pmeta = getContent(
          "page",
          "display:detail",
          "find:".$_GET['nav'],
          "show:__title__",
          "show:~|~",
          "show:__description__",
          "show:~|~",
          "show:__tags__",
          "show:~|~",
          "show:__group__",
          "noecho"
          );
        $pmeta .= getContent("media","display:detail","find:".$_GET['nav'],"label:header","show:__imageurl maxWidth='300' maxHeight='300'__",'noecho');
        $this->assignMeta($pmeta);
    }
  }

  // assigns module data to class variables for output
  private function assignMeta($value){
      list($ptitle,$pdes,$ptag,$pgroup,$pimage) = explode("~|~",$value);
      function processMetaItem($meta_input){
	  		return trim(strip_tags($meta_input));
	   }
	   if($ptitle=='INDEX'){
      	global $MCMS_SITENAME;
	      $ptitle = $MCMS_SITENAME;
      }
      if($ptitle!=''){
      	$this->page_title = processMetaItem($ptitle);
      }
      $this->page_description = processMetaItem($pdes);
      $this->page_keywords = processMetaItem($ptag);
      $this->page_group = processMetaItem($pgroup);
      $this->page_image = processMetaItem($pimage);

      if($this->facebook){
        $this->facebook_og .= "<meta property='og:image' content='".$this->facebook_image."'/>\n";
        $this->facebook_og .= "<meta property='og:title' content='".$this->page_title."'/>\n";
        $this->facebook_og .= "<meta property='og:url' content='".$this->getURL()."'/>\n";
        $this->facebook_og .= "<meta property='og:site_name' content='".$this->sitename."'/>\n";
        if($facbook_type != ""){
          $this->facebook_og .= "<meta property='og:type' content='".$this->fbtype."'/>\n";
        }
      }
  }

  private function getURL(){
     $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https')
                     === FALSE ? 'http' : 'https';
     $host     = $_SERVER['HTTP_HOST'];
     $script   = $_SERVER['SCRIPT_NAME'];
     $uri      = $_SERVER['REQUEST_URI'];
     $params   = $_SERVER['QUERY_STRING'];

     $currentUrl = $protocol . '://' . $host . $uri;
     return $currentUrl;
   }
}
?>