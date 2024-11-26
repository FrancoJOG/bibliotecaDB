
# Define la clase y la función
class Solution(object):
    def isPalindrome(self, x):
        if x < 0 or (x != 0 and x % 10 == 0):
            return False
    
        half = 0
        while x > half:
            half = (half * 10) + (x % 10)
            x = x // 10

        return x == half or x == half // 10

# Crear una instancia de Solution
solution = Solution()

# Probar la función con diferentes números
num = +101  # Cambia este número por el que desees testear
print(f"El numero {num} es un palindromo? {solution.isPalindrome(num)}")