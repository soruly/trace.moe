find "/mnt/Data/Anime New" \
 -type f \
 -name "*.mp4" \
 -mmin -180 \
 -not \( \
 -path "/mnt/Data/Anime New/HKTVBJ2/*" \
 -prune \
 \) \
 -not \( \
 -path "/mnt/Data/Anime New/Others/*" \
 -prune \
 \) \
 -exec /home/soruly/index-anime.sh "{}" \;

find "/mnt/Data/Anime New/Others/Naruto Shippuuden" \
 -type f \
 -name "*.mp4" \
 -mmin -180 \
 -exec /home/soruly/index-anime.sh "{}" \;

find "/mnt/Data/Anime New/Others/One Piece" \
 -type f \
 -name "*.mp4" \
 -mmin -180 \
 -exec /home/soruly/index-anime.sh "{}" \;

