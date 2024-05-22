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

async function fixinput(event){
  try {
    event.preventDefault();
    const clipboardContents = await navigator.clipboard.readText();
    var currentfeld = document.getElementById("key");
    currentfeld.value = "";
    for (const item of clipboardContents) {
        currentfeld.value += item;
        if(item == " "){
          currentfeld = document.getElementById("space");
          currentfeld.value = "";
        }
        
    }

  } 
  catch (error) {
    console.log(error.message);
  }

}