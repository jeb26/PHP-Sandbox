from sys import argv

filename, first, second = argv

def findMax(a,b):
	if a > b:
		return a
	else:
		return b
ans = findMax(first,second)

print(ans)
#sys.stdout.flush()
