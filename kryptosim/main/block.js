
blocks = document.getElementsByClassName("block");
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