
import time
 
try:
    num = 11
    while True: 
        if num % 2 == 0:
            break
        print(num)
        num = num + 2
        time.sleep(1)  # Using to slow the while loop by one second 🙂
except KeyboardInterrupt:
    pass
 
print("Continuing with the program")
