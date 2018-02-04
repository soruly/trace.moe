#!/bin/bash

find -L "/mnt/data/anime_new" \
 -type f \
 -name "*.mp4" \
 -mmin -1800 \
 -not \( \
 -path "/mnt/data/anime_new/HKTVBJ2/*" \
 -prune \
 \) \
 -not \( \
 -path "/mnt/data/anime_new/Others/*" \
 -prune \
 \) \
 -exec /home/soruly/index-anime.sh "{}" \;

find -L "/mnt/data/anime_new/Others/Naruto Shippuuden" \
 -type f \
 -name "*.mp4" \
 -mmin -1800 \
 -exec /home/soruly/index-anime.sh "{}" \;

find -L "/mnt/data/anime_new/Others/One Piece" \
 -type f \
 -name "*.mp4" \
 -mmin -1800 \
 -exec /home/soruly/index-anime.sh "{}" \;

