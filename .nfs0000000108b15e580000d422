#!/bin/bash

# query the NCBI protein database for protein family and taxonomy group
# return max=20 results
# convert FASTA to json
# code reference: the parser from fasta to JSON is generated with the help of ChatGPT

FAMILY=$1
TAXONOMY=$2

QUERY="$FAMILY AND $TAXONOMY"
# echo $QUERY

EDIRECT=/home/s2890444/edirect

$EDIRECT/esearch -db protein -query "$QUERY" \
| $EDIRECT/efetch -format fasta \
| awk '
BEGIN {
    print "["
    first=1
    count=0
    done=0
}

/^>/ {
    if (seq != "") {

        if (!first) print ","
        first=0

        printf "{\"id\":\"%s\",\"name\":\"%s\",\"sequence\":\"%s\",\"length\":%d}",
            id, name, seq, length(seq)

        count++

        if (count >= 20) {
            print "\n]"
            done=1
            exit
        }
    }

    seq=""

    id=$1
    sub(/^>/, "", id)

    name=$0
    sub(/^>[^ ]+ /, "", name)

    next
}

{
    seq = seq $0
}

END {
    if (!done) {
        if (count < 20 && seq != "") {
            if (!first) print ","
            printf "{\"id\":\"%s\",\"name\":\"%s\",\"sequence\":\"%s\",\"length\":%d}",
                id, name, seq, length(seq)
        }
        print "\n]"
    }
}'
