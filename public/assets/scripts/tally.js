/**
 * Tally page
 * @author  Yifan Wu
 */
$(document).ready(function() {
    refreshVoting();
    // candidatevotes = [];
    getCandidates();
    createChart();
    createBarChart();

    $('body').delegate('.panel-candidate', 'click', function () {
        $(".panel-candidate").removeClass('selected');
        $(this).addClass('selected');
        $(".vote-candidate").val($(this).attr("data-id"));
    });

    data.labels = [];
    data.series = [];
    data.id = [];
    keys = [];
    verifyPubKeys();

});

function refreshVoting() {
    $.getJSON("https://testnet-api.smartbit.com.au/v1/blockchain/addr/" + $(".eaaddress").val() + "?limit=1000", function (result) {
        if (result.success === true) {
            var item =  result.address.transactions;
            for(var i=0; i<item.length;i++){
                (function(i){
                    setTimeout(function(){
                        if(i == item.length - 1 ){
                            $(".tally-status").html('Done, the result is as follows');
                        }else{
                            $(".tally-status").html('Tallying and verifying No.' + i + ' transaction.');
                        }
                        updateCandidate(item[i].outputs[0].script_pub_key.hex);
                    },i*200);
                })(i);
            }
        }
    });
}

function updateCandidate(hex) {
            var item =  hex2bin(hex);
            // due to different API
            var candidateid = Number(item.substring(42,45));
            var voteid = Number(item.substring(45,48));
            var hashid = item.substring(2,42);
            console.log(hashid);
            $.getJSON("/api/sighash",{hash : hashid},function (result) {
                if(result.success === '1') {
                    var sig = JSON.parse(result.content.sig_msg);
                    if((keys_match(sig, keys)) &&  verify(candidateid+"", sig)){
                        if(candidateid && voteid === Number($(".item_id").val())){
                            for(var i = 0; i<data.id.length;i++){
                                if(data.id[i] === candidateid){
                                    data.series[i] += 1;
                                    console.log("candidate: " + candidateid + ' id:' + voteid);
                                    $(".candidate-vote-"+candidateid).html(data.series[i]);
                                }

                            }
                        }
                    }
                    char.update(data);
                    barchart.update(data);
                }

            });


}

function verifyPubKeys() {
    $.getJSON("/api/publickey",{"item_id": $(".item_id").val()},function(result){
        if(result.success === '1'){
            keys = [];
            $.each( result.content, function( key, value ) {
                keys.push(new JSEncryptRSAKey(value.public_key));
            });
        }else{
            setTimeout(verifyPubKeys(), 3000);
        }
    });

}

function createChart() {
    char = new Chartist.Bar('.ct-chart', data, {
        distributeSeries: true
    });
}

function createBarChart() {

    var options = {
        labelInterpolationFnc: function(value) {
            return value[0]
        }
    };

    var responsiveOptions = [
        ['screen and (min-width: 640px)', {
            chartPadding: 30,
            labelOffset: 100,
            labelDirection: 'explode',
            labelInterpolationFnc: function(value) {
                return value;
            }
        }],
        ['screen and (min-width: 1024px)', {
            labelOffset: 100,
            chartPadding: 20,
            labelInterpolationFnc: function(value) {
                return  value;
            }
        }]
    ];

   barchart = new Chartist.Pie('.ct-bar-chart', data, options, responsiveOptions);
}

function getCandidates() {
    data = {vote_id: $(".item_id").val()};
    $.getJSON("/api/getcandidates",data,function(result){
        if(result.success ==='1'){
            var str ='';
            $(result.content).each(function (i,item) {
                var votebutton = '<button type="button" class="btn btn-toggle btn-info" onclick="editcandidate(' +  item.id +')">Edit</button>';
                str +='<div class="col-md-3"><div class="panel panel-headline panel-candidate panel-candidate-' + item.id +
                    '" data-id="'+ item.id + '"><div class="panel-heading"><h3 class="panel-title">'
                    + item.name +
                    '</h3><p class="panel-subtitle"> #'
                    + item.id +
                    '</p></div><div class="panel-body"><p class="candidate-vote candidate-vote-' +  item.id +'">'
                     +
                    '</p></div></div></div>';
                data.labels.push(item.name);
                data.id.push(item.id);
                data.series.push(0);
            });
            $(".list-candidate").html(str);
            $(".candidate-vote").html("0");

        }
    });
}


/**
 * chain.so API is shit
 **/
// $.getJSON("https://chain.so/api/v2/get_tx_received/BTCTEST/n4Kc1AwFos3aZRvD3Tc9imzeMeA8E9DEUr",function (result) {
//     if(result.status === "success"){
//         var item = result.data.txs;
//         for(var i=0;i<item.length;i++){
//             (function () {
//                 var j = i;
//                 setTimeout( function timer() {
//                     fetchCandidate(item[j].txid);
//                 },i*6000 );
//             })();
//         }
//     }
//
// });
// }

// function fetchCandidate(txid) {
//     // console.log(txid);
//     $.get("https://chain.so/api/v2/tx/BTCTEST/"+txid,function(result){
//         if( result.data.outputs[0].address ==="nulldata"){
//             var item =  hex2bin(result.data.outputs[0].script_asm.substr(10));
//             var hashid = item.substr(0,40);
//             var candidateid = item.substr(40);
//             console.log(candidateid);
//             if(Number(candidateid)){
//                 candidatevotes[candidateid] +=1;
//                 data.series[candidateid] +=1;
//             }
//             $(".candidate-vote-"+candidateid).html(candidatevotes[candidateid]);
//             char.update(data);
//
//         }
//     });
// }
