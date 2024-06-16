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

function encryptmessage(messageid = "message", keyid = "key", spaceid = "space", resultid = "result"){
    message = document.getElementById(messageid);
    key_n = document.getElementById(spaceid);
    key = document.getElementById(keyid);
    result = document.getElementById(resultid);
    let m = message.value;
    let space = key_n.value;
    let k = key.value;
    // console.log(m, k, space);
    if(!m || !k || !space){
        result.innerText = "invalid parameters";
    }
    else{
        m_int = toNum(m);
        m_str = m_int.toString();
        console.log(m_int);
        res_ee = "";
        for(let i = 0; i<m_str.length/9; i++){
            // console.log("e", pow(parseInt(m_str.slice(i*9,(i*9+8 > m_str.length ? m_str.length : i*9+9))), k, space));
            res_ee += pow(parseInt(m_str.slice(i*9,(i*9+8 > m_str.length ? m_str.length : i*9+9))), k, space).toString();
            if(res_ee.length %12){
                console.log("e");
                res_eee = res_ee.slice(0,i*12);
                for(let j = 0; j<12-res_ee.length%12; j++){
                    res_eee += "0";
                }
                res_ee = res_eee + res_ee.slice(i*12);
            }
        }
        // res_e = pow(m_int, k, space);
        // console.log(res_e);
        console.log(res_ee);
        result.innerText = res_ee;
    }
}

function decryptmessage(messageid = "message", keyid = "key", spaceid = "space", resultid = "result"){
    message = document.getElementById(messageid);
    key_n = document.getElementById(spaceid);
    key = document.getElementById(keyid);
    result = document.getElementById(resultid);
    let c = message.value;
    let space = key_n.value;
    let k = key.value;
    c = c.trim();

    if(!c){
        c = message.innerText;
        c = c.trim();

    }
    if(!c || !space || !k){
        result.innerText = "invalid parameters";
    }

    else{
        res_dd = "";
        c_str = c.toString();
        for(let i = 0; i<c_str.length/12; i++){
            substr = (c_str.slice(i*12,(i*12+11 > c_str.length ? c_str.length : i*12+12)));
            while(substr[0] == "0"){
                substr = substr.slice(1, substr.length);
            }
            console.log(substr);
            console.log(toStr(pow(parseInt(substr), k, space).toString()));
            res_dd += pow(parseInt(substr), k, space).toString();
        }
        if(!res_dd){
            result.innerText = "invalid paramteres";
        }
        else{
        result.classList.remove("hidden");
        console.log(toStr(res_dd));
        result.innerText = toStr(res_dd);
        let res_d = pow(c, k, space);
        let res_string = toStr(String(res_d));
        // result.innerText = res_string;
        }
    }

}

function pow(a,b, mod){
    if(!b){return 1;}
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