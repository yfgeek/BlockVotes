/**
 * Vote Fill page
 * @author  Yifan Wu
 */
$(document).ready(function() {
    $(".btn-updatekey").on('click', function() {
        var public_key = $.base64('encode', $(".public-key-area").val());
        $.getJSON("/api/postpubkey",{"public_key": public_key,"code":$(".code").val()},function(result){
            if(result.success === '1'){
                swal({
                        title: "Good Job",
                        text: result.content,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false
                    },
                    function(){
                        window.location.href="start?code=" + $(".code").val();
                    });
                $(".voter-email").val("");
            }else{
                sweetAlert("Oops...", result.content, "error");
            }
        });
    });

    $(".btn-generate").on('click', function() {
        generateKeys();
    });
});


function generateKeys() {
    var keySize = 1024;
    var crypt = new JSEncrypt({default_key_size: keySize});
    crypt.getKey();
    $('.private-key-area').val(crypt.getPrivateKey());
    $('.public-key-area').val(crypt.getPublicKey());
    createAndDownloadFile($(".code").val() +'_private_key.txt',crypt.getPrivateKey());
}

