/**
 * Candidate.js
 * @author  Yifan Wu
 */
$(document).ready(function() {
    $('body').delegate('.panel-candidate', 'click', function () {
        $(".panel-candidate").removeClass('selected');
        $(this).addClass('selected');
        $(".vote-candidate").val($(this).attr("data-id"));
    });
});

function getCandidates() {
    data = {vote_id: $(".item_id").val()};
    $.getJSON("/api/getcandidates",data,function(result){
        if(result.success ==='1'){
            var str ='';
            $(result.content).each(function (i,item) {
                var votebutton = '<button type="button" class="btn btn-toggle btn-info" onclick="editcandidate(' +  item.id +')">Edit</button>';
                str +='<div class="col-md-4"><div class="panel panel-headline panel-candidate panel-candidate-' + item.id +
                    '" data-id="'+ item.id + '"><div class="panel-heading"><h3 class="panel-title">'
                    + item.name +
                    '</h3><p class="panel-subtitle"> #'
                    + item.id +
                    '</p></div><div class="panel-body"><p>'
                    + item.des +
                    '</p></div></div></div>';

                // str+= '<label class="fancy-radio"><input name="candidate" value="'
                //     + item.id + '" type="radio" /><i></i><span>' + item.name+ '</span> <span class="candidate-des">'
                //     + item.des + '</span></label>';
            });
            $(".list-candidate").html(str);
        }
    });
}
