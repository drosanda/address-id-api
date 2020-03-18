<?php
class Social_Enhancer {
	var $app_id="1670419043213964";
	var $title;
	var $type="website";
	var $description;
	var $url;
	var $locale="id_ID";
	var $site_name;
	var $image;
	var $card="photo";
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
	public function get(){
	 return array(
		"<meta property=\"fb:app_id\" content=\"".$this->app_id."\"/>",
		"<meta property=\"og:locale\" content=\"".$this->locale."\"/>",
		"<meta property=\"og:title\" content=\"".$this->title."\"/>",
		"<meta property=\"og:site_name\" content=\"".$this->site_name."\"/>",
		"<meta property=\"og:description\" content=\"".$this->description."\"/>",
		"<meta property=\"og:url\" content=\"".$this->url."\"/>",
		"<meta property=\"og:image\" content=\"".$this->image."\" />",
		"<meta property=\"twitter:card\" content=\"".$this->card."\" />",
		"<meta property=\"twitter:site\" content=\"".$this->site_name."\" />",
		"<meta property=\"twitter:url\" content=\"".$this->url."\" />",
		"<meta property=\"twitter:title\" content=\"".$this->title."\" />",
		"<meta property=\"twitter:description\" content=\"".$this->description."\" />",
		"<meta property=\"twitter:image\" content=\"".$this->image."\" />","
		<script>
			window.fbAsyncInit = function() {
				FB.init({
					appId      : '".$this->app_id."',
					xfbml      : true,
					version    : 'v2.5'
				});
			};

			(function(d, s, id){
				 var js, fjs = d.getElementsByTagName(s)[0];
				 if (d.getElementById(id)) {return;}
				 js = d.createElement(s); js.id = id;
				 js.src = \"//connect.facebook.net/en_US/sdk.js\";
				 fjs.parentNode.insertBefore(js, fjs);
			 }(document, 'script', 'facebook-jssdk'));
		</script>"
		);
	}
}