let createButton = document.querySelector("create");

function showlogin(){
  document.getElementById("dtl").classList.toggle("show");
}

function test(){
    console.log(this.responsetext);
}


const req = new XMLHttpRequest();

function copy(id){
  // Get the text field
  var copyText = document.getElementById(id);

  // Select the text field
  // copyText.select();
  // copyText.setSelectionRange(0, 99999); // For mobile devices

   // Copy the text inside the text field
   console.log(copyText.textContent.trim());
  navigator.clipboard.writeText(copyText.textContent.trim());


}

async function fixinput(event, keyid = "key", spaceid = "space"){
  try {
    event.preventDefault();
    const clipboardContents = (await navigator.clipboard.readText()).trim();
    var currentfeld = document.getElementById(keyid);
    currentfeld.value = "";
    for (const item of clipboardContents) {
        currentfeld.value += item;
        if(item == " "){
          currentfeld = document.getElementById(spaceid);
          currentfeld.value = "";
        }
        
    }

  } 
  catch (error) {
    console.log(error.message);
  }

}