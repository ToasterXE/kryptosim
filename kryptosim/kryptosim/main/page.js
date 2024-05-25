var cpage = 0;
var items = document.getElementsByClassName("useritem");
var itemsperpage = 12;

function page(page){
var pagenum = document.getElementById("pagenum");

    cpage += page;

    for(let i = 0; i<items.length; i++){
        if(i>=itemsperpage*cpage && i<itemsperpage*cpage+itemsperpage){
            items[i].classList.remove("hidden");
            continue;
        }
        items[i].classList.add("hidden");
    }

    pagenum.innerText = "Page "+(cpage+1)+" of "+(Math.floor(items.length/itemsperpage) + 1); 
    if(cpage == 0){
        document.getElementById("prev").disabled = true;
    }
    else{
        document.getElementById("prev").disabled = false;
    }

    if(cpage == Math.floor(items.length/itemsperpage)){
        document.getElementById("next").disabled = true;
    }
    else{
        document.getElementById("next").disabled = false;
    }

}