/**
 * EA Vote
 * @author  Yifan Wu
 */
$(document).ready(function() {
    var id = $(".input-id").val();
    loadCandidate(id);
});

function loadCandidate(id){
    var data = { vote_id: id};
    $.getJSON("/api/getcandidates",data,function(result){
        if(result.success ==='1'){
            var str ='';
            $(result.content).each(function (i,item) {
                str +='<div class="col-md-4"><div class="panel panel-headline"><div class="panel-heading"><h3 class="panel-title">'
                    + item.name +
                    '</h3><p class="panel-subtitle"> #'
                    + item.id +
                    '</p></div><div class="panel-body"><p>'
                    + item.des +
                    '</p></div></div></div></div>';
            });
            $(".box-candidate").html(str);
        }
    });
}


function loadProfile(id){
    $(".input-id").val(id);
    var data = { id: id};
    $.getJSON("/api/voteprofile",data,function(result){
        if(result.success ==='1'){
           $(".title").val(result.content.title);
           $(".number").val(result.content.number);
           $(".description").val(result.content.description);
        }
    });
}


function updateProfile(){
    var id = $(".input-id").val();
    var data = { id: id, title: $(".title").val(),number:$(".number").val(),description: $(".description").val(),csrf_name : $("input[name='csrf_name']").val(), csrf_value: $("input[name='csrf_value']").val()};
    $.post("/api/updateprofile",data,function(result){
        $(".modal-manage-profile").modal("hide");
        if (result.success === '1') {
            swal({
                    title: "Good Job",
                    text: result.content,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "Got it!",
                    closeOnConfirm: false
                },
                function(){
                    window.location.reload();
                });
        } else {
            swal({
                    title: "Opps",
                    text: result.content,
                    type: "error",
                    showCancelButton: false,
                    confirmButtonText: "Got it!",
                    closeOnConfirm: false
                },
                function(){
                    window.location.reload();
                });
        }
    });
}