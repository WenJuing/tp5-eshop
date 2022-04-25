import sys
import codecs

# 解决php获取中文数据乱码问题
sys.stdout = codecs.getwriter('utf-8')(sys.stdout.detach())
print("我是Python文件")
print(int(sys.argv[1]) * int(sys.argv[2]))