import re

# 1. FIX products/create.blade.php
with open('resources/views/products/create.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()
    
# Remove bg-white at the end of classes
content = content.replace('outline-none bg-white">', 'outline-none dark:bg-primary">')
content = content.replace('bg-gray-100 text-secondary', 'bg-gray-100 dark:bg-gray-800 text-secondary')
content = content.replace('font-bold flex', 'font-bold flex') # To just ensure no change needed
with open('resources/views/products/create.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

# 2. FIX products/edit.blade.php
with open('resources/views/products/edit.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace('outline-none bg-white">', 'outline-none dark:bg-primary">')
content = content.replace('bg-gray-100 text-secondary', 'bg-gray-100 dark:bg-gray-800 text-secondary')
with open('resources/views/products/edit.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
    
# 3. FIX posts/create.blade.php
with open('resources/views/posts/create.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Fix the messed up classes
content = content.replace('class="appearance-none w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors bg-transparent text-primary dark:text-white dark:bg-transparent text-primary dark:text-white focus:ring-2 focus:ring-gray-500 outline-none transition-all cursor-pointer"', 'class="appearance-none w-full px-4 py-3 bg-transparent dark:bg-primary border border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-gray-500 cursor-pointer"')
content = content.replace('class="appearance-none w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors bg-transparent text-primary dark:text-white dark:bg-transparent text-primary dark:text-white focus:ring-2 focus:ring-gray-500 outline-none transition-all cursor-pointer @error(\'category_id\') border-red-500 @enderror"', 'class="appearance-none w-full px-4 py-3 bg-transparent dark:bg-primary border border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-gray-500 cursor-pointer @error(\'category_id\') border-red-500 @enderror"')
content = content.replace('bg-white hover:bg-transparent text-primary dark:text-white dark:bg-transparent', 'bg-transparent text-primary dark:text-white')
content = content.replace('class="block w-full text-sm text-secondary dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:dark:bg-gray-800 file:text-gray-800 hover:file:bg-gray-300 cursor-pointer focus:outline-none @error(\'image\') border-red-500 @enderror"', 'class="block w-full text-sm text-secondary dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-800 dark:file:bg-gray-800 dark:file:text-white cursor-pointer focus:outline-none"')

with open('resources/views/posts/create.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

