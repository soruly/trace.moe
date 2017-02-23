tmp_path=/tmp/animehashload/
anime_path=/mnt/Data/Anime\ New/
hash_path=/mnt/Data/Anime\ Hash/

cd /home/soruly
input_path="$1"
if [[ "$1" != */ ]] ; then
	input_path="$1"/
fi

if [[ -d $input_path ]] ; then
if [[ "$input_path" == "$anime_path"* ]] ; then
        relative_path="${1//$anime_path/}"
        input_season=$(echo ${relative_path} | cut -d'/' -f1 -s)
        input_series=$(echo ${relative_path} | cut -d'/' -f2 -s)
	if [ "$input_season" ] && [ "$input_series" ] ; then
                        if [[ -d $input_path ]]; then
                        for file in "$input_path"*.mp4
                        do
				#echo "$file"
                                /home/soruly/index-anime-load.sh "$file"
                        done
                        fi
	elif [ "$input_season" ] ; then
                if [[ -d $input_path ]]; then
                for series in "$input_path"*
                do
                        if [[ -d $series ]]; then
                        for file in "$series"/*.mp4
                        do
				#echo "$file"
                                /home/soruly/index-anime-load.sh "$file"
                        done
                        fi
                done
                fi
	else
	for season in "$anime_path"*
	do
		if [[ -d $season ]]; then
                for series in "$season"/*
                do
			if [[ -d $series ]]; then
                	for file in "$series"/*.mp4
                        do
				#echo "$file"
				/home/soruly/index-anime-load.sh "$file"
                        done
			fi
		done
		fi
	done
	fi
else
        echo Input path is not anime
fi
else
	echo Input path is not directory
fi
