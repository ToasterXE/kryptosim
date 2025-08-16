function page(itemsperpage = 10, page = 0){
    var items = document.getElementsByClassName("useritem");
    
    for(i in items){
        if(itemsperpage){
            itemsperpage--;
        }
        else{
            i.classlist.add("hidden");
        }
    }

}