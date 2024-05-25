message = document.getElementById("message");
key_n = document.getElementById("space");
key = document.getElementById("key");
result = document.getElementById("result");

function init(){
    message = document.getElementById("message");
    key_n = document.getElementById("space");
    key = document.getElementById("key");
    result = document.getElementById("result");
}

function encryptmessage(){
    let m = message.value;
    let space = key_n.value;
    let k = key.value;
    // console.log(m, k, space);
    
    if(!m || !k || !space){
        result.innerText = "invalid parameters";
    }

    else{
        m_int = toNum(m);
        console.log(m_int);

        res_e = pow(m_int, k, space);
        result.innerText = res_e;
    }
}

function decryptmessage(){
    let c = message.value;
    let space = key_n.value;
    let k = key.value;

    if(!c || !space || !k){
        result.innerText = "invalid parameters";
    }

    else{
        let res_d = pow(c, k, space);
        let res_string = toStr(String(res_d));
        result.innerText = res_string;
    }

}

function pow(a,b, mod){
    if(b==0){return 1;}
    else if(b==1){
        // console.log(a,b);
        return a;}

    else if(b%2){
        let temp = BigInt(pow(a,(b-1)/2, mod));
        // console.log(temp,b);
        return BigInt(((BigInt(a)*temp)%BigInt(mod) * temp)%BigInt(mod));
    }

    else{
        let temp = BigInt(pow(a, b/2, BigInt(mod)));
        // console.log(temp,b);
        return BigInt(((temp*temp)%BigInt(mod)));
    }

}

function toNum(string){
    number = "";
    var length = string.length;
    for (var i = 0; i < length; i++)
        number += (string.charCodeAt(i)+68).toString(10);
    return number;

}

function toStr(number) {
    var string = "";
    var length = number.length;
    for (var i = 0; i < length;) {
        var code = number.slice(i, i += 3);
        string += String.fromCharCode((parseInt(code, 10)-68));
    }
    return string;
}