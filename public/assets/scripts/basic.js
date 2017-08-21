/**
 * Basic.js
 * @author  Yifan Wu
 */

function createAndDownloadFile(fileName, content) {
    var aTag = document.createElement('a');
    var blob = new Blob([content]);
    aTag.download = fileName;
    aTag.href = URL.createObjectURL(blob);
    aTag.click();
    URL.revokeObjectURL(blob);
}

function randomsort(a, b) {
    return Math.random()>.5 ? -1 : 1;
}

function padding(num,length){
    var numstr = num.toString();
    var l=numstr.length;
    if (numstr.length>=length) {return numstr;}

    for(var  i = 0 ;i<length - l;i++){
        numstr = "0" + numstr;
    }
    return numstr;
}

function hex2bin (s) {
    var ret = [];
    var i = 0;
    var l;
    s += '';
    for (l = s.length; i < l; i += 2) {
        var c = parseInt(s.substr(i, 1), 16);
        var k = parseInt(s.substr(i + 1, 1), 16);
        if (isNaN(c) || isNaN(k)) return false;
        ret.push((c << 4) | k);
    }
    return String.fromCharCode.apply(String, ret);
}