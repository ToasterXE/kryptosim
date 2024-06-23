var realhash;

async function update_blocks(){
  var oldhash = blocks[0].querySelector('.header').textContent;
  var header, headertext, text, block;

  for(let i = 0; i<blocks.length; i++){
    block =  blocks[i];  
    header = block.querySelector('#header');
    currenthash = block.querySelector('#hash').textContent;
    headertext = header.textContent;

    if(oldhash !== headertext){
      header.textContent = oldhash;
    }
    
    text = (block.querySelector('.liste')).textContent;
    var input = oldhash.replace(/\s+/g, '')+text.replace(/\s+/g, '');
    await hash(input).then((e) => {realhash = e});
    // console.log(input, realhash);
    if(currenthash !== realhash){

      block.querySelector('#hash').textContent = realhash;
    }
    oldhash = block.querySelector('#hash').textContent;
    

  }
}

async function verify_blocks(){
  var blocks = document.getElementsByClassName("block");
  // console.log(blocks.length);
  prevhash = "0";
  for(let i = blocks.length-1; i>=0; i--){
    string = "";
    header = blocks[i].getElementsByClassName("headerid")[0];
    string += header.textContent.trim();
    liste = blocks[i].getElementsByClassName("liste")[0];
    transactions = liste.getElementsByClassName("t");

    for(let j = 0; j<(4-transactions.length); j++){
      string += "{}";
    }


    for(let j = 0; j<transactions.length; j++){
      string += transactions[j].value.replace(/(\r\n|\n|\r\s|\s)/gm, '');

    }
    pow = blocks[i].getElementsByClassName("pow")[0];
    string += pow.textContent.trim();
    hashdiv = blocks[i].getElementsByClassName("hash")[0];
    chash = hashdiv.textContent.trim();

    if(prevhash == header.textContent.trim()){
      prevhash = chash;
      header.classList.add("verified");
      header.classList.remove("invalid");
    }
    else{
      header.classList.remove("verified");
      header.classList.add("invalid");
    }

    realhash = hash(string);
    if((await realhash) == chash && (await realhash).toString().slice(0,6) == "000000"){
      hashdiv.classList.add("verified");
      hashdiv.classList.remove("invalid");
    }
    else{
      hashdiv.classList.add("invalid");
      hashdiv.classList.remove("verified");
    }

  }
}



async function verify_blocks_old(){
  var lasthash = blocks[0].querySelector('#header').textContent;
  var header, headertext, block, headerdiv;
  for(let i = 0; i<blocks.length; i++){
    block =  blocks[i];  
    headerdiv = block.querySelector('#header');
    hashdiv = block.querySelector('#hash');

    text = (block.querySelector('.liste')).textContent;
    headertext = headerdiv.textContent;
    hashtext = hashdiv.textContent;

    headerdiv.classList.remove("invalid");
    headerdiv.classList.remove("verified");
    hashdiv.classList.remove("invalid");
    hashdiv.classList.remove("verified");

    var realhash;
    var input = headertext.replace(/\s+/g, '')+text.replace(/\s+/g, '');
    await hash(input).then((e) => {realhash = e});
    // console.log(realhash, hashtext);
    if(lasthash !== headertext){
      headerdiv.classList.add("invalid");
    }
    else{
      headerdiv.classList.add("verified");
    }
    if(hashtext !== realhash){
      hashdiv.classList.add("invalid");
    }
    else{
      hashdiv.classList.add("verified");
    }

    lasthash = block.querySelector('#hash').textContent;
    
  }
}


async function hash(string) {
    const string_encode = new TextEncoder().encode(string);
    const hashBuffer = await crypto.subtle.digest('SHA-256', string_encode);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashHex = hashArray.map((bytes) => 
      bytes.toString(16).padStart(2, '0')).join('');
      return hashHex;
}