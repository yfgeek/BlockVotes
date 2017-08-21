/**
 * RA: show candidate
 * @author  Yifan Wu
 */
$(document).ready(function() {
    var id = $(".input-id").val();
    loadCandidate(id);
    $(".input-id").change(function () {
        loadCandidate($(this).val());
    });
    $(".refresh-btn").click(function () {
        loadCandidate($(".input-id").val());
    });

    $(".btn-edit-candidate").click(function(){
        var candidateid = $(".candidate-id").val();
        var data = { id: candidateid, name: $(".input-name").val(), des: $(".input-des").val()};
        $.getJSON("/api/updatecandidate",data,function(result){
            if(result.success==='1'){
                $(".modal-edit-candidate").modal("hide");
                swal('Good Job!',result.content,'success');
                loadCandidate($(".input-id").val());
            }else{
                swal('Opps','Please refresh this page and try again!','error');
                loadCandidate($(".input-id").val());

            }
        });
    });
});
function loadCandidate(id){
    var data = { vote_id: id};
    $.getJSON("/api/getcandidates",data,function(result){
        if(result.success ==='1'){
            var str ='';
            $(result.content).each(function (i,item) {
                var editbutton = '<button type="button" class="btn btn-toggle btn-info" data-toggle="modal" data-target=".modal-edit-candidate"  onclick="editcandidate(' +  item.id +')">Edit</button>';
                var delbutton =  '<button type="button" class="btn btn-toggle btn-info" onclick="delcandidate(' +  item.id +')">Delete</button>';
                str +='<div class="col-md-4"><div class="panel panel-headline"><div class="panel-heading"><h3 class="panel-title candidate-' + item.id +'-name">'
                    + item.name +
                    '</h3><p class="panel-subtitle"> #'
                    + item.id +
                    '</p></div><div class="panel-body candidate-' + item.id +'-des"><p>'
                    + item.des +
                    '</p></div><div class="panel-footer"><h5>'
                    + editbutton + ' ' + delbutton +
                    '</h5></div></div></div>';
            });
            $(".box-candidate").html(str);
        }
    });
}

function editcandidate(id){
    $(".candidate-id").val(id);
    $(".input-name").val($(".candidate-"+id+"-name").html());
    $(".input-des").val($(".candidate-"+id+"-des").text());
}
function delcandidate(id) {
    swal({
            title: "Are you sure?",
            text: "This candidate will be deleted",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        },
        function(){
            var data = {id: id};
            $.getJSON("/api/delcandidate",data,function(result){
                if(result.success==='1'){
                    swal("Deleted!", "This candidate has been deleted.", "success");
                    loadCandidate($(".input-id").val());
                }
            });
        });
}