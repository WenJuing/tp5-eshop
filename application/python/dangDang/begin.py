from scrapy import cmdline
import sys
import codecs

sys.stdout = codecs.getwriter('utf-8')(sys.stdout.detach())
# 启动爬虫脚本
# cmdline.execute('scrapy crawl top250'.split())
# cmdline.execute('scrapy crawl books -a num=1 -a url=http://bang.dangdang.com/books/bestsellers/01.00.00.00.00.00-24hours-0-0-1-'.split())
t = 'scrapy crawl books -a num='+str(sys.argv[2])+' -a url=http://bang.dangdang.com/books/'+sys.argv[1]
print(t)
cmdline.execute(t.split())

