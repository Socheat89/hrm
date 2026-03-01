import sys
with open('resources/views/admin/settings/edit.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

c = c.replace('<div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6"> \n                          <div class="md:col-span-12">', '<div class="mb-6">\n                          <div>')
c = c.replace('<div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">\n                          <div class="md:col-span-12">', '<div class="mb-6">\n                          <div>')
c = c.replace('<div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">\r\n                          <div class="md:col-span-12">', '<div class="mb-6">\r\n                          <div>')

c = c.replace('class="md:col-span-12 bg-slate-50 rounded-xl', 'class="w-full bg-slate-50 rounded-xl')

with open('resources/views/admin/settings/edit.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)

print('Done')
