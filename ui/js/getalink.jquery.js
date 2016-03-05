$(function(){
  $(".newlink").on("click", "a", function(event) {
    event.preventDefault();
    $.getJSON("/link", function(json){
      
      var $message1 = $('<span>Here\'s your link! <a href="/link">Want another?</a></span>');
      var $message2 = $('<p><a href="/link/nsfw/' + json.id + '">This link is not safe for work!</a></p><p><a href="/link/functional/' + json.id + '">This link doesn\'t work!</a></p>');
      
      
      console.log("success");
      
      var $newA = $("<a></a>");
      $newA.attr("href", json.url);
      $newA.attr("target", "_blank");
      $newA.append(json.processed);
      $("p.mainlink").html($newA);
      
      $("#controls p.newlink").html($message1);
      $("#controls div.control-links").html($message2);
      
    });
    
  });
  
  $("a#close").click(function() {
  
    $("div.msg").slideUp();
    
  });
});