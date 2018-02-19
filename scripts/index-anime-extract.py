#!/usr/bin/python

import os, sys, getopt, collections
from lxml import etree as ET
from operator import itemgetter
from collections import Counter

def getkey(elem):
    return elem.findtext("field[@name='id']")

def main(argv):
    path = ''
    filename = ''
    try:
        opts, args = getopt.getopt(argv,"hi:o:",["ifile=","ofile="])
    except getopt.GetoptError:
        print 'test.py -i <path> -o <filename>'
        sys.exit(2)
    for opt, arg in opts:
        if opt == '-h':
            print 'test.py -i <path> -o <filename>'
            sys.exit()
        elif opt in ("-i", "--ifile"):
            path = os.path.join(arg)
        elif opt in ("-o", "--ofile"):
            filename = arg
    #print 'Working path is ', path
    print 'Working file is ', filename
    
    tree = ET.parse(filename)
    root = tree.getroot()
    
    #root[:] = sorted(root, key=getkey)
    
    new_root = ET.Element("add")
    duplicated_doc_count = 0
    queue = collections.deque('',12)
    for doc in root.findall("doc"):
        if doc.find("field[@name='cl_hi']").text in queue:
            duplicated_doc_count += 1
        else:
            queue.append(doc.find("field[@name='cl_hi']").text)
            new_doc = ET.SubElement(new_root, "doc")
            ET.SubElement(new_doc, "field", name="id").text = doc.find("field[@name='id']").text
            ET.SubElement(new_doc, "field", name="cl_hi").text = doc.find("field[@name='cl_hi']").text
            ET.SubElement(new_doc, "field", name="cl_ha").text = doc.find("field[@name='cl_ha']").text
            
    #new_root[:] = sorted(new_root, key=getkey)
    new_tree = ET.ElementTree(new_root)
    new_tree.write(path+"tmp.xml", encoding="UTF-8", xml_declaration=True, pretty_print=True)
    
if __name__ == "__main__":
    main(sys.argv[1:])

