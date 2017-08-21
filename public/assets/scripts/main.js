/**
 * main.js
 * @author  Yifan Wu
 */
$(document).ready(function() {


    $(".btn-email").on('click', function() {
        swal('Waiting','Please wait, sending email now','info');
        $.getJSON("/api/addvoter",{"email":$(".voter-email").val(),"vote_id":$(".vote-id").val()},function(result){
            if(result.success == 1){
                swal("Good job!", "Send an email to the voter successfully!", "success");
                $(".voter-email").val("");
            }else{
                sweetAlert("Oops...", result.content, "error");
            }
        });

    });

    $(".btn-keys").on('click', function(){
        $.getJSON("/api/keyslist",{"id":$(this).attr("data-id")},function(result){
            if(result){
                var str = "";
                $.each(result, function(i, item){
                    str = str + "<tr><td>" + item.id + "</td><td data-pub='" + item.public_key + "'> View </td><td data-priv='" + item.private_key + "'> View</td><td>" + item.bitcoin_address + "</td><td>" + item.is_used + "</td></tr>";
                });
                console.log(str);
                $(".table-keys").html(str);

            }
        });
    });


    $(".btn-toggle").on('click', function(){
        $.getJSON("/api/togglevote",{"vote_id":$(this).attr("data-id")},function(result){
            if(result){
                swal({
                        title: "Good Job",
                        text: prefixToggle(result.content),
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false
                    },
                    function(){
                        window.location.reload();
                    });
            }
        });
    });

    loadmenu();

});

function prefixToggle(str){
    if(str===1)
        return "The vote has been started";
    if(str===0)
        return "The vote has been stopped";
}
function loadmenu() {
    var localad = location.href.lastIndexOf("/");
    if (localad > 0) {
        addnow = location.href.substring(localad + 1, location.href.length);
        var now = $(".menu-" + addnow);
        now.children("a").addClass("active");
        if (now.hasClass("sub-menu")){
            now.parent().parent().addClass("in").prev().addClass("active").removeClass("collapsed").addClass("collapse");
        }
        // set title
        $(document).attr("title",now.children("a").children("span").text() + ' | BlockVotes');

    }


}