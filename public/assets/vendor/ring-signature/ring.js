var keys;
var nkeys
var L = 1024;
var P;
var rng;
var TWO = new BigInteger("2");

function init(_keys) {
    keys = _keys
    nkeys = keys.length;

    rng = new SecureRandom();
}

// return a random bigint 0 <= n < 2^n
// XXX: super hokey and not crypto-graphically secure
function rand_bigint(n) {
    r = new BigInteger();

    // add on 1 bit at a time
    for (var i = 0; i < n; i++) {
        var s = new Array(1);
        rng.nextBytes(s);
        r = r.multiply(TWO).add(s[0] > 127 ? BigInteger.ONE : BigInteger.ZERO);
    }

    return r;
}

// z = index of our private key in the keys array
function sign(message, z) {
    if (z >= nkeys) {
        console.log("Can't sign with key " + z + ", only have " + nkeys + " keys");
        return;
    }

    if (!keys[z].d) {
        console.log("Key " + z + " must be a private key");
        return;
    }

    permut(message);

    s = new Array(nkeys);
    u = rand_bigint(L-1);
    c = v = E(u.toString());
    var done = 0;
    for (var i = (z+1)%nkeys; i != z; i=(i+1)%nkeys) {
        s[i] = rand_bigint(L-1);
        e = g(s[i], new BigInteger(keys[i].e.toString()), keys[i].n);
        v = E(v.xor(e).toString());
        if (i+1 == nkeys) {
            c = v;
        }
    }
    s[z] = g(v.xor(u), new BigInteger(keys[z].d.toString()), keys[z].n);

    s.unshift(c);

    for (var i = 0; i < s.length; i++) {
        s[i] = s[i].toString();
    }

    k = [];
    for (var i = 0; i < nkeys; i++) {
        k.push({e: keys[i].e.toString(), n: keys[i].n.toString()});
    }

    output = {};
    output["sig"] = s;
    output["keys"] = k;

    return output;
}

// X = the signature thing?
function verify(message, sig) {
    permut(message);

    X = sig["sig"];
    k = sig["keys"];

    if (X.length != k.length+1) {
        console.log("Inconsistent signature");
        return false;
    }

    y = [];
    for (var i = 0; i < X.length-1; i++) {
        y.push(g(new BigInteger(X[i+1].toString()), new BigInteger(k[i].e.toString()), new BigInteger(k[i].n.toString())));
    }
    r = new BigInteger(X[0].toString());
    for (var i = 0; i < k.length; i++) {
        r = E(r.xor(y[i]));
    }
    return r.toString() == X[0].toString();
}

function stringify_keys(k) {
    var strkeys = [];
    for (var i = 0; i < k.length; i++) {
        strkeys.push("e:" + k[i].e.toString() + "n:" + k[i].n.toString());
    }

    strkeys.sort();
    return strkeys.join(',');
}

function keys_match(sig, k) {
    return stringify_keys(sig["keys"]) == stringify_keys(k);
}

// arg is string
// THIS FUNCTION IS CORRECT
function permut(message) {
    P = new BigInteger(Sha1.hash(message), 16);
}

// arg is string, return is bigint
function E(x) {
    return new BigInteger(Sha1.hash(x + P.toString()), 16);
}

// all args are bigint
// THIS FUNCTION IS CORRECT
function g(x, e, n) {
    divmod = x.divideAndRemainder(n);
    q = divmod[0];
    r = divmod[1];

    if (q.add(BigInteger.ONE).multiply(n).compareTo(TWO.pow(L).subtract(BigInteger.ONE)) <= 0) {
        return q.multiply(n).add(r.modPow(e,n));
    } else {
        return x;
    }
}
