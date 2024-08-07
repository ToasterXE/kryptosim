#include <iostream>
#include "lib/picosha.cpp"

using namespace std;
using namespace picosha2;

const int numzeros = 3;

bool test(string hex){
    for(int i = 0; i<numzeros; i++){
        if(hex[i] != *"0"){
            return 0;
        }
    }
    return 1;
}

int main(){
    cout<<"enter data of block to mine"<<"\n";
    string data;
    cin>>data;
    vector<unsigned char> hash(k_digest_size);
    hash256(data.begin(), data.end(), hash.begin(), hash.end());

    string hex_str = bytes_to_hex_string(hash.begin(), hash.end());
    // cout<<hex_str<<"\n";w
    long long pow = 0;
    bool found = 0;
    cout<<"mining block"<<"\n";
    while(!found){
        string temp = data+to_string(pow);
        hash = vector<unsigned char>(k_digest_size);
        hash256(temp.begin(), temp.end(), hash.begin(), hash.end());
        found = test(bytes_to_hex_string(hash.begin(),hash.end()));
        pow++;
    }
    cout<<"solution found: "<<pow-1<<"\n";
    string s;
    cin>>s;
}