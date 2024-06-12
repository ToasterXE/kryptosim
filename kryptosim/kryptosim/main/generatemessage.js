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