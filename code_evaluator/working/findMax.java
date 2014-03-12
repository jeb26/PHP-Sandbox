public class findMax { 
	 public static void main(String[] args) { 
	 	 int test = findMax(Integer.parseInt(args[1]), Integer.parseInt(args[2])); 
	 	 System.out.println(test); 
	 } 
	def findMax(a,b):
    if a > b:
        return a
    else:
        return b
} 
