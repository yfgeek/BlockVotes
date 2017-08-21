/**
 * EA Balance
 * @author  Yifan Wu
 */
$(document).ready(function() {
    var id = $(".input-id").val();

    loadBlockchain(id);
    $(".input-id").change(function () {
        loadBlockchain($(this).val());
    });
    $(".btn-load-balance").click(function () {
        for(var i=0;i<txid.length;i++){
            (function(i){
                setTimeout(function(){
                    loadBalance(txid[i],txaddress[i]);
                },i*1800);
            })(i);
        }
    });


});
function loadBlockchain(id){
    var data = { item_id: id};
    txid =[];
    txaddress = [];
    $.getJSON("/api/allbitcoinaddress",data,function(result){
        if(result.success ==='1'){
            var str ='';
            $(result.content).each(function (i,item) {
                var loadBalance = '<button type="button" class="btn btn-load-balance btn-info btn-balance-' + item.id +  '" data-toggle="modal"  onclick="loadBalance('
                    + item.id + ',\'' +  item.bitcoin_address +'\')">Load</button>';
                str +='<tr><td>' + item.id + '</td>' +
                    '<td>' + item.bitcoin_address + '</td><td>' + loadBalance + '</td></tr>';
                txid.push(item.id);
                txaddress.push(item.bitcoin_address);
            });
            $(".table-balance").html(str);
        }
    });
}

function loadBalance(id,address){
    $.getJSON("https://chain.so/api/v2/get_address_balance/BTCTEST/"+address,function(result){
        if(result.status === "success"){
            console.log(result.data.confirmed_balance.slice(0,6));
            $(".btn-balance-" + id).text(result.data.confirmed_balance.slice(0,6));
        }
    });
}