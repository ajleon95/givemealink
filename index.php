<?php

  //Include the framework
  $f3 = require('lib/base.php');
  
  //Set up required (framework) variables
  $f3->set('DEBUG', 0);
  $f3->set('UI', 'ui/');
  $f3->set('AUTOLOAD', 'classes/');
  
  //Set up view variables
  $f3->set('msg', false);
  $f3->set('aboutlink', true);
  $f3->set('contactlink', true);
  $f3->set('homelink', false);
  
  //Homepage
  $f3->route('GET /', function($f3) {
    
    $f3->set('content', 'index.htm');
    echo Template::instance()->render('layout.htm');
    
  });
  
  //Displays a message when the user reports a link. Basically home page with a banner. 
  $f3->route('GET /msg', function($f3) {
    
    $f3->set('msg', true);
    $f3->set('content', 'index.htm');
    echo Template::instance()->render('layout.htm');
    
  });
  
  //Returns the JSON of a link item, to be used by an AJAX request.
  $f3->route('GET /link [ajax]', function() {
    
    $link = new link();
    $link->returnJSON();
    
  });
  
  //If Javascript doesn't work/is not enabled, the browser will navigate to /link.
  //Basically the same as above but returns a full-fleged page.
  $f3->route('GET /link [sync]', function($f3) {
    
    $link = new link();
    $f3->mset($link->getValue());
    $f3->set('processedURL', $link->processedURL());
    $f3->set('content', 'specific.htm');
    echo Template::instance()->render('layout.htm');
    
  });
  
  //Returns a specific link's JSON.
  $f3->route('GET /link/@id [ajax]', function($f3) {
    
    $link = new link($f3->get('PARAMS.id'));
    $link->returnJSON();
    
  });
  
  //Returns a speficic link's full page.
  $f3->route('GET /link/@id [sync]', function($f3) {
    
    $link = new link($f3->get('PARAMS.id'));
    $f3->mset($link->getValue());
    $f3->set('processedURL', $link->processedURL());
    $f3->set('content', 'specific.htm');
    echo Template::instance()->render('layout.htm');
    
  });
  
  //Reports a URL as NSFW.
  $f3->route('GET /link/nsfw/@id', function($f3) {
    
    $link = new link($f3->get('PARAMS.id'));
    $link->addNSFW();
    $f3->reroute('/msg');
    
  });
  
  //Reports a URL as nonfunctional.
  $f3->route('GET /link/functional/@id', function($f3) {
    
    $link = new link($f3->get('PARAMS.id'));
    $link->addFunctional();
    $f3->reroute('/msg');
        
  } );
  
  //Returns the JSON of a particular link, useful for tesitng. 
  $f3->route('GET /link/json/@id', function($f3) {
    
    $link = new link($f3->get('PARAMS.id'));
    $link->returnJSON();
    
  });
  
  //Displays the aboute page. 
  $f3->route('GET /about', function($f3) {
    
    $f3->set('content', 'about.htm');
    $f3->mset(array(
        'homelink' => true,
        'aboutlink' => false
    ));
    echo Template::instance()->render('layout.htm');
    
  });
  
  //Displays the contact page.
  $f3->route('GET /contact', function($f3) {
    
    $f3->set('content', 'contact.htm');
    $f3->mset(array(
        'homelink' => true,
        'contactlink' => false
    ));
    echo Template::instance()->render('layout.htm');
    
  });
  
  //Minfies resources (JS and CSS). Minifed resource cached for one day (3600*24 secs)
  $f3->route('GET /minify/@type', function($f3, $args) {
          $f3->set('UI',$f3->get('UI').$args['type'].'/'); 
          echo Web::instance()->minify($_GET['files']);
      },
      3600*24 
  );
  
  
  $f3->run();
  
?>