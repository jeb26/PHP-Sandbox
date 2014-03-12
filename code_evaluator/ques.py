'''
    wordStats():
    Write a function called wordStats() that takes two parameters.
    1. a sentence of words, 2. an int, the function returns the
    number of words in the string whose length is larger than the int.
'''

def wordStats(sen, num):
    words = sen.split()
    count = 0;
    
    for word in words:
        if len(word) > num:
            count += 1

    return count

'''
    findMax():
    Write a function called findMax() which takes two parameters.
    each parameter is a positive integer. the program returns the
    largest integer. 
'''

def findMax(a, b):
    largest = 0

    if a > b:
        return a
    else:
        return b



'''
    nthPower():
    Write a function called nthPower() which takes two paramters.
    1. an integer 2. a power. The function returns the nth power of
    the integer.
'''

def nthPower(n, power):
    return pow(n, power)
    
