calc(){ awk "BEGIN { print $* }"; }



echo "" >> ./map-sepia.css
i=0
while [ $i -ne 101 ]
do

    value=$(calc 1-$i/100)
    echo "#map-holder .sepia-$i {filter:sepia($value);}" >> map-sepia.css;


    i=$((i+1))

done