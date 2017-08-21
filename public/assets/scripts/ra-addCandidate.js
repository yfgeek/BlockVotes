/**
 * RA: add candidate
 * @author  Yifan Wu
 */
$(document).ready(function() {
    $(".btn-add-candidate").on('click', function() {
        var id = $(".vote-id").val();
        var data = { vote_id: id, name: $(".input-name").val(), des: $(".input-des").val()};
        $.getJSON("/api/addcandidate",data,function(result){
            if(result.success==='1'){
                swal('Good Job!',result.content,'success');
            }else{
                swal('Opps','Please refresh this page and try again!','error');
            }
        });

    });
});