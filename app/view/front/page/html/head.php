<head>
  <meta charset="utf-8">
  <script async src="https://cdn.ampproject.org/v0.js"></script>
  <title><?php echo $this->getTitle(); ?></title>
  <link rel="canonical" href="https://amp.dev/documentation/guides-and-tutorials/start/create/basic_markup/">
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
  <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>


  <?php $this->getAdditionalBefore(); ?>
  <?php $this->getAdditional(); ?>
  <?php $this->getAdditionalAfter(); ?>

  <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>

  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <style amp-custom>
  html {
background-color: #fff;
font-size: 20px;
-moz-osx-font-smoothing: antialiased;
-webkit-font-smoothing: antialiased;
min-width: 300px;
overflow-x: hidden;
overflow-y: scroll;
text-rendering: optimizeLegibility;
-webkit-text-size-adjust: 100%;
-moz-text-size-adjust: 100%;
-ms-text-size-adjust: 100%;
text-size-adjust: 100%;
-webkit-tap-highlight-color: transparent;
}

a {
cursor: pointer;
text-decoration: none;
}
a:hover, a:focus {
text-decoration: underline;
}

body, button, input, select, textarea {
  font-family: BlinkMacSystemFont,-apple-system,"Segoe UI","Roboto","Oxygen","Ubuntu","Cantarell","Fira Sans","Droid Sans","Helvetica Neue","Helvetica","Arial",sans-serif;
}
body {
color: #333;
font-size: 1em;
font-weight: 400;
line-height: 1.5;
}
.hamburger{
 width: 30px;
 height: 15px;
 display:block;
 cursor: pointer;
 position: fixed;
 top: 1em;
 right: 1em;
 z-index: 1;
 border-radius: 3px;
 font-size: 2em;
 font-weight: bolder;
 color: #f44336;
}


#sidebar1 {
  line-height: 1.25;
}
amp-sidebar#sidebar1 {
  background-color: hsl(357deg 93% 76%);
  padding: 0.5em;
}

amp-sidebar#sidebar1 ul {
  list-style: none;
}

amp-sidebar#sidebar1 li {
  margin: 0;
  padding: 0;
  margin: 1em 0;
}

amp-sidebar#sidebar1 li a {
}

amp-sidebar#sidebar1 a {
  color: #fff;
  border-radius: 2px;
  font-size: 1em;
  padding: .5em .75em;
}
amp-sidebar#sidebar1 a:focus, amp-sidebar#sidebar1 a:hover {
  background-color: #ed6f75;
  color: #fff;
}

amp-sidebar#sidebar1 ul li ul {
  border-left: 1px solid #dbdbdb;
  margin: .75em;
  padding-left: .75em;
}


.main-content {
  min-height: 90vh;
}

section, .section {
  padding: 2.5rem 3.5rem;
}
.container {
  margin: 0 auto;
  position: relative;
  width: auto;
}
.columns {
  margin-left: -.75rem;
  margin-right: -.75rem;
  margin-top: -.75rem;
}
.columns:not(:last-child) {
  margin-bottom: .75rem;
}
.columns:not(.is-desktop) {
}
.column {
  display: block;
  padding: .75rem;
}

blockquote, body, dd, dl, dt, fieldset, figure, h1, h2, h3, h4, h5, h6, hr, html, iframe, legend, li, ol, p, pre, textarea, ul {
    margin: 0;
    padding: 0;
}

.content blockquote:not(:last-child), .content dl:not(:last-child), .content ol:not(:last-child), .content p:not(:last-child), .content pre:not(:last-child), .content table:not(:last-child), .content ul:not(:last-child) {
    margin-bottom: 1em;
}
.content ul {
    list-style: disc outside;
    margin-left: 2em;
    margin-top: 1em;
}
.content ol {
    list-style-position: outside;
    margin-left: 2em;
    margin-top: 1em;
}
.content h1, .content h2, .content h3, .content h4, .content h5, .content h6 {
    color: #666666;
    font-weight: 600;
    line-height: 1.125;
}
.content h1 {
    font-size: 2em;
    margin-bottom: .5em;
}
.content h2 {
    font-size: 1.75em;
    margin-bottom: .5714em;
}
.content h3 {
    font-size: 1.65em;
    margin-bottom: .5814em;
}
.content h4 {
    font-size: 1.55em;
    margin-bottom: .5914em;
}
.content h5 {
    font-size: 1.50em;
    margin-bottom: .5914em;
}
.content h6 {
    font-size: 1.41em;
    margin-bottom: .5994em;
}
.content h2:not(:first-child) {
    margin-top: 1.1428em;
}

.content h1.is-size-1 {
  font-size: 4.5rem;
}
.content h1.is-size-1 span {
  color: #fb898f;
  padding: 0 0.25em;
  border-bottom: 0.1em #fb898f dashed;
}

.content amp-img {
  text-align: center;
}

.breadcrumb {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.breadcrumb ol, .breadcrumb ul {
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start;
}
.breadcrumb li {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}
.breadcrumb li:first-child a {
    padding-left: 0;
}
.breadcrumb a {
    padding-right: .4em;
}
.breadcrumb a, .breadcrumb a:hover {
    color: #ed6f75;
}
.breadcrumb li+li:before {
    color: #b5b5b5;
    content: "\0002f";
    padding-right: .4em;
}
.breadcrumb li.unavailable:before {
    padding-right: .25em;
}

.footer, footer {
    color: #dcdcdc;
    background-color: #bf3e44;
    padding: 1.5em .5em 2.2em;
    font-size: 0.75em;
}

.has-text-centered {
    text-align: center;
}

hr {
    background-color: #f4d3d5;
}

hr {
    border: none;
    display: block;
    height: 2px;
    margin: 1.5rem 0;
}
code, pre {
    -moz-osx-font-smoothing: auto;
    -webkit-font-smoothing: auto;
    font-family: monospace;
}
pre {
    -webkit-overflow-scrolling: touch;
    background-color: #f5f5f5;
    color: #4a4a4a;
    font-size: .875em;
    overflow-x: auto;
    padding: 1.25rem 1.5rem;
    white-space: pre;
    word-wrap: normal;
}
.content pre {
    -webkit-overflow-scrolling: touch;
    overflow-x: auto;
    padding: 1.25em 1.5em;
    white-space: pre;
    word-wrap: normal;
}
code {
    color: #ff3860;
    font-size: .875em;
    font-weight: 400;
    padding: .25em .5em;
}

@media screen and (max-width: 1024px){
  section, .section {
    padding: 2.5rem 1.5rem;
  }
  .content h1.is-size-1 {
    font-size: 2.2rem;
    border-size: 0.09em;
  }
}

@media screen and (max-width: 425px){
  section, .section {
    padding: 0.75rem 0.5rem;
  }
  .content h1.is-size-1 {
    font-size: 2.5rem;
  }
  .content h1.is-size-1 span {
    font-size: 2.2rem;
    border-size: 0.08em;
  }
}
    </style>
    <script type="application/ld+json">
      {
        "@context": "http://schema.org",
        "@type": "NewsArticle",
        "mainEntityOfPage": {
          "@type": "WebPage",
          "@id": "<?=base_url()?>"
        },
        "headline": "Seme Indonesia Address Provider Documentation API",
        "datePublished": "2015-02-05T08:00:00+08:00",
        "dateModified": "2015-02-05T09:20:00+08:00",
        "author": {
          "@type": "Person",
          "name": "Daeng Rosanda"
        },
        "description": "API Documentation for using Indonesian address provider"
      }
    </script>
</head>
