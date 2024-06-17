function generatemessage(){
    let sender = document.getElementById("sender_t").value;
    let receiver = document.getElementById("receiver_t").value;
    let sum = document.getElementById("sum").value;
    let result = document.getElementById("transaktiontext");

    if(sum == 0){
        return 0;
    }

    var currentdate = new Date(); 

    result.innerText=(sender.trim()+" -> " + sum.trim() + " -> " + receiver.trim() + " " + currentdate.toLocaleString());
}

function getrewardtext(){
    var miner = document.getElementById("miner").value;
    if(!miner){
        result = "missing miner key!"
    } 
    else{
        result = ("100 -> "+miner.trim());
    }
    document.getElementById("reward").value = result;
}

function copyhashtext(){
    var prevhash = document.getElementById("prevhash");
    var t1 = document.getElementById("t1");
    var t2 = document.getElementById("t2");
    var t3 = document.getElementById("t3");
    var miner = document.getElementById("miner");
    var reward = document.getElementById("reward");
   
    if(prevhash == null || t1 == null || t2 == null || t3 == null || miner == null || reward == null){
        document.getElementById("error").textContent = "invalid values!";
    }
    else{
        var text = String(prevhash.value+t1.textContent+t2.textContent+t3.textContent+'{"receiver": "'+miner.value+'","text":"'+reward.value+'"}')
        console.log(text);
        text = text.replace(/(\r\n|\n|\r\s|\s)/gm, '');
        console.log(text);

        navigator.clipboard.writeText(text);

    }

}