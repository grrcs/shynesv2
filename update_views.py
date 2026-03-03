import re

def process_file(filename):
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # check if already processed
    if '@extends' in content:
        return
    
    # 1. replace header and navbar with @extends
    content = re.sub(
        r'<!DOCTYPE html>.*?(?:<!-- Main Content -->\s*<div[^>]*>|<!-- Main Content -->\n\s*<div[^>]*>)', 
        '''@extends('layouts.app')

@section('title', 'Tambah Produk - Shyness OS')

@section('content')
    <div class="max-w-4xl mx-auto">''', 
        content, flags=re.DOTALL
    )

    # 2. Add dark mode classes
    content = content.replace('bg-white rounded-xl shadow border border-gray-200', 'bg-white dark:bg-primary border-thin dark:border-gray-800 transition-colors')
    content = content.replace('text-gray-900', 'text-primary dark:text-white')
    content = content.replace('text-gray-700', 'text-secondary dark:text-gray-300')
    content = content.replace('text-gray-500', 'text-secondary dark:text-gray-400')
    content = content.replace('bg-gray-50', 'bg-transparent text-primary dark:text-white dark:bg-transparent')
    content = content.replace('px-4 py-2 border rounded-lg', 'w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors')
    content = content.replace('px-4 py-2 border border-gray-300 rounded-lg', 'w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors')
    content = content.replace('px-4 py-2.5 border border-gray-300 rounded-lg', 'w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors')
    content = content.replace('w-full px-4 py-2 border rounded-lg', 'w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors')
    content = content.replace('w-full px-4 py-2 rounded-lg border border-gray-300', 'w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors')
    
    content = content.replace('bg-gray-100 dark:border-gray-800', 'bg-transparent border-thin dark:border-gray-800')
    content = content.replace('bg-gray-200', 'dark:bg-gray-800')
    content = content.replace('bg-gray-900 text-white', 'bg-primary text-white dark:bg-white dark:text-primary')
    content = content.replace('bg-black', 'bg-black dark:bg-gray-200')

    # footer
    content = re.sub(
        r'</body>\s*</html>',
        '@endsection\n\n@push(\'scripts\')\n<script>\n// Any scripts here\n</script>\n@endpush\n',
        content, flags=re.DOTALL
    )
    
    with open(filename, 'w', encoding='utf-8') as f:
        f.write(content)

process_file('resources/views/products/create.blade.php')

def fix(filename):
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()

    # fix double w-full
    content = content.replace('w-full w-full', 'w-full')
    
    # fix title in videos/create
    if 'videos/create' in filename:
        content = content.replace('Edit Produk - Shyness OS', 'Upload Video - Shyness OS')

    # fix title in posts/create
    if 'posts/create' in filename:
        content = content.replace('Admin - Shyness', 'Tambah Post Baru - Shyness OS')
        
    with open(filename, 'w', encoding='utf-8') as f:
        f.write(content)

for fn in ['resources/views/products/create.blade.php', 'resources/views/products/edit.blade.php', 'resources/views/videos/create.blade.php', 'resources/views/posts/create.blade.php']:
    try:
        fix(fn)
    except:
        pass
