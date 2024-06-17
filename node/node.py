import requests
import time
from bs4 import BeautifulSoup
import json
from datetime import datetime
from hashlib import sha256


headers = {'User-Agent': 'Mozilla/5.0'}
daten = {'test': '1'}

session = requests.Session()

def getnodeid():
    return BeautifulSoup(session.post('https://kryptosim.eu/registernode',headers=headers, data=daten).text, 'html.parser').find(id="responsetonode").text.strip()

def verifytransaction(jsondata):
    data = json.loads(jsondata)
    d = decrypt(data['text'], int(data['sender'].split(" ")[0]), int(data['sender'].split(" ")[1]))
    d_split = d.split()
    # print(d)
    if(d_split[0] + " " + d_split[1] == data['sender']):
        # print("e")
        return True
    # print(datetime.strptime(data['date'], "%Y-%m-%d %H:%M:%S"))
    # print(datetime.strptime(d_split[7]+d_split[8], "%d.%m.%Y,%H:%M:%S"))
    # if(datetime.strptime(data['date'], "%Y-%m-%d %H:%M:%S") == datetime.strptime(d_split[7]+d_split[8], "%d.%m.%Y,%H:%M:%S")):
    #     print("ee")

def decrypt(message, key, space):
    res_dd = ""
    for i in range(0, int(len(message)/12)):
        substr = message[i*12 : min(i*12+12, len(message))]
        while(substr[0] == "0"):
            substr = substr[1:]
        res_dd += str(pow(int(substr), key, space))
    return toStr(res_dd)

def toStr(number):
    res = ""
    length = len(number)
    i = 0
    while i < length:
        code = number[i : i+3]
        i+=3
        res += chr(int(code)-68)
    return res

def verifyblock(blockdata, t1, t2, t3):
    file_path = 'blockchain.txt'
    with open(file_path, 'r') as file:
        data = file.read()
    file.close()
    data = data.split(" ")
    prevhash = data[-1]
    blockjson = json.loads(blockdata)
    t1json = json.loads(t1)
    t2json = json.loads(t2)
    t3json = json.loads(t3)

    if(prevhash != blockjson['header']):
        return 0
    string = prevhash+t1+t2+t3+'{"receiver": "'+ blockjson['miner']+'","text":"'+str(blockjson['reward'])+'->'+blockjson['miner']+'"}'+str(blockjson['pow'])
    string = string.replace("\'","\"")
    string = string.replace(" ","")
    hash = (sha256(string.encode('utf-8')).hexdigest())
    
    if(hash != blockjson['hash']):
        return 0
    
    for i in range(0,6):
        if(hash[i] != "0"):
            return 0
    
    with open(file_path, 'a') as file:
        file.write("\n")    
        file.write(string+" "+hash)

    return 1
    # print(t1)


# NODEID  = getnodeid()
NODEID = 8
print(NODEID)
run = True

nodedata = {'nodeid': NODEID}

try:
    lasttime = time.time()
    while(run):
        if(time.time()-lasttime > 5):
            lasttime = time.time()
            print("ping")
            ans = session.post('https://kryptosim.eu/node', headers=headers, data=nodedata)
            transaction = BeautifulSoup(ans.text, 'html.parser').find(id="transaction_id")
            block = BeautifulSoup(ans.text, 'html.parser').find(id="block_id")
            if(transaction):
                print(transaction.text.strip())
                if(verifytransaction(transaction.text.strip())):
                    print("transaction verified")
                    verify = {'nodeid': NODEID, 'verify': 1}
                else:
                    print("transaction failed validation test")
                    verify = {'nodeid': NODEID, 'verify': 0}
                ans = session.post('https://kryptosim.eu/node', headers=headers, data=verify)
                transaction = BeautifulSoup(ans.text, 'html.parser').find(id="transaction_id")
                print(transaction.text.strip())

            if(block):
                print("received block")
                t1 = BeautifulSoup(ans.text, 'html.parser').find(id="t1")
                t2 = BeautifulSoup(ans.text, 'html.parser').find(id="t2")
                t3 = BeautifulSoup(ans.text, 'html.parser').find(id="t3")
                if(verifyblock(block.text.strip(), t1.text.strip(), t2.text.strip(), t3.text.strip())):
                    verify = {'nodeid': NODEID, 'verifyblock': 1}
                    print("block verified!")
                else:
                    verify = {'nodeid': NODEID, 'verifyblock': 0}
                ans = session.post('https://kryptosim.eu/node', headers=headers, data=verify)
                transaction = BeautifulSoup(ans.text, 'html.parser').find(id="block_id")
                print(transaction.text.strip())


except(KeyboardInterrupt):
    pass