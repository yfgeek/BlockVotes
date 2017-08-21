/**
 * EA Fee
 * @author  Yifan Wu
 */
$(document).ready(function() {
    $.getJSON("https://chain.so/api/v2/get_address_balance/BTCTEST/"+$(".bitcoin-address").val(),function(result){
        if(result.status === "success"){
            $(".number-btc").html(result.data.confirmed_balance.slice(0,6));
            $(".number-network").html(result.data.network);

        }else{
            $(".number-btc").html("Unconfigured");
            $(".number-network").html("Unconfigured");

        }
    });
    $.getJSON("https://chain.so/api/v2/get_tx_unspent/BTCTEST/"+$(".bitcoin-address").val(),function(result) {
        if(result.status === "success"){
            $(".number-unspent").html(result.data.txs.length);

        }else{
            $(".number-unspent").html("Unconfigured");
        }
    });

    txaddress = [];
    txid =[];
    pubkeyhash = [];
    wif = $('.bitcoin-wif').val();

    var currentid = $(".input-id").val();
    loadBlockchain(currentid);
    getPubKeysHash(currentid);


    $(".input-id").change(function () {
        loadBlockchain($(this).val());
        getPubKeysHash($(this).val());
    });
    $(".btn-pay-now").click(function () {
        payBitcoin();
    })
});


function loadBlockchain(id){
    var data = { item_id: id};
    $.getJSON("/api/allbitcoinaddress",data,function(result){
        if(result.success ==='1'){
            var str ='';
            txaddress = [];
            txid =[];
            $(".number-addresses").html(result.content.length);
            $(result.content).each(function (i,item) {
                if(item.is_paid==='0'){
                    txaddress.push(item.bitcoin_address);
                    txid.push(item.id);
                }
                var paySingle = '<td><button type="button" class="btn btn-single-pay btn-info btn-pay-' + item.id +  '" data-toggle="modal"  onclick="singlepay('
                    + item.id + ',\'' +  item.bitcoin_address +'\')">Pay</button></td>';
                str +='<tr><td>' + item.id + '</td>' +
                    '<td>' + item.bitcoin_address + '</td>' +
                    '<td><span class="status-'+ item.id+'"></td>'+
                    paySingle + '</tr>' ;
            });
            $(".table-fee").html(str);
        }
    });
}


function getPubKeysHash(id){
    var data = {item_id:id};
    pubkeyhash = [];
    $.getJSON("http://localhost/api/publickey",data,function(result){
        if(result.success ==="1"){
            $(result.content).each(function (i,item) {
                pubkeyhash.push(Sha1.hash(item.public_key));
            });
        }
    });
}

function payBitcoin(){
    if(pubkeyhash.length > txaddress){
        swal("Oops..","The length of public key hash and address does not match","error");
    }else if(pubkeyhash.length < txaddress.length){
        console.log('Padding the hash...');
        for(var j=pubkeyhash.length-1; j<txaddress.length;j++){
            pubkeyhash[j] = '';
        }
        payBitcoin();
    }else{
        if(txaddress.length === txid.length){
            wif = $('.bitcoin-wif').val();
            network = Bitcoin.networks.testnet;
            keyPair = Bitcoin.ECPair.fromWIF(wif,network);
            bitcoinAddress = keyPair.getAddress();
            lock = false;
            for(var i =0; i<txaddress.length;i++){
                (function(i){
                    setTimeout(function(){
                        makeTransaction(txid[i],txaddress[i],pubkeyhash[i]);
                    },i*10000);
                })(i);
            }
        }else{
            swal("Oops..","The length of address id and address does not match","error");
        }
    }
}

function makeTransaction(itemid,address,pbhash){
    if(!lock){
        $.getJSON("https://chain.so/api/v2/get_tx_unspent/BTCTEST/"+bitcoinAddress,function(result){
            lock =true;
            var last = result.data.txs.length - 1;
            console.log('Current:' + last + ' ' + address);
            var unspent_txid = result.data.txs[last].txid;

            var unspent_vout = result.data.txs[last].output_no;
            txb = new Bitcoin.TransactionBuilder(network);

            txb.addInput(unspent_txid, unspent_vout);

            value = Number(result.data.txs[last].value * 100000000);

            pay = 0.0001 * 100000000;
            change = parseInt(value - pay);

            var commit = new Buffered(pbhash);

            var dataScript = Bitcoin.script.nullData.output.encode(commit);

            txb.addOutput(dataScript, 0);
            txb.addOutput(address,change);

            txb.sign(0, keyPair);

            var txRaw = txb.build();
            var txHex = txRaw.toHex();
            console.log('hex',txHex);
            postdata = { tx_hex : txHex };
            postTransaction(itemid,postdata);
        });
        return true;
    }

}

function postTransaction(itemid,postdata){
    $.post("https://chain.so/api/v2/send_tx/BTCTEST/",postdata,function(result){
        if(result.status ==="success"){
            $(".status-"+ itemid ).html(result.data.txid);
        }
        lock = false;
    });
}
function singlepay(itemid,address){
    swal({
            title: "OP_RETURN",
            text: "Write the OP_RETURN code, normally the hash of the public key",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: ""
        },
        function(inputValue){
            wif = $('.bitcoin-wif').val();
            network = Bitcoin.networks.testnet;
            keyPair = Bitcoin.ECPair.fromWIF(wif,network);
            bitcoinAddress = keyPair.getAddress();
            lock = false;
            if(makeTransaction(itemid,address,inputValue)){
                swal("Good job!","Success","success")
            }
        });
}