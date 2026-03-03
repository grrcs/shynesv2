import re

filepath = 'resources/views/videos/index.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace classes for Dark Mode
content = content.replace('text-gray-900', 'text-primary dark:text-white')
content = content.replace('text-gray-500', 'text-secondary dark:text-gray-400')
content = content.replace('bg-white rounded-xl shadow-sm border border-gray-200', 'bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 transition-colors')
content = content.replace('border-gray-100', 'border-gray-100 dark:border-gray-800')
content = content.replace('text-gray-400', 'text-gray-400 dark:text-gray-500')
content = content.replace('bg-gray-900 text-white', 'bg-primary text-white dark:bg-white dark:text-primary')
content = content.replace('hover:bg-black', 'hover:bg-black dark:hover:bg-gray-200')
content = content.replace('bg-gray-100 mb-4', 'bg-gray-100 dark:bg-gray-800 mb-4')

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
