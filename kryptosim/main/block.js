import sjcl from 'sjcl'

blocks = document.getElementsByClassName("block");
console.log(blocks);

function update_blocks(){
    for(block of blocks){
        console.log("e");
    }
}

function hash(string) {
    const utf8 = new TextEncoder().encode(string);
    return crypto.subtle.digest('SHA-256', utf8).then((hashBuffer) => {
      const hashArray = Array.from(new Uint8Array(hashBuffer));
      const hashHex = hashArray
        .map((bytes) => bytes.toString(16).padStart(2, '0'))
        .join('');
      return hashHex;
    });
  }

console.log(hash("E"));


const myString = 'Hello'
const myBitArray = sjcl.hash.sha256.hash(myString)
const myHash = sjcl.codec.hex.fromBits(myBitArray)
console.log(myHash);