/**
 * Vote page
 * @author  Yifan Wu
 */
$(document).ready(function() {
    // If in the vote page, load the bitcoin address
     getBitcoinAddress();
     getCandidates();

    $(".btn-start-vote").on('click', function() {
        var voteid = $(".vote-candidate").val();

        if(voteid){
            swal({
                    title: "Are you sure?",
                    text: "Vote for " + voteid + ", is that right?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, vote now!",
                    closeOnConfirm: false
                },
                function(){
                    swal({
                        title: "Processing",
                        text: '<div class="row"><div class="col-md-12"><textarea class="form-control cmd-box" placeholder="" rows="20"></textarea></div></div>',
                        html: true,
                        customClass: 'swal-wide',
                        showConfirmButton: false
                    });
                    startVote();
                });
        }else{
            sweetAlert("Oops...", "Please choose your candidate! ", "error");
        }


    });
});

function getBitcoinAddress(){
    $.getJSON("/api/bitcoinaddress",{"item_id": $(".item_id").val()},function(result){
        if(result.success == 1){
            var arr = [];
            var str = '';
            $.each(result.content,function (i, value) {
                arr[i] = '<option value="' + value.id + ' ">' + value.bitcoin_address + '</option>';
            });
            // shuffle it
            str = arr.sort(randomsort).join("");
            $(".bitcoin-address-box").html(str);
            $(".bitcoin-address-numbers").html(result.param);
        }
    });
}




