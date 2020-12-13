/* function fill(Value) {
   $('#search').val(Value);
   $('#display').hide();
} */
$(document).ready(function() {
   /* $("#search").keyup(function() {
       var name = $('#search').val();
       if (name == "") {
           $("#display").html("");
       }
       else {
           $.ajax({
               type: "POST",
               url: "livesearch.php",
               data: {
                   search: name
               },
               success: function(html) {
                   $("#display").html(html).show();
               }
           });
       }
   }); */

    $('#menubar').click(function() {
        var top = $('.box').position();
        if(top.top == 0) {
            $('.box').animate({top: '-80px'}, 500);
        } else {
            $('.box').animate({top: '0px'}, 500);
        }
        
    });
   
});