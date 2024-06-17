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

# NODEID  = getnodeid()
NODEID = 6
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

except(KeyboardInterrupt):
    pass