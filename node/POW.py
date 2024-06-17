from hashlib import sha256

input_ = "e"
print(sha256(input_.encode('utf-8')).hexdigest())