/**
 * EA Dashboard
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
});
