import os

files = [
    'resources/views/products/create.blade.php',
    'resources/views/products/edit.blade.php',
    'resources/views/videos/create.blade.php',
    'resources/views/posts/create.blade.php'
]

for file in files:
    try:
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # fix back button
        content = content.replace('class="text-sm text-gray-600 hover:text-black font-medium"', 'class="text-sm text-secondary hover:text-primary dark:text-gray-400 dark:hover:text-white font-medium transition-colors"')
        
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
    except Exception as e:
        print(f"Error {file}: {e}")
