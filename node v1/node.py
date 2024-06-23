import requests
import time
from bs4 import BeautifulSoup
import json
from datetime import datetime
from hashlib import sha256


headers = {'User-Agent': 'Mozilla/5.0'}
daten = {'test': '1'}

session = requests.Session()
NODEID = 8
file_path = 'blockchain.txt'
nodedata = {}
# NODEID  = getnodeid()
run = True

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
    stringhash = string.replace(" ","")
    hash = (sha256(stringhash.encode('utf-8')).hexdigest())
    
    if(hash != blockjson['hash']):
        return 0
    
    for i in range(0,6):
        if(hash[i] != "0"):
            return 0
    
    with open(file_path, 'a') as file:
        file.write("\n")    
        file.write(string+" _ "+hash)

    return 1

def verifyblockchain():
    
    with open(file_path, 'r') as file:
        line = file.readline()
        prevhash=line.split(" ")[1]
        while(line):
            line = file.readline()
            data = line.split(" _ ")
            if(len(data) != 2):
                continue
            dataarr = data[0].split("{")
            for i in range(1,len(dataarr)):
                dataarr[i] = "{" + dataarr[i]
            if(not verifytransaction(dataarr[1]) or not verifytransaction(dataarr[2]) or not verifytransaction(dataarr[3])):
               return 0
            if(prevhash.strip() != dataarr[0].strip()):
                return 0
            prevhash = data[1]
            stringhash = data[0].replace(" ","")
            hash = (sha256(stringhash.encode('utf-8')).hexdigest())
            if(hash.strip() != prevhash.strip()):
                return 0
    
    file.close()

    return 1

def sendblockchain():
    file_path = 'blockchain.txt'
    with open(file_path, 'r') as file:
        data = file.read()
    nodedata = {'nodeid': NODEID, 'blockchaindata': data.strip()}
    session.post('https://kryptosim.eu/node', headers=headers, data=nodedata)

def syncdata():
    requestdata = {'nodeid': NODEID, 'requestdata': 1}
    ans = BeautifulSoup(session.post('https://kryptosim.eu/node', headers=headers, data=requestdata).text, 'html.parser')
    data = ans.find(id="feedback").text.strip()
    dataarr = data.split("DATA: ")
    with open(file_path, "r") as file:
        chain = file.read()
    file.close()
    length = len(chain)
    maxseen = chain
    maxseenval = 0
    seen = {}
    for d in dataarr:
        if(len(d) < length): continue
        d = d[:length].strip()
        if d in seen:
            seen[d] += 1
        else:
            seen[d] = 1
        if(seen[d]> maxseenval):
            maxseenval = seen[d]
            maxseen = d

    with open(file_path, "w") as file:
        file.write(maxseen)
        file.close()
    
    if(maxseenval > 1):
        print("blockchain synchronised with network successfully")

def init():
    global NODEID, run
    initmsg = BeautifulSoup(session.post('https://kryptosim.eu/registernode',headers=headers, data=daten).text, 'html.parser')  
    NODEID = initmsg.find(id="responsetonode").text.strip()
    blockchaindata = initmsg.find(id="blockchaindata").text.strip()
    with open(file_path, "w") as file:
        file.write(blockchaindata)
        file.close()
    syncdata()

    if(verifyblockchain()):
        sendblockchain()
    else:
        print("fatal error in node network :(")
        run = False
    nodedata['nodeid'] = NODEID
    print("initialisation successful")

init()
print("id: " + NODEID)

try:
    lasttime = time.time()
    while(run):
        if(time.time()-lasttime > 5):
            lasttime = time.time()
            print("ping")
            ans = BeautifulSoup(session.post('https://kryptosim.eu/node', headers=headers, data=nodedata).text, 'html.parser')
            feedback = ans.find(id="feedback")

            if(feedback and feedback.text.strip()):
                print(feedback.text.strip())

            transaction = ans.find(id="transaction_id")
            block = ans.find(id="block_id")
            
            if(transaction):
                print(transaction.text.strip())
                if(verifytransaction(transaction.text.strip())):
                    print("transaction verified")
                    verifydata = {'nodeid': NODEID, 'verify': 1}
                else:
                    print("transaction failed validation test")
                    verifydata = {'nodeid': NODEID, 'verify': 0}
                ans = BeautifulSoup(session.post('https://kryptosim.eu/node', headers=headers, data=verifydata).text, 'html.parser')
                verifyfeedback = ans.find(id="transaction_id")
                print(verifyfeedback.text.strip())

            if(block):
                print("received block")
                t1 = ans.find(id="t1").text.strip()
                t2 = ans.find(id="t2").text.strip()
                t3 = ans.find(id="t3").text.strip()
                if(verifyblock(block.text.strip(), t1, t2, t3)):
                    sendblockchain()
                    verifydata = {'nodeid': NODEID, 'verifyblock': 1}
                    print("block verified!")
                else:
                    syncdata()
                    if(verifyblock(block.text.strip(), t1, t2, t3)):
                        sendblockchain()
                        print("blockchain updated and block verified")
                    else:
                        verifydata = {'nodeid': NODEID, 'verifyblock': 0}
                ans = BeautifulSoup(session.post('https://kryptosim.eu/node', headers=headers, data=verifydata).text, 'html.parser')
                verifyfeedback = ans.find(id="block_id")
                print(verifyfeedback.text.strip())


except(KeyboardInterrupt):
    pass