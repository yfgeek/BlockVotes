/**
 * Vote Now page
 * @author  Yifan Wu
 */
function startVote() {
    step = 0;
    keys = [];
    value = 0;
    candidate = $(".vote-candidate").val();
    download();
    bitcoinPriv='';
    bitcoinAddress='';
    hashSig ='Error';
    item_id = $(".item_id").val();
}

/**
 *
 */
function download() {
    step = 1;
    showCMD(step,"Downloading all public keys of other voters");
    $.getJSON("/api/publickey",{"item_id": $(".item_id").val(),"code":$(".code").val()},function(result){
        if(result.success === '1'){
            $.each( result.content, function( key, value ) {
                keys.push(new JSEncryptRSAKey(value.public_key));
            });
            showCMD(step,'Got and fetch ' + (keys.length + 1) + ' public keys, including yours');
            fetchPrivateKey();
        }else{
            showCMD(step,"Please check your network, 3 second");
            setTimeout(download(), 3000);
        }
    });
}

/**
 *
 * @param vStr
 * @returns {string|XML|Chartist.Svg|void|*}
 */
function  trim(vStr)   {
    return   vStr.replace(/(^\s+)|(\s+$)/g, "");
}

/**
 *
 */
function fetchPrivateKey(){
    step = 2;
    showCMD(step,"Fetching your private key from the form, the private key will not upload to our system");
    privkey = new JSEncryptRSAKey($(".private-key-area").val());
    validatePrivateKey();
}

/**
 *
 */
function validatePrivateKey(){
    step = 3;
    showCMD(step,"Validating your private key with your public key");
    pubkey = $(".public-key-area").val();
    if(trim(pubkey)!=trim(privkey.getPublicKey())){
        showCMD(step,"Error: it's not a pair of keys, please check your private key");
    }else{
        showCMD(step,"Validated");
        fetchCandidate();
    }
    console.log(privkey.getPublicKey());
        console.log($(".public-key-area").val());
    console.log($(".public-key-area").val()==privkey.getPublicKey());

}

/**
 *
 */
function fetchCandidate(){
    step = 3;
    showCMD(step,"Fetching your candidate from the voting list");
    candidate = $(".vote-candidate").val();
    showCMD(step,"Fetched successfully, your vote number is: " + candidate);
    ringSig();
}

function postSig(sig) {
    var data ={sig:sig,csrf_name : $("input[name='csrf_name']").val(), csrf_value: $("input[name='csrf_value']").val()}
    $.post("/api/sigpairs",data,function (result) {
        showCMD(step,result.content);
    })
}

/**
 *
 */
function ringSig(){
    step = 4;
    showCMD(step,"Generating your unique ring signature");
    keys = keys.sort(randomsort);
    z = Math.floor(Math.random() * (keys.length+1));
    keys.splice(z, 0, privkey);
    init(keys);
    console.log(candidate);
    sig = sign(candidate, z);
    hashSig = Sha1.hash(JSON.stringify(sig));
    console.log(JSON.stringify(sig));
    console.log('hash:' + hashSig);
    showText(JSON.stringify(sig));
    showCMD(step,'Generated successfully, hash: ' + hashSig);
    createAndDownloadFile($(".code").val() +'sig.txt',JSON.stringify(sig));
    postSig(JSON.stringify(sig));
    fetchBitcoinPrivateKey();
}

/**
 *
 */
function fetchBitcoinPrivateKey(){
    step = 5;
    showCMD(step,"Fetching your bitcoin address information");
    $.getJSON("/api/bitcoinkey",{"bitcoin_address": $(".bitcoin-address-box").val(),"code":$(".code").val()},function(result){
        if(result.success === '1'){
            wif =result.content.bitcoin_private_key;
            network = Bitcoin.networks.testnet;
            keyPair = Bitcoin.ECPair.fromWIF(wif,network);
            bitcoinAddress = keyPair.getAddress();
            $.getJSON("https://chain.so/api/v2/get_tx_unspent/BTCTEST/"+bitcoinAddress,function(result){
                var txb = new Bitcoin.TransactionBuilder(network);

                var last = result.data.txs.length - 1;
                unspent_txid = result.data.txs[last].txid;
                unspent_vout = result.data.txs[last].output_no;
                txb.addInput(unspent_txid, unspent_vout);
                value = Number(result.data.txs[last].value * 100000000);
                address = $(".eaaddress").val();
                // address = 'n4Kc1AwFos3aZRvD3Tc9imzeMeA8E9DEUr';
                pay = 0.01 * 100000000;
                fee = 0.01 * 100000000;
                change = parseInt(value - pay - fee);

                console.log(value);

                var commit = new Buffered(hashSig +padding(candidate,3) + padding(item_id,3));
                var dataScript = Bitcoin.script.nullData.output.encode(commit);

                txb.addOutput(dataScript, 0);
                txb.addOutput(address,change);

                txb.sign(0, keyPair);

                var txRaw = txb.build();
                var txHex = txRaw.toHex();

                console.log('hex',txHex);
                postdata = { tx_hex : txHex };
                $.post("https://chain.so/api/v2/send_tx/BTCTEST/",postdata,function(result){
                    showCMD(step,result.status);
                    showCMD(step,result.data.txid);
                    window.open("https://www.blocktrail.com/tBTC/tx/"+result.data.txid);
                });
            });


        }
    });
    showCMD(step,"Your code has been revoked now, DO NOT CLOSE THIS PAGE OR YOU WILL LOST THE LAST CHANCE TO VOTE");
}

/**
 *
 * @param step
 * @param txt
 */
function showCMD(step,txt) {
    // swal({
    //     title: 'Step ' + step,
    //     text:  txt + '\n',
    //     html: true,
    //     customClass: 'swal-wide',
    //     showConfirmButton: false
    // });
    // var content = '<div class="finish-box"><img class="icon-finish" src="/assets/img/finish.png" height="32" width="32"/><span class="text-step">Step ' + step + '</span><span class="text-content"> '
        + txt + '</span></div>';
    // $(".status").append(content);
    $(".cmd-box").val($(".cmd-box").val()+'\n'+step+' : '+txt )

}

function showText(txt){
    $(".cmd-box").val($(".cmd-box").val()+'\n'+step+' : '+txt )
}

