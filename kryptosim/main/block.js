
blocks = document.getElementsByClassName("block");
var currenthash;

async function update_blocks(){
  var lasthash = blocks[0].querySelector('.header').textContent;
  var header, headertext, text, block;

  for(let i = 0; i<blocks.length; i++){
    block =  blocks[i];  
    header = block.querySelector('.header');
    headertext = header.textContent;

    if(lasthash !== headertext){
      header.textContent = lasthash;
    }
    
    lasthash = blocks[i].querySelector('#hash').textContent;
    text = (block.querySelector('.liste')).textContent;
    await hash(headertext+text).then((e) => {test(e)});
    console.log(currenthash);
  }
}
 
function test(str){
  console.log(str);
  currenthash = str;
  console.log(currenthash);
}


function verify_blocks(){
  var lastheader = blocks[0].querySelector('.header').textContent;
  var header, headertext, block;
  for(let i = 0; i<blocks.length; i++){
    block =  blocks[i];  
    header = block.querySelector('.header');
    headertext = header.textContent;

    if(lastheader !== headertext){
      header.classList.toggle("invalid");
    }
    
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