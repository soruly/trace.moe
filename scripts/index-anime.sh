#!/bin/bash

tmp_path=/tmp/animehash/
anime_path=/mnt/data/anime_new/
hash_path=/mnt/data/anime_hash/

cd /home/soruly
#curl -s http://localhost:8983/solr/lireq/update?commit=true -d '<delete><query>*:*</query></delete>' > /dev/null
echo $1

if [[ ! -f index-anime.lock ]] ; then
  touch index-anime.lock

if [[ "$1" == "$anime_path"*.mp4 ]] ; then
	relative_path="${1//$anime_path/}"
	input_season=$(echo ${relative_path} | cut -d'/' -f1)
	input_series=$(echo ${relative_path} | cut -d'/' -f2)
  file_name=$(basename "$1")
else
	echo Invalid input file
	exit
fi
	file="$1"
	#echo "${file}"
	#continue
xmlxxx="${file%.mp4}.xml"
xmlfile="${xmlxxx//$anime_path/$hash_path}"
xmlpath=$(dirname "$xmlfile")

if [ ! -f "$xmlfile" ] ; then
	echo Removing old files
	rm -rf "${tmp_path}"

	echo Creating temp directory
	mkdir -p "${tmp_path}"

	echo Extracting thumbnails
	ffmpeg -i "$file" -q:v 2 -an -vf "fps=12,scale=-1:120,showinfo" "${tmp_path}%08d.jpg" 2>&1 | grep pts_time | awk -F"(pos:|pts_time:)" '{print $2}' | tr -d ' ' > "${tmp_path}pts_time.txt"

	echo Preparing frame files for analysis
	find "${tmp_path}" -mindepth 1 -maxdepth 1 -type f -name "*.jpg" -printf "${tmp_path}%f\n" | sort > "${tmp_path}frames.txt"

	echo Analyzing frames
	java -jar /var/solr/data/lib/lire-request-handler.jar -i "${tmp_path}frames.txt" -o "${tmp_path}tmp.xml" -n 8 -f
	#java -jar /home/soruly/liresolr/dist/lire-request-handler.jar -i "${tmp_path}frames.txt" -o "${tmp_path}tmp.xml" -n 8 -f

	echo Parsing output XML
	python index-anime-parse.py -i "${tmp_path}" -o "${file//$anime_path/}"
	
        echo Parsing input XML
        python index-anime-extract.py -i "${tmp_path}" -o "${tmp_path}analyzed.xml"

        echo Copy back parsed XML
        mkdir -p "$xmlpath"
        cp "${tmp_path}tmp.xml" "${xmlfile}"

        echo Updating Solr
        curl -s http://192.168.2.11:8983/solr/anime_cl/update  -H "Content-Type: text/xml" --data-binary @"${tmp_path}tmp.xml" > /dev/null
        curl -s http://192.168.2.11:8983/solr/anime_cl/update  -H "Content-Type: text/xml" --data-binary "<commit/>" > /dev/null

	echo Removing temp files
	rm -rf "${tmp_path}"

	echo Completed

fi

rm index-anime.lock

else
  echo "Another process is running"
fi
