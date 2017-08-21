/**
 * Verify page
 * @author  Yifan Wu
 */
$(document).ready(function() {
    getCandidates();
    $('body').delegate('.panel-vote-item', 'click', function(){
        $(".overlay").removeClass('clicked');
        $(this).find(".overlay").addClass('clicked');
        $(this).find(".vote-radio-item").attr("checked",true);
        $(".vote-item").val($(this).attr("data-id"));
    });

    $(".btn-blockchain-id").click(function () {
       var btcid =  $(".input-blockchain-id").val();
        $.getJSON("https://chain.so/api/v2/tx/BTCTEST/"+btcid,function(result){
            var item =  hex2bin(result.data.outputs[0].script_asm.substr(10));
            console.log(item);
            var hashid = item.substring(0,40);
            var candidateid = Number(item.substring(40,43));
            $(".hash-value-area").val(hashid);
            $(".vote-candidate").val(candidateid);
            clickCandidate(candidateid);
            console.log(candidateid);
            $.getJSON("/api/sighash",{hash : hashid},function (result) {
                if(result.success === '1') {
                    $(".signature-area").val(result.content.sig_msg);
                }else{
                    sweetAlert("Opps..","The system does not save your signature, please paste it by yourself","error")
                }
                });
        });
    });

    $(".signature-area").change(function () {
        var signature = $(".signature-area").val();
        var hashSig = Sha1.hash(signature);
        $(".hash-value-area").val(hashSig);
    });

    $('.signature-verify').click(function() {
        msg = $(".vote-candidate").val();
        console.log(msg);
        try{
            sig = JSON.parse($(".signature-area").val());
        } catch(e) {
            sweetAlert("Oops...", "Please paste the signature file content, it is a json file ", "error");
        }
        ver = verify(msg, sig);
        console.log(msg);
        if (ver) {
            swal({
                    title: "Good Job!",
                    text: "The signature matched, do you still want to verify the public key?",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonText: "Okay, verify it now",
                    closeOnConfirm: false
                },
                function(){
                    verifyPubKeys();
                });
        }else{
            sweetAlert("Oops...", "The signature does not match, please check your vote ", "error");
        }
    });

});

var clickCandidate = function (candidateid) {
    $(".panel-candidate").removeClass("selected");
    $(".panel-candidate-"+candidateid).addClass("selected");

};

function verifyPubKeys() {
    $.getJSON("/api/publickey",{"item_id": $(".item_id").val()},function(result){
        if(result.success === '1'){
            keys = [];
            $.each( result.content, function( key, value ) {
                keys.push(new JSEncryptRSAKey(value.public_key));
            });
            if (keys_match(sig, keys)) {
                sweetAlert("Perfect", "The public keys matched and the signature verified! Well done!", "success");
            } else {
                sweetAlert("Oops...", "The public keys does not match with the servers public keys, but the signature matched. This may due to wrong pasting.", "error");
            }
        }else{
            swal("Please check your network, 3 seconds retry");
            setTimeout(download(), 3000);
        }
    });

}