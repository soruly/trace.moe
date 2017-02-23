tmp_path=/tmp/animehashload/
anime_path=/mnt/Data/Anime\ New/
hash_path=/mnt/Data/Anime\ Hash/

cd /home/soruly
#curl -s http://localhost:8983/solr/lireq/update?commit=true -d '<delete><query>*:*</query></delete>' > /dev/null
#echo $1

if [[ "$1" == "$anime_path"*.mp4 ]] ; then
	relative_path="${1//$anime_path/}"
	input_season=$(echo ${relative_path} | cut -d'/' -f1)
	input_series=$(echo ${relative_path} | cut -d'/' -f2)
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

if [ -f "$xmlfile" ] ; then
	echo ${xmlfile}
        #echo Removing old files
        #rm -rf "${tmp_path}"

        #echo Creating temp directory
        #mkdir -p "${tmp_path}"

	#echo Parsing input XML
        #python index-anime-extract.py -i "${tmp_path}" -o "${xmlfile}"

	#echo Copy back parsed XML
	#cp "${tmp_path}tmp.xml" "${xmlfile}"

	echo Updating Solr
	#curl -s http://192.168.2.11:8983/solr/anime_cl/update  -H "Content-Type: text/xml" --data-binary @"${tmp_path}tmp.xml" > /dev/null
	curl -s http://127.0.0.1:8983/solr/anime_cl/update  -H "Content-Type: text/xml" --data-binary @"${xmlfile}" > /dev/null
	curl -s http://127.0.0.1:8983/solr/anime_cl/update  -H "Content-Type: text/xml" --data-binary "<commit/>" > /dev/null

	#echo Removing temp files
        #rm -rf "${tmp_path}"

	echo Completed
fi
