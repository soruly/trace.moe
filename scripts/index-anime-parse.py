#!/usr/bin/python

import os, sys, getopt
import xml.etree.ElementTree as ET

def getkey(elem):
    return elem.findtext("field[@name='title']")

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
    #print 'Working file is ', filename
    
    tree = ET.parse(path+'tmp.xml')
    root = tree.getroot()
    
    root[:] = sorted(root, key=getkey)
    
    with open(path+'pts_time.txt') as pts_time:
        pts = pts_time.readlines()
    if pts[0] != "0" :
        pts.insert(0,"0")
    pts.append(pts[-1])
    pts.append(pts[-1])
    pts.append(pts[-1])
    pts.append(pts[-1])
    pts.append(pts[-1])
    pts.append(pts[-1])
    pts.append(pts[-1])
    pts.append(pts[-1])
    
    i=0
    for doc_title in root.findall("./doc/field[@name='title']"):
        doc_title.text = filename.decode('utf-8')+"?t="+pts[i].strip('\n')
        i+=1
    
    i=0
    for doc_id in root.findall("./doc/field[@name='id']"):
        doc_id.text = filename.decode('utf-8')+"?t="+pts[i].strip('\n')
        i+=1
    
    tree.write(path+"analyzed.xml",encoding="UTF-8",xml_declaration=True)
    
if __name__ == "__main__":
    main(sys.argv[1:])
